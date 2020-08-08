<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PoinSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poin_siswa', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('id_siswa');
            $table->integer('id_pelanggaran');
            $table->integer('id_petugas');
            $table->datetime('tanggal');
            $table->text('keterangan');
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
        Schema::dropIfExists('poin_siswa');
    }
}
