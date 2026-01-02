<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_orders');

            // Relasi ke Customers
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id_customer')->on('customers')
                ->onUpdate('cascade')
                ->onDelete('set null'); // Data order aman meski customer dihapus

            // Relasi ke Users
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id_user')->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->dateTime('tgl_masuk');
            $table->dateTime('tgl_selesai_estimasi')->nullable();
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->enum('status_pembayaran', ['Pending', 'Lunas'])->default('Pending');
            $table->boolean('is_pickup')->default(false);
            $table->text('catatan')->nullable();
            $table->text('alamat_jemput')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
