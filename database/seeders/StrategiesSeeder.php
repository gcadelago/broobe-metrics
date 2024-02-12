<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrategiesSeeder extends Seeder
{
    public function run(): void
    {
        if (!DB::table('strategies')->find(1)) {
            DB::table('strategies')->insert([
                [
                    'id'   => 1,
                    'name' => 'DESKTOP',
                ],
                [
                    'id'   => 2,
                    'name' => 'MOBILE',
                ]
            ]);
        }
    }
}
