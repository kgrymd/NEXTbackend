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
            ['name' => '未設定', 'latitude' => -15.7795, 'longitude' => -47.9292], // 設定なしのためにブラジルの緯度と経度を保存
            ['name' => '北海道', 'latitude' => 43.2203, 'longitude' => 142.8635],
            ['name' => '青森県', 'latitude' => 40.8244, 'longitude' => 140.7401],
            ['name' => '岩手県', 'latitude' => 39.7036, 'longitude' => 141.1527],
            ['name' => '宮城県', 'latitude' => 38.2688, 'longitude' => 140.8721],
            ['name' => '秋田県', 'latitude' => 39.7186, 'longitude' => 140.1024],
            ['name' => '山形県', 'latitude' => 38.2404, 'longitude' => 140.3636],
            ['name' => '福島県', 'latitude' => 37.7503, 'longitude' => 140.4676],
            ['name' => '茨城県', 'latitude' => 36.3418, 'longitude' => 140.4468],
            ['name' => '栃木県', 'latitude' => 36.5657, 'longitude' => 139.8836],
            ['name' => '群馬県', 'latitude' => 36.3912, 'longitude' => 139.0608],
            ['name' => '埼玉県', 'latitude' => 35.8616, 'longitude' => 139.6455],
            ['name' => '千葉県', 'latitude' => 35.6050, 'longitude' => 140.1233],
            ['name' => '東京都', 'latitude' => 35.6895, 'longitude' => 139.6917],
            ['name' => '神奈川県', 'latitude' => 35.4475, 'longitude' => 139.6425],
            ['name' => '新潟県', 'latitude' => 37.9024, 'longitude' => 139.0232],
            ['name' => '富山県', 'latitude' => 36.6953, 'longitude' => 137.2113],
            ['name' => '石川県', 'latitude' => 36.5947, 'longitude' => 136.6256],
            ['name' => '福井県', 'latitude' => 36.0652, 'longitude' => 136.2216],
            ['name' => '山梨県', 'latitude' => 35.6642, 'longitude' => 138.5684],
            ['name' => '長野県', 'latitude' => 36.6513, 'longitude' => 138.1812],
            ['name' => '岐阜県', 'latitude' => 35.3912, 'longitude' => 136.7222],
            ['name' => '静岡県', 'latitude' => 34.9769, 'longitude' => 138.3831],
            ['name' => '愛知県', 'latitude' => 35.1802, 'longitude' => 136.9067],
            ['name' => '三重県', 'latitude' => 34.7303, 'longitude' => 136.5086],
            ['name' => '滋賀県', 'latitude' => 35.0045, 'longitude' => 135.8686],
            ['name' => '京都府', 'latitude' => 35.0116, 'longitude' => 135.7681],
            ['name' => '大阪府', 'latitude' => 34.6863, 'longitude' => 135.5200],
            ['name' => '兵庫県', 'latitude' => 34.6913, 'longitude' => 135.1830],
            ['name' => '奈良県', 'latitude' => 34.6851, 'longitude' => 135.8050],
            ['name' => '和歌山県', 'latitude' => 34.2260, 'longitude' => 135.1675],
            ['name' => '鳥取県', 'latitude' => 35.5039, 'longitude' => 134.2381],
            ['name' => '島根県', 'latitude' => 35.4723, 'longitude' => 133.0505],
            ['name' => '岡山県', 'latitude' => 34.6618, 'longitude' => 133.9350],
            ['name' => '広島県', 'latitude' => 34.3966, 'longitude' => 132.4596],
            ['name' => '山口県', 'latitude' => 34.1861, 'longitude' => 131.4714],
            ['name' => '徳島県', 'latitude' => 34.0658, 'longitude' => 134.5593],
            ['name' => '香川県', 'latitude' => 34.3401, 'longitude' => 134.0434],
            ['name' => '愛媛県', 'latitude' => 33.8417, 'longitude' => 132.7661],
            ['name' => '高知県', 'latitude' => 33.5597, 'longitude' => 133.5311],
            ['name' => '福岡県', 'latitude' => 33.6068, 'longitude' => 130.4183],
            ['name' => '佐賀県', 'latitude' => 33.2496, 'longitude' => 130.2991],
            ['name' => '長崎県', 'latitude' => 32.7448, 'longitude' => 129.8737],
            ['name' => '熊本県', 'latitude' => 32.7898, 'longitude' => 130.7417],
            ['name' => '大分県', 'latitude' => 33.2382, 'longitude' => 131.6126],
            ['name' => '宮崎県', 'latitude' => 31.9111, 'longitude' => 131.4239],
            ['name' => '鹿児島県', 'latitude' => 31.5602, 'longitude' => 130.5581],
            ['name' => '沖縄県', 'latitude' => 26.2124, 'longitude' => 127.6809],
        ]);
    }
}
