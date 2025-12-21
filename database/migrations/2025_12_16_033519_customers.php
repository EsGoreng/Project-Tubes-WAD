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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id_customer');
            $table->string('nama_lengkap');
            $table->text('description')->nullable();
            $table->string('no_wa');
            $table->text('alamat')->nullable();
            $table->string('email',)->nullable();
            $table->string('password',)->nullable();
            $table->string('created_at',)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
