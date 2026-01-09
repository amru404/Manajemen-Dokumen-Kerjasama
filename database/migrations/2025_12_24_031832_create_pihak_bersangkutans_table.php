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
        Schema::create('pihak_bersangkutans', function (Blueprint $table) {
            $table->id();
            $table->string('peran');
            $table->unsignedBigInteger('mitra_id');
            $table->unsignedBigInteger('judul_id');
            
            $table->foreign('judul_id')->references('id')->on('judul_kerjasamas');
            $table->foreign('mitra_id')->references('id')->on('mitras');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pihak_bersangkutans');
    }
};
