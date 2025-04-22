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
        Schema::create('venue_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->uuid('venue_id');
            $table->foreign('venue_id')->references('venue_id')->on('venues')->onDelete('cascade');
            $table->string('image_url');
            $table->enum('type',['cover','thumbnail','default'])->default('default');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_images');
    }
};
