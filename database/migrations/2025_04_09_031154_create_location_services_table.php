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
        Schema::create('location_services', function (Blueprint $table) {
            $table->id("service_id")->autoIncrement()->primary();
            $table->uuid('venue_id');
            $table->string("service_name",50);
            $table->decimal("price", 10, 2);
            $table->boolean("is_available");
            $table->text("description");
            $table->timestamps();

            $table->foreign("venue_id")->references("venue_id")->on("venues");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_services');
    }
};
