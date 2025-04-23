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
        Schema::create('court_special_time', function (Blueprint $table) {
            $table->id('court_special_time_id')->primary()->autoIncrement();
            $table->uuid('court_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('date');
            $table->integer('min_rental');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('court_id')->references('court_id')->on('courts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_special_time');
    }
};
