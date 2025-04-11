<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reviews')->insert([
            [
                'review_id' => 1,
                'venue_id' => 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f',
                'user_id' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'rating' => 5,
                'comment' => 'Great venue and friendly staff!',
                'created_at' => Carbon::parse('2025-04-10 19:45:46'),
                'updated_at' => Carbon::parse('2025-04-10 19:45:46'),
            ],
            [
                'review_id' => 2,
                'venue_id' => 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93',
                'user_id' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'rating' => 4,
                'comment' => 'Great venue and friendly staff!',
                'created_at' => Carbon::parse('2025-04-10 19:47:07'),
                'updated_at' => Carbon::parse('2025-04-10 19:47:07'),
            ],
            [
                'review_id' => 3,
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'user_id' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'rating' => 3,
                'comment' => 'Great venue and friendly staff!',
                'created_at' => Carbon::parse('2025-04-10 19:47:18'),
                'updated_at' => Carbon::parse('2025-04-10 19:47:18'),
            ],
            [
                'review_id' => 4,
                'venue_id' => '7d465e0a-ce6b-4bf5-9327-228de8b96302',
                'user_id' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'rating' => 2,
                'comment' => 'Great venue and friendly staff!',
                'created_at' => Carbon::parse('2025-04-10 19:47:36'),
                'updated_at' => Carbon::parse('2025-04-10 19:47:36'),
            ],
            [
                'review_id' => 5,
                'venue_id' => '7d465e0a-ce6b-4bf5-9327-228de8b96302',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 4,
                'comment' => 'Great venue and friendly staff!',
                'created_at' => Carbon::parse('2025-04-10 19:48:20'),
                'updated_at' => Carbon::parse('2025-04-10 19:48:20'),
            ],
            [
                'review_id' => 6,
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 3,
                'comment' => 'Great venue and friendly staff!',
                'created_at' => Carbon::parse('2025-04-10 19:48:29'),
                'updated_at' => Carbon::parse('2025-04-10 19:48:29'),
            ],
            [
                'review_id' => 7,
                'venue_id' => 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 1,
                'comment' => 'Great venue and friendly staff!',
                'created_at' => Carbon::parse('2025-04-10 19:48:39'),
                'updated_at' => Carbon::parse('2025-04-10 19:48:39'),
            ],
            [
                'review_id' => 8,
                'venue_id' => 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 2,
                'comment' => 'Great venue and friendly staff!',
                'created_at' => Carbon::parse('2025-04-10 19:48:54'),
                'updated_at' => Carbon::parse('2025-04-10 19:48:54'),
            ],
        ]);
    }
}
