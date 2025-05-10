<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'uuid' => '05b35154-d7bd-45ca-bb17-666d093c8324',
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'phone_number' => '+84987654321',
                'email_verified_at' => '2025-04-09 12:07:50',
                'password' => '$2y$12$nGB6TXDB5bTNRv3X5Rgv8exDXc.4uBQJA7bmSYeljH/oH75TQMWrG',
                'role' => 'admin',
                'remember_token' => null,
                'created_at' => '2025-04-10 05:02:10',
                'updated_at' => '2025-04-10 05:02:10',
            ],
            [
                'uuid' => '2adacea2-7414-4ed3-9bac-4632f29cca5c',
                'name' => 'IAMTDF',
                'email' => 'thaidangfa@gmail.com',
                'phone_number' => '+84987654321',
                'email_verified_at' => '2025-04-09 12:07:50',
                'password' => '$2y$12$43fbpisJuV4zlaiPBPjmjuAOxg2Z4fka92OBj12MJFTC.kvSAoH.W',
                'role' => 'owner',
                'remember_token' => null,
                'created_at' => '2025-04-10 05:02:33',
                'updated_at' => '2025-04-10 05:02:33',
            ],
            [
                'uuid' => '2adacea2-7414-4ed3-9bac-4632f29cca5d',
                'name' => 'THÁI ĐẶNG',
                'email' => 'thaidangfa12@gmail.com',
                'phone_number' => '+84949628929',
                'email_verified_at' => '2025-04-09 12:07:50',
                'password' => '$2y$12$43fbpisJuV4zlaiPBPjmjuAOxg2Z4fka92OBj12MJFTC.kvSAoH.W',
                'role' => 'owner',
                'remember_token' => null,
                'created_at' => '2025-04-10 05:02:33',
                'updated_at' => '2025-04-10 05:02:33',
            ],
            [
                'uuid' => '5c6fd6fd-4391-4ff9-bc97-535a202fe94b',
                'name' => 'User 1',
                'email' => 'user1@gmail.com',
                'phone_number' => '+84987654321',
                'email_verified_at' => '2025-04-09 12:07:50',
                'password' => '$2y$12$x.4xg4ciAdHzVL4IfY3BQuLR3WSYI4pVduxMEvIAd/DSNOLkw3SvG',
                'role' => 'user',
                'remember_token' => null,
                'created_at' => '2025-04-10 05:01:23',
                'updated_at' => '2025-04-10 05:01:23',
            ],
            [
                'uuid' => '6f6199b6-6bbf-41b4-8bf7-72dbc044f6fe',
                'name' => 'OWNER 2',
                'email' => 'owner2@gmail.com',
                'phone_number' => '+84987654321',
                'email_verified_at' => '2025-04-09 12:07:50',
                'password' => '$2y$12$2OggiY1pQdJqoDZxnManYuNSe.RBblg8BR5Y4/GtfNFd.RkAhK18C',
                'role' => 'owner',
                'remember_token' => null,
                'created_at' => '2025-04-10 05:02:50',
                'updated_at' => '2025-04-10 05:02:50',
            ],
            [
                'uuid' => 'a721ac9c-c392-4c97-8a01-37e4c803b9ac',
                'name' => 'User 2',
                'email' => 'user2@gmail.com',
                'phone_number' => '+84987654321',
                'email_verified_at' => '2025-04-09 12:07:50',
                'password' => '$2y$12$2uzj9fcMmhvlIdxubx0K4e/dGvaiL4LJ4zEMAU7FzQC7PjQWnrO2K',
                'role' => 'user',
                'remember_token' => null,
                'created_at' => '2025-04-10 05:07:39',
                'updated_at' => '2025-04-10 05:07:39',
            ],
        ]);
    }
}
