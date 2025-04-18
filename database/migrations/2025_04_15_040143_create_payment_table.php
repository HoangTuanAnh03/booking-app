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
        Schema::create('payment', function (Blueprint $table) {
            $table->uuid("payment_id")->primary();
            $table->uuid("booking_id");
            $table->uuid("uid");
            $table->string("amount");
            $table->dateTime("payment_date")->default(now());
            $table->string("message");
            $table->enum("status", ['pending', 'paid', 'failed'])->default('pending');

            $table->foreign('booking_id')->references('booking_id')->on('booking');
            $table->foreign('uid')->references('uuid')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
