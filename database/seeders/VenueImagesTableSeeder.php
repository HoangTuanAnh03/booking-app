<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenueImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('venue_images')->insert([
            // Venue: Fbshop Kim Giang
            [
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'image_url' => 'https://i.imgur.com/aAxRu02.png',
                'type' => 'cover',
                'created_at' => Carbon::parse('2025-04-21 22:20:52'),
                'updated_at' => Carbon::parse('2025-04-21 22:20:52'),
            ],
            [
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'image_url' => 'https://i.imgur.com/5smYKpG.jpeg',
                'type' => 'thumbnail',
                'created_at' => Carbon::parse('2025-04-21 22:21:14'),
                'updated_at' => Carbon::parse('2025-04-21 22:21:14'),
            ],
            [
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'image_url' => 'https://i.imgur.com/VykuHJy.png',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:09'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:09'),
            ],
            [
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'image_url' => 'https://i.imgur.com/G754ZDB.png',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:10'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:10'),
            ],
            [
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'image_url' => 'https://i.imgur.com/svlfOPM.jpeg',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:11'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:11'),
            ],
            [
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'image_url' => 'https://i.imgur.com/ep65gV3.png',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:12'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:12'),
            ],

            // Venue: AC 32 Đại Từ
            [
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'image_url' => 'https://i.imgur.com/BFIrYaN.jpeg',
                'type' => 'cover',
                'created_at' => Carbon::parse('2025-04-21 22:20:52'),
                'updated_at' => Carbon::parse('2025-04-21 22:20:52'),
            ],
            [
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'image_url' => 'https://i.imgur.com/TCfB3YU.jpeg',
                'type' => 'thumbnail',
                'created_at' => Carbon::parse('2025-04-21 22:21:14'),
                'updated_at' => Carbon::parse('2025-04-21 22:21:14'),
            ],
            [
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'image_url' => 'https://i.imgur.com/9z59zF9.jpeg',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:09'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:09'),
            ],
            [
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'image_url' => 'https://i.imgur.com/vWYEsqX.jpeg',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:10'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:10'),
            ],

            [
                'venue_id' => 'dd93c76e-22fd-4a89-8e8a-1b3db799f755',
                'image_url' => 'https://i.imgur.com/SVvVYuM.png',
                'type' => 'cover',
                'created_at' => Carbon::parse('2025-04-21 22:20:52'),
                'updated_at' => Carbon::parse('2025-04-21 22:20:52'),
            ],
            [
                'venue_id' => 'dd93c76e-22fd-4a89-8e8a-1b3db799f755',
                'image_url' => 'https://i.imgur.com/q2prgLH.png',
                'type' => 'thumbnail',
                'created_at' => Carbon::parse('2025-04-21 22:21:14'),
                'updated_at' => Carbon::parse('2025-04-21 22:21:14'),
            ],
            [
                'venue_id' => 'dd93c76e-22fd-4a89-8e8a-1b3db799f755',
                'image_url' => 'https://i.imgur.com/zIlFXkV.png',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:09'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:09'),
            ],

            // Venue: Sân cầu lông Cảnh Hồ
            [
                'venue_id' => 'a7b1c8e3-206f-4de0-bfae-2132ef5f8e2a',
                'image_url' => 'https://i.imgur.com/j3I8TYX.jpeg',
                'type' => 'cover',
                'created_at' => Carbon::parse('2025-04-21 22:20:52'),
                'updated_at' => Carbon::parse('2025-04-21 22:20:52'),
            ],
            [
                'venue_id' => 'a7b1c8e3-206f-4de0-bfae-2132ef5f8e2a',
                'image_url' => 'https://i.imgur.com/2EqTri8.jpeg',
                'type' => 'thumbnail',
                'created_at' => Carbon::parse('2025-04-21 22:21:14'),
                'updated_at' => Carbon::parse('2025-04-21 22:21:14'),
            ],
            [
                'venue_id' => 'a7b1c8e3-206f-4de0-bfae-2132ef5f8e2a',
                'image_url' => 'https://i.imgur.com/aWYWoDt.jpeg',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:09'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:09'),
            ],
            [
                'venue_id' => 'a7b1c8e3-206f-4de0-bfae-2132ef5f8e2a',
                'image_url' => 'https://i.imgur.com/zydZn1k.png',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:10'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:10'),
            ],

            // Venue: Sky Trap
            [
                'venue_id' => 'b2a3e4f5-115f-4057-b8cc-f7f93d27a084',
                'image_url' => 'https://i.imgur.com/UcxbOeK.jpeg',
                'type' => 'cover',
                'created_at' => Carbon::parse('2025-04-21 22:20:52'),
                'updated_at' => Carbon::parse('2025-04-21 22:20:52'),
            ],
            [
                'venue_id' => 'b2a3e4f5-115f-4057-b8cc-f7f93d27a084',
                'image_url' => 'https://i.imgur.com/ldLqZ1C.jpeg',
                'type' => 'thumbnail',
                'created_at' => Carbon::parse('2025-04-21 22:21:14'),
                'updated_at' => Carbon::parse('2025-04-21 22:21:14'),
            ],
            [
                'venue_id' => 'b2a3e4f5-115f-4057-b8cc-f7f93d27a084',
                'image_url' => 'https://i.imgur.com/G6LkUDP.jpeg',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:09'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:09'),
            ],
            [
                'venue_id' => 'b2a3e4f5-115f-4057-b8cc-f7f93d27a084',
                'image_url' => 'https://i.imgur.com/iqcrd0t.jpeg',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:10'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:10'),
            ],
        ]);
    }
}
