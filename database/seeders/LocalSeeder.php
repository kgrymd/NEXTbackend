<?php

namespace Database\Seeders;

use App\Models\Prefecture;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()->count(10)->create();

        Tag::create([
            'name' => 'スポーツ',
        ]);

        // 「Factoryの定義に合わせて、１０件のデータをつくってくれー」って感じの指定です
        Tag::factory()->count(10)->create();
    }
}
