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
            [
                'venue_id' => '7d465e0a-ce6b-4bf5-9327-228de8b96302',
                'owner_id' => '6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe',
                'name' => 'Venue 2 của Owner 2',
                'address' => 'HCM',
                'longitude' => 105.843,
                'latitude' => 20.9995,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.843 20.9995)')"),
                'status' => 'locked',
                'bank_account_number' => '1234567890',
                'bank_name' => 'Vietcombank',
                'created_at' => '2025-04-10 19:36:01',
                'updated_at' => '2025-04-10 19:36:01',
            ],
            [
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Venue 1 của Owner 1',
                'address' => 'Hà Nội',
                'longitude' => 105.8417,
                'latitude' => 21.002,
                'bank_account_number' => '0949628929',
                'bank_name' => 'OCB',
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.8417 21.002)')"),
                'status' => 'locked',
                'created_at' => '2025-04-10 19:36:08',
                'updated_at' => '2025-04-10 19:36:08',
            ],
            [
                'venue_id' => 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Venue 2 của Owner 1',
                'address' => 'HCM',
                'longitude' => 105.849,
                'latitude' => 21.0042,
                'bank_account_number' => 'iamtdf',
                'bank_name' => 'Techcombank',
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.849 21.0042)')"),
                'status' => 'locked',
                'created_at' => '2025-04-10 19:35:48',
                'updated_at' => '2025-04-10 19:35:48',
            ],
            [
                'venue_id' => 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f',
                'owner_id' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'Venue 1 của Owner 1',
                'address' => 'Hà nội',
                'longitude' =>  105.8288 ,
                'latitude' =>  21.0019,
                'coordinates' => DB::raw("ST_GeomFromText('Point(105.8288 21.0019)')"),
                'status' => 'locked',
                'bank_account_number' => '1234567890',
                'bank_name' => 'Vietinbank',
                'created_at' => '2025-04-10 19:24:46',
                'updated_at' => '2025-04-10 19:24:46',
            ],
        ]);
    }
}
