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
        Schema::create('field_opening_hours', function (Blueprint $table) {
            $table->id("opening_id")->autoIncrement()->primary();
            $table->uuid('field_id');
            $table->foreign('field_id')->references('field_id')->on('fields');
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('opening_time');
            $table->time('closing_time');
            $table->timestamps();
            $table->unique(['field_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_opening_hours');
    }
};
