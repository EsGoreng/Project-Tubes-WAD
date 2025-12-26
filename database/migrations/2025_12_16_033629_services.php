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
        Schema::create('services', function (Blueprint $table) {
            $table->id('id_services');
            $table->string('nama_paket', 45);
            $table->text('deskripsi', 500);
            $table->enum('satuan', ['Kg', 'Pcs']);
            $table->decimal('harga', 10, 2);
            $table->integer('estimasi_durasi'); // Durasi dalam jam/hari
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
