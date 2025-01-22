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
            $table->foreignId('noorder') // Relasi ke tabel orders
                ->constrained('orders', 'noorder') // Menggunakan noorder sebagai foreign key
                ->onDelete('cascade'); // Hapus item jika order dihapus
            $table->string('item'); // Nama item
            $table->integer('jumlah'); // Jumlah item
            $table->timestamps(); // Kolom created_at dan updated_at
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

