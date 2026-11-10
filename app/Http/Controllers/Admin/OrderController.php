<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', '');
        $search = trim($request->get('q', ''));

        $query = Order::with('user');

        if (!empty($statusFilter)) {
            $query->where('order_status', $statusFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'statusFilter', 'search'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.book']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status'   => 'required|in:Menunggu Konfirmasi,Sedang Diproses,Sedang Dikirim,Selesai,Dibatalkan',
            'payment_status' => 'required|in:Menunggu Pembayaran,Sudah Dibayar (Menunggu Verifikasi),Lunas,Dibatalkan',
        ]);

        $oldOrderStatus = $order->order_status;
        $newOrderStatus = $request->order_status;
        $newPaymentStatus = $request->payment_status;

        try {
            DB::transaction(function () use ($order, $oldOrderStatus, $newOrderStatus, $newPaymentStatus) {
                $order->load('items');

                // 1. Kasus Pembatalan: dari status aktif menjadi Dibatalkan -> Kembalikan stok buku
                if ($newOrderStatus === 'Dibatalkan' && $oldOrderStatus !== 'Dibatalkan') {
                    foreach ($order->items as $item) {
                        if ($item->book_id) {
                            $book = Book::lockForUpdate()->find($item->book_id);
                            if ($book) {
                                $book->increment('stock', $item->quantity);
                            }
                        }
                    }
                }
                // 2. Kasus Pengaktifan Kembali: dari Dibatalkan menjadi Aktif -> Cek ketersediaan stok lalu kurangi kembali
                elseif ($oldOrderStatus === 'Dibatalkan' && $newOrderStatus !== 'Dibatalkan') {
                    foreach ($order->items as $item) {
                        if ($item->book_id) {
                            $book = Book::lockForUpdate()->find($item->book_id);
                            if (!$book || $book->stock < $item->quantity) {
                                $avail = $book ? $book->stock : 0;
                                throw new \Exception("Gagal mengaktifkan kembali pesanan: Stok buku '{$item->book_title}' tidak mencukupi (Tersedia: {$avail}, Dibutuhkan: {$item->quantity}).");
                            }
                        }
                    }

                    foreach ($order->items as $item) {
                        if ($item->book_id) {
                            Book::where('id', $item->book_id)->decrement('stock', $item->quantity);
                        }
                    }
                }

                // Perbarui status pesanan
                $order->update([
                    'order_status'   => $newOrderStatus,
                    'payment_status' => $newPaymentStatus,
                ]);
            });

            return back()->with('success', "Status pesanan {$order->order_number} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->with('danger', $e->getMessage());
        }
    }
}
