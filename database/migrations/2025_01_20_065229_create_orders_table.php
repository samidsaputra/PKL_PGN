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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('noorder')->primary(); // ID order (primary key)
            $table->string('acara'); // Acara
            $table->date('tanggal_acara'); // Tanggal acara
            $table->date('tanggal_yang_diharapkan'); // Tanggal yang diharapkan
            $table->string('status'); // Status order
            $table->string('penerima'); // Nama penerima
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

