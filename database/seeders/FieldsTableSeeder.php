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
            [
                'field_id' => 'd0ee0d1c-7aeb-42a3-835c-dbe7b78bbd94',
                'venue_id' => 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f',
                'sport_type_id' => 7,
                'field_name' => 'PickerBall',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:56:16',
                'updated_at' => '2025-04-10 19:56:16',
            ],
            [
                'field_id' => 'dcb38c24-adb0-4c57-9551-ed4413becf4e',
                'venue_id' => 'd2d1ec4a-7b73-4740-815a-87a4d6b9146f',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:54:55',
                'updated_at' => '2025-04-10 19:54:55',
            ],
            [
                'field_id' => 'dcb38c24-adb0-4c57-9551-ed4413becf4h',
                'venue_id' => 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93',
                'sport_type_id' => 7,
                'field_name' => 'PickerBall',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:54:55',
                'updated_at' => '2025-04-10 19:54:55',
            ],
            [
                'field_id' => 'dcb38c24-adb0-4c57-9551-ed4413becf4k',
                'venue_id' => 'ce8db96c-8468-45a4-a8f7-fb7c6e8b6b93',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:54:55',
                'updated_at' => '2025-04-10 19:54:55',
            ],
            [
                'field_id' => 'dcb38c24-adb0-4c57-9551-ed4413becf4o',
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'sport_type_id' => 7,
                'field_name' => 'PickerBall',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:54:55',
                'updated_at' => '2025-04-10 19:54:55',
            ],
            [
                'field_id' => 'dcb38c24-adb0-4c57-9551-ed4413becf4m',
                'venue_id' => '8aa84f24-773d-4e16-a40d-cc449abb4151',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:54:55',
                'updated_at' => '2025-04-10 19:54:55',
            ],
            [
                'field_id' => 'dcb38c24-adb0-4c57-9551-ed4413becf4n',
                'venue_id' => '7d465e0a-ce6b-4bf5-9327-228de8b96302',
                'sport_type_id' => 7,
                'field_name' => 'PickerBall',
                'default_price' => 200000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:54:55',
                'updated_at' => '2025-04-10 19:54:55',
            ],
            [
                'field_id' => 'dcb38c24-adb0-4c57-9551-ed4413becf4p',
                'venue_id' => '7d465e0a-ce6b-4bf5-9327-228de8b96302',
                'sport_type_id' => 1,
                'field_name' => 'Cầu Lông',
                'default_price' => 150000.00,
                'is_active' => 1,
                'created_at' => '2025-04-10 19:54:55',
                'updated_at' => '2025-04-10 19:54:55',
            ],
        ]);
    }
}
