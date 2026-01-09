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
        Schema::create('pasal_selcetions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mou_id');
            $table->unsignedBigInteger('pasal_id');
            $table->foreign('mou_id')->references('id')->on('mous');
            $table->foreign('pasal_id')->references('id')->on('pasals');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasal_selcetions');
    }
};
