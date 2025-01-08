<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satuan_kerja', function (Blueprint $table) {
            $table->string('nama')->primary(); // Satuan Kerja sebagai primary key
            $table->string('contact'); // Nomor Telepon
            $table->string('email')->unique(); // Email harus unik
            $table->string('PIC'); // Nama PIC
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('satuan_kerja');
    }
};
