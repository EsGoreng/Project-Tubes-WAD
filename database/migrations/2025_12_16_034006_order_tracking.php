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

            // Relasi ke Orders
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id_orders')->on('orders');

            $table->enum('status', ['Dicuci', 'Dijemur', 'Disetrika', 'Siap']);

            // Kolom updated_at sesuai permintaan
            $table->timestamp('updated_at')->useCurrent();
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
