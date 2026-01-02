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
        Schema::create('order_tracking', function (Blueprint $table) {
            $table->id('id_order_tracking');

            // Relasi ke Order
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                ->references('id_orders')->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Hapus Order = Hapus history tracking

            $table->enum('status', ['Perlu Dijemput', 'Dicuci', 'Dijemur', 'Disetrika', 'Siap']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_tracking');
    }
};
