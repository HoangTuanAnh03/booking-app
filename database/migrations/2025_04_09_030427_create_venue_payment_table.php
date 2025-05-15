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
        Schema::create('venue_payment', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->uuid('owner_id');
            $table->foreign('owner_id')->references('uuid')->on('users');
            $table->uuid('venue_id');
            $table->integer('amount');
            $table->string('message');
//            $table->bigInteger('code')->unique()->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_payment');
    }
};
