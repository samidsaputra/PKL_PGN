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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // ID item (primary key)
            $table->string('noorder'); // Hapus item jika order dihapus
            $table->string('item'); // Nama item
            $table->integer('jumlah'); // Jumlah item
            $table->timestamps(); // Kolom created_at dan updated_at

            $table->foreign('noorder')->references('noorder')->on('orders')->onDelete('cascade');
            $table->foreign('item')->references('Nama_Barang')->on('barang')->onDelete('restrict'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};

