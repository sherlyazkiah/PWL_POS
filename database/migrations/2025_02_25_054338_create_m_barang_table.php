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
        Schema::create('m_barang', function (Blueprint $table) {
            $table->id('barang_id'); // primaary key
            $table->unsignedBigInteger('kategori_id'); // foreign key to m_category
            $table->string('barang_kode', 10)->unique(); //varchar
            $table->string('barang_nama', 100); //varchar
            $table->integer('harga_beli'); // int(11)
            $table->integer('harga_jual'); // int(11)
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('kategori_id')->references('kategori_id')->on('m_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_barang');
    }
};
