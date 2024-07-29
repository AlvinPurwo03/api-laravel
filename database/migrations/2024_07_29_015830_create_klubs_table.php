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
        Schema::create('klubs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_klub');
            $table->string('logo')->nullable();
            $table->unsignedBigInteger('id_liga'); //foreign key

            $table->foreign('id_liga')->references('id')->on('ligas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klubs');
    }
};
