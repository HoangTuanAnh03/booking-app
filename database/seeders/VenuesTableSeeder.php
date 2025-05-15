<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('venues')->insert([
            // Venue: Namtrangpickleball Club
            [
                'venue_id' => 'a54f62ab-1a56-4dbd-b23f-e1534771a9c0',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Namtrangpickleball Club',
                'address' => '22 Đ. Trần Duy Hưng, Trung Hoà, Cầu Giấy, Hà Nội',
                'longitude' => 105.8329394039998,
                'latitude' => 21.043210409502866,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.8329394039998 21.043210409502866)')"),
                'status' => 'locked',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: Câu lạc bộ Tây Hồ
            [
                'venue_id' => 'b2e3a1c5-61f1-4770-b8b6-e2b70c60f779',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Câu lạc bộ Tây Hồ',
                'address' => 'Số 10, Thụy Khuê, Tây Hồ, Hà Nội',
                'longitude' => 105.8329394039998,
                'latitude' => 21.043210409502866,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.8329394039998 21.043210409502866)')"),
                'status' => 'locked',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: Tenis cây xoài 182 Đình Thôn
            [
                'venue_id' => 'ca5f3207-2ea4-4d8c-8c9d-08f71a064f1a',
                'owner_id' => '6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe',
                'name' => 'Tenis cây xoài 182 Đình Thôn',
                'address' => '182 Đình Thôn, Mỹ Đình, Nam Từ Liêm, Hà Nội',
                'longitude' => 105.7725718,
                'latitude' => 21.0165516,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.7725718 21.0165516)')"),
                'status' => 'locked',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: Us THCS Phú Đô
            [
                'venue_id' => '3d5b81ad-b589-406f-bf3d-ff46974e300f',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Us THCS Phú Đô',
                'address' => '56 Lê Quang Đạo, Mễ Trì, Nam Từ Liêm, Hà Nội',
                'longitude' => 105.7709249,
                'latitude' => 21.0121225,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.7709249 21.0121225)')"),
                'status' => 'locked',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: Sky Trap
            [
                'venue_id' => 'b2a3e4f5-115f-4057-b8cc-f7f93d27a084',
                'owner_id' => '6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe',
                'name' => 'Sky Trap',
                'address' => '1 Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội',
                'longitude' => 105.74277317300005,
                'latitude' => 21.039038645000062,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.74277317300005 21.039038645000062)')"),
                'status' => 'locked',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: Sân cầu lông Cảnh Hồ
            [
                'venue_id' => 'a7b1c8e3-206f-4de0-bfae-2132ef5f8e2a',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Sân cầu lông Cảnh Hồ',
                'address' => '138B Trường Chinh, Khương Mai, Thanh Xuân, Hà Nội',
                'longitude' => 105.8354972,
                'latitude' => 20.9985122,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.8354972 20.9985122)')"),
                'status' => 'active',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: DINKZONE
            [
                'venue_id' => 'dfb4bb62-7b32-4061-91f3-4a381fd07c0f',
                'owner_id' => '6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe',
                'name' => 'DINKZONE',
                'address' => '65 Ngõ 124 Phố Vĩnh Tuy, Hai Bà Trưng, Hà Nội',
                'longitude' => 105.8770822,
                'latitude' => 20.998703,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.8770822 20.998703)')"),
                'status' => 'active',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: Đức Thảo
            [
                'venue_id' => 'dd93c76e-22fd-4a89-8e8a-1b3db799f755',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Đức Thảo',
                'address' => '18 Tam Trinh, Mai Động, Hoàng Mai, Hà Nội',
                'longitude' => 105.86211771800004,
                'latitude' => 20.994902652000064,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.86211771800004 20.994902652000064)')"),
                'status' => 'active',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: AC 32 Đại Từ
            [
                'venue_id' => '16bfc5b4-3934-47ed-bf90-b8a81dcd7c9c',
                'owner_id' => '6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe',
                'name' => 'AC 32 Đại Từ',
                'address' => '32 Đại Từ, Đại Kim, Hoàng Mai, Hà Nội',
                'longitude' => 105.83896259400007,
                'latitude' => 20.97195193700003,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.83896259400007 20.97195193700003)')"),
                'status' => 'locked',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Venue: Fbshop Kim Giang
            [
                'venue_id' => 'ebfe8b85-93e5-47b3-852d-6d61763ca7c9',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Fbshop Kim Giang',
                'address' => '918 Kim Giang, Kim Liệt, Thanh Trì, Hà Nội',
                'longitude' => 105.81218005200009,
                'latitude' => 20.960001591000037,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.81218005200009 20.960001591000037)')"),
                'status' => 'active',
                'bank_account_number' => '3505205270680',
                'bank_name' => 'agribank',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
