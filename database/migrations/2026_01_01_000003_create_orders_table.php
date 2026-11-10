<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 25);
            $table->text('shipping_address');
            $table->string('payment_method', 100)->default('Payment at Delivery (Pembayaran sebelum dikirim)');
            $table->enum('payment_status', [
                'Menunggu Pembayaran',
                'Sudah Dibayar (Menunggu Verifikasi)',
                'Lunas',
                'Dibatalkan'
            ])->default('Menunggu Pembayaran');
            $table->enum('order_status', [
                'Menunggu Konfirmasi',
                'Sedang Diproses',
                'Sedang Dikirim',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu Konfirmasi');
            $table->decimal('total_amount', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
