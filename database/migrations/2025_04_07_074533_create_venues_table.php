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
        Schema::create('venues', function (Blueprint $table) {
            $table->uuid('venue_id')->primary();
            $table->uuid('owner_id');
            $table->foreign('owner_id')->references('uuid')->on('users');
            $table->string('name');
            $table->string('address');
            $table->decimal('longitude', 11, 8);
            $table->decimal('latitude', 11, 8);
            $table->geometry('coordinates', subtype: 'point');
            $table->enum('status', ['active', 'locked', 'banned'])->default('locked');
            $table->string('bank_account_number');
            $table->string('bank_name');
            $table->timestamps();
            $table->spatialIndex('coordinates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
