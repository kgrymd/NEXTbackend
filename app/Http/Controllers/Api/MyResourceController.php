<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MyResourceController extends Controller
{
    public function me(Request $request)
    {

        // ↓で認証済みのリクエストしている人のUserインスタンスが取得できる
        $me = Auth::user();
        // 別に以下のように書いてもOK（が以下の書き方を簡単に書く方法が↑）
        // $myId = Auth::id();
        // $me = User::find($myId);
        // とりあえず、そのままレスポンス（後で整形）
        return response()->json($me);
    }

    public function tags(Request $request)
    {
        $tags = Tag::with('users')
            ->whereHas('users', function (Builder $query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($tags);
    }

    public function updateIcons(Request $request)
    {
        // 画像がアップロードされているか確認
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No image uploaded'], 400);
        }
        DB::transaction(function () use ($request) {


            $savedPath = $request->image->store('users/images');

            try {
                Auth::user()
                    ->fill([
                        'icon_path' => $savedPath,
                    ])
                    ->save();
            } catch (\Exception $e) {
                // DBでのエラーが起きた場合は、保存したファイルを削除
                Storage::delete($savedPath);
                throw $e;
            }
        });

        return response()->json(
            route('web.users.image', ['userId' => Auth::id()])
        );
    }
}
