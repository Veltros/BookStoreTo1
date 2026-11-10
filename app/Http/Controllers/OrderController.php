<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('warning', 'Keranjang belanja Anda masih kosong.');
        }

        // 1. Ambil daftar item yang dipilih (dari input request atau session)
        $selectedParam = $request->input('selected_items');
        if ($selectedParam !== null) {
            if (is_string($selectedParam)) {
                $selectedIds = array_filter(explode(',', $selectedParam), fn($v) => is_numeric($v));
            } else {
                $selectedIds = (array)$selectedParam;
            }
            $selectedIds = array_map('intval', $selectedIds);
            session()->put('checkout_selected_ids', $selectedIds);
        } else {
            $selectedIds = session()->get('checkout_selected_ids');
        }

        // Filter cart hanya untuk item yang diceklis
        if (!empty($selectedIds)) {
            $checkoutCart = [];
            foreach ($selectedIds as $id) {
                if (isset($cart[$id])) {
                    $checkoutCart[$id] = $cart[$id];
                }
            }
        } else {
            $checkoutCart = $cart;
        }

        if (empty($checkoutCart)) {
            return redirect()->route('cart.index')
                ->with('warning', 'Silakan pilih (centang) minimal 1 buku yang ingin Anda checkout.');
        }

        // 2. Validasi real-time stok & SINKRONISASI HARGA RESMI DATABASE
        $bookIds = array_keys($checkoutCart);
        $books = Book::whereIn('id', $bookIds)->get()->keyBy('id');

        $totalPrice = 0;
        foreach ($checkoutCart as $bookId => &$item) {
            $book = $books->get($bookId);
            $stock = $book ? (int)$book->stock : 0;

            if (!$book || $stock <= 0) {
                return redirect()->route('cart.index')
                    ->with('danger', "Buku '{$item['title']}' sudah habis terjual. Silakan hilangkan centang buku ini atau hapus dari keranjang.");
            }

            if ($item['qty'] > $stock) {
                return redirect()->route('cart.index')
                    ->with('danger', "Jumlah buku '{$item['title']}' melebihi stok yang tersedia saat ini (Sisa stok: {$stock} eks). Silakan sesuaikan kuantitas di keranjang.");
            }

            // Selalu gunakan harga & judul resmi dari database
            $item['price'] = (float)$book->price;
            $item['title'] = $book->title;
            $totalPrice += $item['price'] * $item['qty'];
        }
        unset($item);

        $user = auth()->user();

        // Simpan snapshot checkout_cart ke session untuk diproses di placeOrder
        session()->put('checkout_cart', $checkoutCart);

        return view('orders.checkout', [
            'cart'       => $checkoutCart,
            'totalPrice' => $totalPrice,
            'user'       => $user,
        ]);
    }

    public function placeOrder(Request $request)
    {
        // Gunakan item terpilih dari checkout_cart, fallback ke cart penuh jika checkout_cart belum ada
        $cart = session()->get('checkout_cart', session()->get('cart', []));
        if (empty($cart)) {
            return redirect()->route('home')->with('warning', 'Keranjang belanja Anda kosong.');
        }

        $request->validate([
            'recipient_name'   => 'required|string|max:100',
            'recipient_phone'  => 'required|string|max:25',
            'shipping_address' => 'required|string|min:8',
            'payment_channel'  => 'nullable|string',
            'notes'            => 'nullable|string|max:500',
        ], [
            'recipient_name.required'   => 'Nama penerima paket wajib diisi.',
            'recipient_phone.required'  => 'Nomor telepon/HP penerima wajib diisi.',
            'shipping_address.required' => 'Peringatan: Alamat pengiriman lengkap wajib diisi!',
            'shipping_address.min'      => 'Alamat pengiriman terlalu singkat. Harap cantumkan alamat dengan jelas.',
        ]);

        $user = auth()->user();
        $paymentChannel = $request->input('payment_channel', 'Transfer Bank BCA');
        $paymentMethod = 'Payment at Delivery (' . $paymentChannel . ')';

        try {
            $order = DB::transaction(function () use ($request, $user, $cart, $paymentMethod) {
                // 1. Verifikasi stok dengan pessimistic lock & hitung total berdasarkan harga database murni
                $verifiedItems = [];
                $totalAmount = 0;

                foreach ($cart as $bookId => $item) {
                    $book = Book::lockForUpdate()->find($bookId);
                    if (!$book) {
                        throw new \Exception("Buku '{$item['title']}' sudah tidak tersedia di katalog.");
                    }
                    if ($book->stock < $item['qty']) {
                        throw new \Exception("Stok untuk buku '{$book->title}' tidak mencukupi (Tersedia: {$book->stock}, Diminta: {$item['qty']}).");
                    }

                    // Ambil harga resmi dari database yang di-lock, bukan dari session
                    $unitPrice = (float)$book->price;
                    $subtotal = $unitPrice * $item['qty'];
                    $totalAmount += $subtotal;

                    $verifiedItems[] = [
                        'book_id'    => $book->id,
                        'book_title' => $book->title,
                        'price'      => $unitPrice,
                        'quantity'   => $item['qty'],
                        'subtotal'   => $subtotal,
                    ];
                }

                // 2. Buat order number
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                // 3. Simpan Order
                $order = Order::create([
                    'order_number'     => $orderNumber,
                    'user_id'          => $user->id,
                    'recipient_name'   => $request->recipient_name,
                    'recipient_phone'  => $request->recipient_phone,
                    'shipping_address' => $request->shipping_address,
                    'payment_method'   => $paymentMethod,
                    'payment_status'   => 'Menunggu Pembayaran',
                    'order_status'     => 'Menunggu Konfirmasi',
                    'total_amount'     => $totalAmount,
                    'notes'            => $request->notes,
                ]);

                // 4. Simpan Order Items dan kurangi stok
                foreach ($verifiedItems as $vItem) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'book_id'    => $vItem['book_id'],
                        'book_title' => $vItem['book_title'],
                        'price'      => $vItem['price'],
                        'quantity'   => $vItem['quantity'],
                        'subtotal'   => $vItem['subtotal'],
                    ]);

                    Book::where('id', $vItem['book_id'])->decrement('stock', $vItem['quantity']);
                }

                // 5. Update kontak user jika masih kosong
                if (empty($user->phone) || empty($user->address)) {
                    $user->update([
                        'phone'   => $user->phone ?: $request->recipient_phone,
                        'address' => $user->address ?: $request->shipping_address,
                    ]);
                }

                return $order;
            });

            // 6. Hapus HANYA item yang dibeli dari session & database, item yang tidak dicentang tetap aman di keranjang
            $fullCart = session()->get('cart', []);
            foreach (array_keys($cart) as $boughtBookId) {
                unset($fullCart[$boughtBookId]);
                CartItem::where('user_id', $user->id)->where('book_id', $boughtBookId)->delete();
            }
            session()->put('cart', $fullCart);
            session()->forget('checkout_cart');
            session()->forget('checkout_selected_ids');

            return redirect()->route('orders.success', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran sebelum buku dikirim.');

        } catch (\Exception $e) {
            return back()->withInput()->with('danger', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load('items.book');
        return view('orders.success', compact('order'));
    }

    public function myOrders()
    {
        $orders = Order::with('items.book')
            ->where('user_id', auth()->id())
            ->orderByDesc('id')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load('items.book');
        return view('orders.show', compact('order'));
    }
}
