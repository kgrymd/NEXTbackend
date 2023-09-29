<?php

namespace App\Http\Controllers;

use App\Models\ChatGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UnchartedChallengeController extends Controller
{
    public function store()
    {
        // uncharted_challengeが1のユーザーを取得
        $users = User::where('uncharted_challenge', 1)->get();
        $users = $users->shuffle(); // ユーザーの順序をランダムにする

        // 4人ごとにユーザーをグループ化
        $userGroups = $users->chunk(4);

        // ユーザー数が4で割り切れない場合
        if (
            $users->count() % 4 !== 0
        ) {
            $lastGroup = $userGroups->pop(); // 最後のグループを取り出す
            $counter = 0;
            // 最後のグループのユーザーを前のグループに追加
            foreach ($lastGroup as $user) {
                $userGroups[$counter % count($userGroups)]->push($user);
                $counter++;
            }
        }

        // 現在の年と月を取得
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        foreach ($userGroups as $group) {
            // chat_groupを作成
            $chatGroup = ChatGroup::create([
                'uuid' => \Str::uuid(),
                'name' => $year . "年" . $month . "月",
                'year' => $year,
                'month' => $month
            ]);

            // ユーザーをchat_groupに関連付ける
            foreach ($group as $user) {
                $chatGroup->users()->attach($user->id);
            }
        }
    }
}
