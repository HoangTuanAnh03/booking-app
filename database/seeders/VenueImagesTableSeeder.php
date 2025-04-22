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
            [
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'image_url' => 'https://us1.discourse-cdn.com/flex026/uploads/djangoproject/original/2X/d/dfd2ee1c273e3084e7643082f24df3d6f1ea1ee0.jpeg',
                'type' => 'thumbnail',
                'created_at' => Carbon::parse('2025-04-21 22:20:52'),
                'updated_at' => Carbon::parse('2025-04-21 22:20:52'),
            ],
            [
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRPXt1jAuIQc02tOh7juYsWDjqsfRTrSCCzIg&s',
                'type' => 'cover',
                'created_at' => Carbon::parse('2025-04-21 22:21:14'),
                'updated_at' => Carbon::parse('2025-04-21 22:21:14'),
            ],
            [
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'image_url' => 'https://europe1.discourse-cdn.com/flex013/uploads/make/optimized/3X/0/5/05a7391ccc1e5c787b7a5017955e2fb18a05eda9_2_490x500.png',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:24:09'),
                'updated_at' => Carbon::parse('2025-04-21 22:24:09'),
            ],
            [
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'image_url' => 'https://europe1.discourse-cdn.com/flex013/uploads/make/optimized/3X/0/5/05a7391ccc1e5c787b7a5017955e2fb18a05eda9_2_490x500.png',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:27:16'),
                'updated_at' => Carbon::parse('2025-04-21 22:27:16'),
            ],
            [
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'image_url' => 'https://europe1.discourse-cdn.com/flex013/uploads/make/optimized/3X/0/5/05a7391ccc1e5c787b7a5017955e2fb18a05eda9_2_490x500.png',
                'type' => 'default',
                'created_at' => Carbon::parse('2025-04-21 22:35:12'),
                'updated_at' => Carbon::parse('2025-04-21 22:35:12'),
            ],
        ]);
    }
}
