<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FieldPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $data = [];
        $fieldPriceId = 1;

        $fieldPrices = [
            '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d' => [
                ['monday',    '08:00:00', '09:00:00', 140000, 30],
                ['monday',    '09:00:00', '11:00:00', 150000, 40],
                ['monday',    '11:00:00', '13:00:00', 140000, 30],
                ['monday',    '13:00:00', '14:00:00', 180000, 60],
                ['monday',    '14:00:00', '17:00:00', 150000, 45],
                ['monday',    '17:00:00', '19:00:00', 100000, 30],
                ['monday',    '19:00:00', '21:00:00', 170000, 40],

                ['tuesday',    '08:00:00', '10:00:00', 130000, 30],
                ['tuesday',    '10:00:00', '12:00:00', 110000, 40],
                ['tuesday',    '12:00:00', '15:00:00', 160000, 45],
                ['tuesday',    '15:00:00', '21:00:00', 180000, 40],

                ['wednesday',    '08:00:00', '12:00:00', 130000, 30],
                ['wednesday',    '12:00:00', '14:00:00', 130000, 40],
                ['wednesday',    '14:00:00', '21:00:00', 130000, 35],

                ['thursday',    '08:00:00', '21:00:00', 200000, 30],
                ['friday',    '08:00:00', '22:00:00', 200000, 30],
                ['saturday',    '08:00:00', '23:00:00', 200000, 30],
                ['sunday',    '08:00:00', '23:00:00', 200000, 30],
            ],
        ];

        foreach ($fieldPrices as $fieldId => $prices) {
            foreach ($prices as $price) {
                $data[] = [
                    'field_price_id' => $fieldPriceId++,
                    'field_id' => $fieldId,
                    'day_of_week' => $price[0],
                    'start_time' => $price[1],
                    'end_time' => $price[2],
                    'price' => $price[3],
                    'min_rental' => $price[4],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('field_price')->insert($data);
    }
}
