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

        User::create([
            "name" =>  "バカチンガー",
            "password" =>  "password",
            "email" =>  "hoge@hogemail.com",
            "icon_path" =>  "users/images/51TAPoQmD1A7AWxhhHpK5jURqfI5nFyQsm3SMEUL.jpg",
            "introduction" =>  "バカチンガーは福岡の未来をちょっとだけ明るくする庶民派ヒーロー。\n地球にやさしく、人にやさしく、なにより福岡をよくするためにFBS福岡放送から 生まれ出たキャラクターだ。",
        ]);

        User::factory()->count(10)->create();

        Tag::create([
            'name' => 'スポーツ',
        ]);

        // ↓Factoryの定義に合わせて１０件のデータをつくる
        Tag::factory()->count(10)->create();
    }
}
