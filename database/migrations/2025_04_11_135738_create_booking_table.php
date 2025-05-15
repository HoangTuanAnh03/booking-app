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
        Schema::create('booking', function (Blueprint $table) {
            $table->uuid("booking_id")->primary();
            $table->bigInteger('order_id')->unique()->nullable();;
            $table->uuid("field_id");
            $table->uuid("user_id");
            $table->decimal("total_price", 10, 2);
            $table->string("customer_name");
            $table->string("customer_phone");
            $table->enum("status", ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->string("booking_date");
            $table->timestamps();

//            $table->foreign('field_id')->references('field_id')->on('fields');
            $table->foreign('user_id')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
