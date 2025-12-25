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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('id_order_details');

            // Relasi ke Order
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                ->references('id_orders')->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Hapus Order induk = Hapus detail itemnya

            // Relasi ke Services
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')
                ->references('id_services')->on('services')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->float('qty');
            $table->decimal('harga_saat_ini', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
