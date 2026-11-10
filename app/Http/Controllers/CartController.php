<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Sinkronisasi data keranjang antara session dan database
     */
    public static function syncUserCartWithDatabase()
    {
        if (!auth()->check()) {
            return;
        }

        $userId = auth()->id();
        $sessionCart = session()->get('cart', []);

        // 1. Simpan / gabungkan item dari session ke database
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $bookId => $item) {
                $book = Book::find($bookId);
                if ($book && $book->stock > 0) {
                    $existing = CartItem::where('user_id', $userId)->where('book_id', $bookId)->first();
                    $qtyToSet = $existing ? min($existing->quantity + $item['qty'], $book->stock) : min($item['qty'], $book->stock);
                    
                    if ($qtyToSet > 0) {
                        CartItem::updateOrCreate(
                            ['user_id' => $userId, 'book_id' => $bookId],
                            ['quantity' => $qtyToSet]
                        );
                    }
                }
            }
        }

        // 2. Muat semua data keranjang dari database ke session (bersihkan orphan)
        $dbItems = CartItem::with('book')->where('user_id', $userId)->get();
        $syncedCart = [];
        foreach ($dbItems as $ci) {
            if ($ci->book) {
                $syncedCart[$ci->book_id] = [
                    'id'          => $ci->book->id,
                    'title'       => $ci->book->title,
                    'price'       => (float)$ci->book->price,
                    'cover_image' => $ci->book->cover_image,
                    'qty'         => (int)$ci->quantity,
                ];
            } else {
                // Buku sudah dihapus dari tabel books, bersihkan baris orphan
                $ci->delete();
            }
        }

        session()->put('cart', $syncedCart);
    }

    public function index()
    {
        // Pastikan keranjang tersimpan dimuat saat user login
        if (auth()->check()) {
            if (empty(session()->get('cart', []))) {
                self::syncUserCartWithDatabase();
            }
        }

        $cart = session()->get('cart', []);
        $totalPrice = 0;
        $hasStockIssue = false;

        if (!empty($cart)) {
            $bookIds = array_keys($cart);
            $books = Book::whereIn('id', $bookIds)->get()->keyBy('id');

            foreach ($cart as $bookId => &$item) {
                $book = $books->get($bookId);
                $currentStock = $book ? (int)$book->stock : 0;
                $item['current_stock'] = $currentStock;

                // Selalu perbarui harga dan judul terkini dari database
                if ($book) {
                    $item['price'] = (float)$book->price;
                    $item['title'] = $book->title;
                    $item['cover_image'] = $book->cover_image;
                }

                if (!$book || $currentStock <= 0) {
                    $item['is_out_of_stock'] = true;
                    $item['stock_shortage'] = false;
                    $hasStockIssue = true;
                } elseif ($item['qty'] > $currentStock) {
                    $item['is_out_of_stock'] = false;
                    $item['stock_shortage'] = true;
                    $hasStockIssue = true;
                } else {
                    $item['is_out_of_stock'] = false;
                    $item['stock_shortage'] = false;
                }

                $totalPrice += $item['price'] * $item['qty'];
            }
            unset($item);
            session()->put('cart', $cart);
        }

        return view('cart.index', compact('cart', 'totalPrice', 'hasStockIssue'));
    }

    public function add(Request $request)
    {
        if (!auth()->check()) {
            session()->put('url.intended', url()->previous());
            return redirect()->route('login')
                ->with('warning', 'Silakan masuk (login) atau daftar akun terlebih dahulu untuk menambahkan buku ke keranjang.');
        }

        $bookId = (int)$request->input('book_id');
        $qty = max(1, (int)$request->input('quantity', 1));

        $book = Book::find($bookId);
        if (!$book) {
            return back()->with('danger', 'Maaf, buku tidak ditemukan atau telah dihapus dari katalog.');
        }

        if ($book->stock <= 0) {
            return back()->with('danger', 'Maaf, persediaan buku ini sedang habis.');
        }

        $cart = session()->get('cart', []);
        $currentQty = isset($cart[$bookId]) ? $cart[$bookId]['qty'] : 0;

        if ($currentQty + $qty > $book->stock) {
            return back()->with('danger', "Gagal menambahkan! Jumlah di keranjang ({$currentQty} eks) sudah mencapai batas stok yang tersedia ({$book->stock} eks).");
        }

        $newQty = $currentQty + $qty;

        $cart[$bookId] = [
            'id'          => $book->id,
            'title'       => $book->title,
            'price'       => (float)$book->price,
            'cover_image' => $book->cover_image,
            'qty'         => $newQty,
        ];

        session()->put('cart', $cart);

        // Simpan persisten ke database agar tidak hilang saat logout
        CartItem::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $bookId],
            ['quantity' => $newQty]
        );

        return back()->with('success', "Buku '{$book->title}' berhasil ditambahkan ke keranjang belanja!");
    }

    public function update(Request $request)
    {
        $bookId = (int)$request->input('book_id');
        $qty = (int)$request->input('quantity', 1);

        $book = Book::find($bookId);
        $cart = session()->get('cart', []);

        if (isset($cart[$bookId])) {
            if ($qty <= 0 || !$book) {
                unset($cart[$bookId]);
                session()->put('cart', $cart);

                if (auth()->check()) {
                    CartItem::where('user_id', auth()->id())->where('book_id', $bookId)->delete();
                }

                return back()->with('info', "Buku telah dihapus dari keranjang.");
            }

            if ($qty > $book->stock) {
                return back()->with('danger', "Jumlah melebihi stok yang tersedia saat ini ({$book->stock} eks).");
            }

            $cart[$bookId]['qty'] = $qty;
            $cart[$bookId]['price'] = (float)$book->price;
            $cart[$bookId]['title'] = $book->title;
            session()->put('cart', $cart);

            if (auth()->check()) {
                CartItem::updateOrCreate(
                    ['user_id' => auth()->id(), 'book_id' => $bookId],
                    ['quantity' => $qty]
                );
            }

            return back()->with('success', "Keranjang belanja berhasil diperbarui.");
        }

        return back();
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $title = $cart[$id]['title'];
            unset($cart[$id]);
            session()->put('cart', $cart);

            if (auth()->check()) {
                CartItem::where('user_id', auth()->id())->where('book_id', $id)->delete();
            }

            return back()->with('success', "Buku '{$title}' berhasil dihapus dari keranjang.");
        }
        return back();
    }

    public function clear()
    {
        session()->forget('cart');
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        }
        return back()->with('info', 'Keranjang belanja telah dikosongkan.');
    }
}
