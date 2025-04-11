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
        Schema::create('fields', function (Blueprint $table) {
            $table->uuid('field_id')->primary();
            $table->uuid('venue_id');
            $table->unsignedBigInteger('sport_type_id');
            $table->string('field_name');
            $table->decimal('default_price');
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('venue_id')->references('venue_id')->on('venues');
            $table->foreign('sport_type_id')->references('sport_type_id')->on('sport_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
