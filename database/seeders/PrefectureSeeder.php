<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrefectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prefectures')->insert([
            ['name' => 'Hokkaido', 'latitude' => 43.2203, 'longitude' => 142.8635],
            ['name' => 'Aomori', 'latitude' => 40.8244, 'longitude' => 140.7401],
            [
                'name' => 'Iwate',
                'latitude' => 39.7036,
                'longitude' => 141.1527,
            ],
            [
                'name' => 'Miyagi',
                'latitude' => 38.2688,
                'longitude' => 140.8721,
            ],
            [
                'name' => 'Akita',
                'latitude' => 39.7186,
                'longitude' => 140.1024,
            ],
            [
                'name' => 'Yamagata',
                'latitude' => 38.2404,
                'longitude' => 140.3636,
            ],
            [
                'name' => 'Fukushima',
                'latitude' => 37.7503,
                'longitude' => 140.4676,
            ],
            [
                'name' => 'Ibaraki',
                'latitude' => 36.3418,
                'longitude' => 140.4468,
            ],
            [
                'name' => 'Tochigi',
                'latitude' => 36.5657,
                'longitude' => 139.8836,
            ],
            [
                'name' => 'Gunma',
                'latitude' => 36.3912,
                'longitude' => 139.0608,
            ],



            // ... 他の都道府県データ
        ]);
    }
}