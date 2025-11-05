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
        Schema::create('news', function (Blueprint $table) {
            // Kolom id_news sebagai primary key
            $table->id('id_news');

            // Kolom judul berita
            $table->string('judul');

            // Kolom keterangan, menggunakan tipe TEXT untuk isi yang panjang
            $table->text('keterangan');

            // Kolom gambar, untuk menyimpan path/URL gambar
            $table->string('gambar');

            // Kolom tanggal yang boleh null (kosong)
            $table->date('tanggal')->nullable();

            // Kolom jam yang boleh null (kosong)
            $table->time('jam')->nullable();

            // Kolom created_at dan updated_at (standar Laravel)
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
        Schema::dropIfExists('news');
    }
};