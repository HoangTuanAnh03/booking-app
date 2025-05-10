<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fields')->insert([
            // Venue: Namtrangpickleball Club
            [
                'field_id' => '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d',
                'venue_id' => 'a54f62ab-1a56-4dbd-b23f-e1534771a9c0',
                'sport_type_id' => 7,
                'field_name' => 'Iron Man',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],
            [
                'field_id' => '2b3c4d5e-6f7a-8b9c-0d1e-2f3a4b5c6d7e',
                'venue_id' => 'a54f62ab-1a56-4dbd-b23f-e1534771a9c0',
                'sport_type_id' => 7,
                'field_name' => 'Thor',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: Câu lạc bộ Tây Hồ
            [
                'field_id' => '3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f',
                'venue_id' => 'b2e3a1c5-61f1-4770-b8b6-e2b70c60f779',
                'sport_type_id' => 1,
                'field_name' => 'Sân Cầu Lông',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            [
                'field_id' => '3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8g',
                'venue_id' => 'b2e3a1c5-61f1-4770-b8b6-e2b70c60f779',
                'sport_type_id' => 6,
                'field_name' => 'Bóng Bàn',
                'default_price' => 170000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            [
                'field_id' => '3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8h',
                'venue_id' => 'b2e3a1c5-61f1-4770-b8b6-e2b70c60f779',
                'sport_type_id' => 7,
                'field_name' => 'Pickleball',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: Tenis cây xoài 182 Đình Thôn
            [
                'field_id' => '4d5e6f7a-8b9c-0d1e-2f3a-4b5c6d7e8f9a',
                'venue_id' => 'ca5f3207-2ea4-4d8c-8c9d-08f71a064f1a',
                'sport_type_id' => 5,
                'field_name' => 'Tennis 1',
                'default_price' => 250000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],
            [
                'field_id' => '5e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b',
                'venue_id' => 'ca5f3207-2ea4-4d8c-8c9d-08f71a064f1a',
                'sport_type_id' => 5,
                'field_name' => 'Tennis 2',
                'default_price' => 250000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: Us THCS Phú Đô
            [
                'field_id' => '6f7a8b9c-0d1e-2f3a-4b5c-6d7e8f9a0b1c',
                'venue_id' => '3d5b81ad-b589-406f-bf3d-ff46974e300f',
                'sport_type_id' => 2,
                'field_name' => 'Sân A',
                'default_price' => 300000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: Sky Trap
            [
                'field_id' => '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d',
                'venue_id' => 'b2a3e4f5-115f-4057-b8cc-f7f93d27a084',
                'sport_type_id' => 3,
                'field_name' => 'Hulk',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: Sân cầu lông Cảnh Hồ
            [
                'field_id' => '8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e',
                'venue_id' => 'a7b1c8e3-206f-4de0-bfae-2132ef5f8e2a',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông 1',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],
            [
                'field_id' => '9c0d1e2f-3a4b-5c6d-7e8f-9a0b1c2d3e4f',
                'venue_id' => 'a7b1c8e3-206f-4de0-bfae-2132ef5f8e2a',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông 2',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: DINKZONE
            [
                'field_id' => '0d1e2f3a-4b5c-6d7e-8f9a-0b1c2d3e4f5a',
                'venue_id' => 'dfb4bb62-7b32-4061-91f3-4a381fd07c0f',
                'sport_type_id' => 7,
                'field_name' => 'PickleBall',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],
            [
                'field_id' => '0d1e2f3a-4b5c-6d7e-8f9a-0b1c2d3e4f5b',
                'venue_id' => 'dfb4bb62-7b32-4061-91f3-4a381fd07c0f',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông',
                'default_price' => 130000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: Đức Thảo
            [
                'field_id' => '1e2f3a4b-5c6d-7e8f-9a0b-1c2d3e4f5a6b',
                'venue_id' => 'dd93c76e-22fd-4a89-8e8a-1b3db799f755',
                'sport_type_id' => 1,
                'field_name' => 'Sân A',
                'default_price' => 100000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: AC 32 Đại Từ
            [
                'field_id' => '2f3a4b5c-6d7e-8f9a-0b1c-2d3e4f5a6b7c',
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'sport_type_id' => 7,
                'field_name' => 'Captain America',
                'default_price' => 250000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],

            // Venue: Fbshop Kim Giang
            [
                'field_id' => '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông 1',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],
            [
                'field_id' => '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e',
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông 2',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],
            [
                'field_id' => '5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f',
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông 3',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],
        ]);
    }
}
