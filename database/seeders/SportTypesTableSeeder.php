<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SportTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sport_types')->insert([
            [
                'sport_type_id' => 1,
                'name' => 'Cầu Lông',
                'description' => 'Môn thể thao cầu lông',
                'created_at' => '2025-04-10 06:46:46',
                'updated_at' => '2025-04-10 06:46:46',
            ],
            [
                'sport_type_id' => 2,
                'name' => 'Bóng Đá',
                'description' => 'Môn thể thao đồng đội phổ biến nhất thế giới',
                'created_at' => '2025-04-10 06:47:31',
                'updated_at' => '2025-04-10 06:47:31',
            ],
            [
                'sport_type_id' => 3,
                'name' => 'Bóng Rổ',
                'description' => 'Môn thể thao thi đấu trên sân với bóng và rổ',
                'created_at' => '2025-04-10 06:47:44',
                'updated_at' => '2025-04-10 06:47:44',
            ],
            [
                'sport_type_id' => 4,
                'name' => 'Bóng Chuyền',
                'description' => 'Môn thể thao đánh bóng qua lưới bằng tay',
                'created_at' => '2025-04-10 06:47:52',
                'updated_at' => '2025-04-10 06:47:52',
            ],
            [
                'sport_type_id' => 5,
                'name' => 'Tenis',
                'description' => 'Môn thể thao đánh bóng qua lưới bằng vợt',
                'created_at' => '2025-04-10 06:48:25',
                'updated_at' => '2025-04-10 06:48:25',
            ],
            [
                'sport_type_id' => 6,
                'name' => 'Bóng Bàn',
                'description' => 'Môn thể thao đánh bóng qua lưới trên bàn nhỏ bằng vợt nhỏ',
                'created_at' => '2025-04-10 06:53:45',
                'updated_at' => '2025-04-10 06:53:45',
            ],
            [
                'sport_type_id' => 7,
                'name' => 'Pickleball',
                'description' => 'Môn thể thao kết hợp giữa tennis, bóng bàn và cầu lông, chơi với vợt và bóng nhựa',
                'created_at' => '2025-04-10 06:53:51',
                'updated_at' => '2025-04-10 06:53:51',
            ],
        ]);
    }
}
