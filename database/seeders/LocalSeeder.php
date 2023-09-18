<?php

namespace Database\Seeders;

use App\Models\ChatGroup;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Participant;
use App\Models\Prefecture;
use App\Models\Recruitment;
use App\Models\RecruitmentImage;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $testUser = User::create([
            "name" =>  "バカチンガー",
            "password" =>  "password",
            "email" =>  "hoge@hogemail.com",
            // "icon_path" =>  "users/images/51TAPoQmD1A7AWxhhHpK5jURqfI5nFyQsm3SMEUL.jpg",
            // "icon_path" =>  "https://yt3.googleusercontent.com/QyEXdCubnXVVAaSxIP9BY3wFdQc7GO_GY6RbZrtiJ0Rla31gt01W1pgBxAflgBOixsIqjBZQ=s900-c-k-c0x00ffffff-no-rj",
            "introduction" =>  "バカチンガーは福岡の未来をちょっとだけ明るくする庶民派ヒーロー。\n地球にやさしく、人にやさしく、なにより福岡をよくするためにFBS福岡放送から 生まれ出たキャラクターだ。",
        ]);

        User::factory()->count(10)->create();

        Tag::create([
            'name' => 'スポーツ',
        ]);

        // ↓Factoryの定義に合わせて１０件のデータをつくる
        Tag::factory()->count(10)->create();

        Recruitment::create([
            'title' =>  'トランポリンやりたいンゴ',
            'user_id' =>  1,
            'description' =>  'トランポリンやりたいンゴトランポリンやりたいンゴトランポリンやりたいンゴトランポリンやりたいンゴトランポリンやりたいンゴ',
            'youtube_url' =>  'https://www.youtube.com/watch?v=ufNWSa7ou74',
            'reference_url' =>  'https://www.instagram.com/reel/Cw8Hwi_BZde/?igshid=NTc4MTIwNjQ2YQ==',
            'prefecture_id' =>  1,
            'age_from' =>  20,
            'age_to' =>  25,
            'min_people' =>  1,
            'max_people' =>  4,
            'start_date' =>  now(), // 現在の日付・時間を設定する場合
            'end_date' =>  now()->addDays(10), // 現在から10日後の日付・時間を設定する場合
        ]);

        // 1つ目のダミーデータ
        Recruitment::create([
            'title' =>  'ボルダリングに挑戦したい！',
            'user_id' =>  2,
            'description' =>  'ボルダリングに興味があるけど一緒に初めてやってみる仲間を探しています！初心者歓迎！',
            'youtube_url' =>  'https://www.youtube.com/watch?v=example1',
            'reference_url' =>  'https://vt.tiktok.com/ZSLwefbFD/',
            'prefecture_id' =>  5,
            'age_from' =>  18,
            'age_to' =>  30,
            'min_people' =>  2,
            'max_people' =>  5,
            'start_date' =>  now(),
            'end_date' =>  now()->addDays(7),
        ]);

        // 2つ目のダミーデータ
        Recruitment::create([
            'title' =>  '週末キャンプ仲間を募集',
            'user_id' =>  3,
            'description' =>  '自然に囲まれて週末を過ごしたい！キャンプ初心者や経験者、どちらも大歓迎。',
            'youtube_url' =>  'https://www.youtube.com/watch?v=example2',
            'reference_url' =>  'https://www.camping-example.com',
            'prefecture_id' =>  10,
            'age_from' =>  20,
            'age_to' =>  40,
            'min_people' =>  3,
            'max_people' =>  8,
            'start_date' =>  now()->addDays(3),
            'end_date' =>  now()->addDays(5),
        ]);

        // 3つ目のダミーデータ
        Recruitment::create([
            'title' =>  '都会の喧騒から離れてハイキング',
            'user_id' =>  4,
            'description' =>  '山の中でのんびりとした時間を過ごすハイキング仲間を探しています。初心者大歓迎！',
            'youtube_url' =>  'https://www.youtube.com/watch?v=example3',
            'reference_url' =>  'https://www.hiking-example.com',
            'prefecture_id' =>  8,
            'age_from' =>  15,
            'age_to' =>  60,
            'min_people' =>  2,
            'max_people' =>  10,
            'start_date' =>  now()->addDays(2),
            'end_date' =>  now()->addDays(2),
        ]);

        $recruitments = Recruitment::all();
        $users = User::all();

        foreach ($recruitments as $recruitment) {
            // 募集ごとの参加人数を設定
            $participants = rand(0, $recruitment->max_people); // ← `max_people`を指定して、その数までのランダムな数を取得

            // ユーザーからランダムに選ぶ
            $participantUsers = Arr::random($users->toArray(), $participants);

            foreach ($participantUsers as $participantUser) {
                // ランダムな過去の日時を生成
                $randomDaysAgo = rand(1, 30); // 1日前から30日前までのランダムな日数を取得
                $joinedAt = Carbon::now()->subDays($randomDaysAgo);

                // participantsテーブルにデータを挿入
                Participant::create([
                    'user_id' => $participantUser['id'],
                    'recruitment_id' => $recruitment->id,
                    'is_approved' => rand(0, 1), // ランダムに承認状態を設定 (0 or 1)
                    'joined_at' => $joinedAt
                ]);
            }
        }

        // recruitment_imagesテーブルへのデータ挿入
        foreach ($recruitments as $recruitment) {
            $imageCount = rand(1, 3);
            for ($i = 1; $i <= $imageCount; $i++) {
                RecruitmentImage::create([
                    'recruitment_id' => $recruitment->id,
                    'image_path' => "/image{$i}.png"
                ]);
            }
        }

        // ↑で作った募集に紐づくメッセージを作成（１募集１０メッセージ作成）
        foreach ($recruitments as $recruitment) {
            Comment::factory()
                ->count(10)
                ->create([
                    'recruitment_id' => $recruitment->id,
                ]);
        }


        ChatGroup::factory()
            ->hasAttached($users->random(3)->push($testUser))
            ->create([
                'name' => 'Quiet Room',
            ]);

        // ChatGroup::factory()
        //     ->hasAttached($users->push($testUser))
        //     ->has(
        //         Message::factory()
        //             ->count(100)
        //             ->recycle($users)
        //     )
        //     ->create([
        //         'name' => 'Noisy Room',
        //     ]);

        ChatGroup::factory()
            ->hasAttached($randomUsers = $users->random(5)->push($testUser))
            ->has(
                Message::factory()
                    ->count(10)
                    ->recycle($randomUsers)
            )
            ->create([
                'name' => 'Normal Room',
            ]);
    }
}
