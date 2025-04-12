<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FieldOpeningHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [];

        $days = [
            ['monday',    '06:00:00', '22:00:00'],
            ['tuesday',   '07:00:00', '21:30:00'],
            ['wednesday', '08:00:00', '22:00:00'],
            ['thursday',  '07:30:00', '21:00:00'],
            ['friday',    '06:30:00', '23:00:00'],
            ['saturday',  '07:00:00', '23:30:00'],
            ['sunday',    '08:00:00', '20:00:00'],
        ];

        $fieldIds = [
            'dcb38c24-adb0-4c57-9551-ed4413becf4o',
            'dcb38c24-adb0-4c57-9551-ed4413becf4m',
        ];

        foreach ($fieldIds as $fieldId) {
            foreach ($days as [$day, $open, $close]) {
                $data[] = [
                    'field_id' => $fieldId,
                    'day_of_week' => $day,
                    'opening_time' => $open,
                    'closing_time' => $close,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('field_opening_hours')->insert($data);
    }
}
