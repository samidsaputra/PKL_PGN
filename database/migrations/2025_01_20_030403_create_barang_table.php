<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key
            $table->string('Nama_Barang')->unique(); // Kolom nama_barang
            $table->unsignedBigInteger('Kategori_Id'); // Kolom kategori sebagai foreign key
            $table->string('Stok');
            $table->string('Kategori');
            $table->string('Deskripsi'); // Kolom deskripsi
            $table->timestamps(); // Kolom created_at dan updated_at

            // Menambahkan foreign key constraint
            $table->foreign('Kategori_Id')->references('id')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang');
    }
};
