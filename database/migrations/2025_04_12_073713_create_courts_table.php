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
        Schema::create('courts', function (Blueprint $table) {
            $table->uuid('court_id')->primary();

            $table->uuid('field_id'); 
            $table->string('court_name');
            $table->boolean('is_active');
            $table->timestamps();

            // Tạo khóa ngoại sau khi đã tạo cột
            $table->foreign('field_id')->references('field_id')->on('fields')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court');
    }
};
