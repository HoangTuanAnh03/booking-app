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
    Schema::create('court_slots', function (Blueprint $table) {
        $table->uuid('slot_id')->primary();
        $table->uuid('court_id');
        $table->unsignedBigInteger('booking_court_id')->nullable();
        $table->time('start_time');
        $table->time('end_time');
        $table->string("date");
        $table->boolean('is_looked');
        $table->boolean('locked_by_owner');
        $table->timestamps();
        $table->foreign('court_id')->references('court_id')->on('courts')->onDelete('cascade');
        $table->foreign('booking_court_id')->references('booking_court_id')->on('booking_courts')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_slots');
    }
};
