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
                'venue_id' => 'dd93c76e-22fd-4a89-8e8a-1b3db799f755',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 5,
                'comment' => 'Sân cầu lông rất sạch sẽ, nhân viên nhiệt tình!',
                'created_at' => Carbon::parse('2025-04-10 19:45:46'),
                'updated_at' => Carbon::parse('2025-04-10 19:45:46'),
            ],
            [
                'review_id' => 2,
                'venue_id' => 'dd93c76e-22fd-4a89-8e8a-1b3db799f755',
                'user_id' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'rating' => 4,
                'comment' => 'Giá cả hợp lý, nhưng ánh sáng cần cải thiện một chút.',
                'created_at' => Carbon::parse('2025-04-11 09:30:22'),
                'updated_at' => Carbon::parse('2025-04-11 09:30:22'),
            ],
            [
                'review_id' => 3,
                'venue_id' => 'dd93c76e-22fd-4a89-8e8a-1b3db799f755',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 5,
                'comment' => 'Địa điểm dễ tìm, sân chất lượng tốt!',
                'created_at' => Carbon::parse('2025-04-12 14:20:15'),
                'updated_at' => Carbon::parse('2025-04-12 14:20:15'),
            ],
            [
                'review_id' => 4,
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'user_id' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'rating' => 5,
                'comment' => 'Sân Pickleball tuyệt vời, không gian rộng rãi!',
                'created_at' => Carbon::parse('2025-04-10 20:15:30'),
                'updated_at' => Carbon::parse('2025-04-10 20:15:30'),
            ],
            [
                'review_id' => 5,
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 3,
                'comment' => 'Sân ổn, nhưng cần thêm quạt vì hơi nóng.',
                'created_at' => Carbon::parse('2025-04-11 15:45:10'),
                'updated_at' => Carbon::parse('2025-04-11 15:45:10'),
            ],
            [
                'review_id' => 6,
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'user_id' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'rating' => 4,
                'comment' => 'Dịch vụ tốt, sân chất lượng, sẽ quay lại.',
                'created_at' => Carbon::parse('2025-04-12 10:25:50'),
                'updated_at' => Carbon::parse('2025-04-12 10:25:50'),
            ],
            [
                'review_id' => 7,
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 5,
                'comment' => 'Sân cầu lông tuyệt vời, nhân viên hỗ trợ nhanh chóng!',
                'created_at' => Carbon::parse('2025-04-10 21:00:00'),
                'updated_at' => Carbon::parse('2025-04-10 21:00:00'),
            ],
            [
                'review_id' => 8,
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'user_id' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'rating' => 4,
                'comment' => 'Sân tốt, nhưng bãi đỗ xe hơi nhỏ.',
                'created_at' => Carbon::parse('2025-04-11 11:10:35'),
                'updated_at' => Carbon::parse('2025-04-11 11:10:35'),
            ],
            [
                'review_id' => 9,
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'user_id' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'rating' => 5,
                'comment' => 'Chất lượng sân rất tốt, giá cả hợp lý!',
                'created_at' => Carbon::parse('2025-04-12 16:30:20'),
                'updated_at' => Carbon::parse('2025-04-12 16:30:20'),
            ],
        ]);
    }
}
