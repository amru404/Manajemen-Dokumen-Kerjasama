<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nomor_document')->unique();
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->foreignId('judul_id')->nullable()->constrained('judul_kerjasamas')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pihak_1_id')->constrained('mitras')->onDelete('cascade');
            $table->foreignId('pihak_2_id')->constrained('mitras')->onDelete('cascade');
            $table->longText('content_html')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('source', ['generate', 'upload'])->default('generate');
            $table->enum('status', ['denied','draft', 'final', 'published'])->default('draft');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
