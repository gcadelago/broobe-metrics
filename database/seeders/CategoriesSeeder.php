<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        if (!DB::table('categories')->find(1)) {
            DB::table('categories')->insert(
                [
                    'id'   => 1,
                    'name' => 'ACCESSIBILITY',
                ],
                [
                    'id'   => 2,
                    'name' => 'BEST_PRACTICES',
                ],
                [
                    'id'   => 3,
                    'name' => 'PERFORMANCE',
                ],
                [
                    'id'   => 4,
                    'name' => 'PWA',
                ],
                [
                    'id'   => 5,
                    'name' => 'SEO',
                ]
            );
        }
    }
}
