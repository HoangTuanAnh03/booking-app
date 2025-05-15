<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE booking MODIFY COLUMN order_id BIGINT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT, AUTO_INCREMENT = 1000000");
//        DB::statement("ALTER TABLE venue_payment MODIFY COLUMN code BIGINT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT, AUTO_INCREMENT = 1000000");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
