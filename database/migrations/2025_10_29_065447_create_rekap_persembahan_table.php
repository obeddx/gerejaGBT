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
        Schema::create('rekap_persembahan', function (Blueprint $table) {
            $table->id('id_rekap');
            $table->unsignedBigInteger('id_event');
            $table->unsignedBigInteger('id_persembahan');
            $table->decimal('nominal', 15, 2);
            $table->date('tgl_persembahan');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_event')
                  ->references('id_event')
                  ->on('event')
                  ->onDelete('cascade');
            
            $table->foreign('id_persembahan')
                  ->references('id_persembahan')
                  ->on('persembahan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropForeign(['id_event']);
        $table->dropForeign(['id_persembahan']);
        Schema::dropIfExists('rekap_persembahan');
    }
};