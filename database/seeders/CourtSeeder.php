<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courts')->insert([
            [
                'court_id'   => "dcb38c24-adb0-4c57-9551-ed4413becf4x",
                'field_id'   => 'dcb38c24-adb0-4c57-9551-ed4413becf4o',
                'court_name' => 'Court 1 ',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_id'   => "dcb38c24-adb0-4c57-9551-ed4413becf4i",
                'field_id'   => 'dcb38c24-adb0-4c57-9551-ed4413becf4o',
                'court_name' => 'Court 2 ',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_id'   => "dcb38c24-adb0-4c57-9551-ed4413becf4z",
                'field_id'   => 'dcb38c24-adb0-4c57-9551-ed4413becf4o',
                'court_name' => 'Court 3 ',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
