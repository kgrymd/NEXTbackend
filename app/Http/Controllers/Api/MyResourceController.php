<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // return response()->json($me);

        // tagsリレーションを事前にロード
        $me->load('tags');
        return new UserResource($me);
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

    // ↓多分もういらないので最終的に消す
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


    public function updateData(Request $request)
    {
        DB::beginTransaction(); // トランザクションを開始

        try {
            $data = [];

            // 画像がアップロードされているか確認
            if ($request->hasFile('iconFile')) {
                // $savedPath = $request->iconFile->store('users/images', 's3'); // 画像保存とs3に保存
                $savedPath = $request->iconFile->store('users/images'); // FILESYSTEM_DISK=s3と記述しているので第二引数いらない↑

                // 以前のアイコン画像を削除
                $previous_icon_path = Auth::user()->icon_path;
                if ($previous_icon_path) {
                    // if (Storage::disk('s3')->exists($previous_icon_path)) {
                    //     Storage::disk('s3')->delete($previous_icon_path);
                    // }
                    // FILESYSTEM_DISK=s3と記述しているのでdiskの引数いらない↑
                    if (Storage::disk()->exists($previous_icon_path)) {
                        Storage::disk()->delete($previous_icon_path);
                    }
                }

                $data['icon_path'] = $savedPath; // アイコンのパスを保存
            }

            // 名前の処理
            if ($request->input('name')) {
                $data['name'] = $request->input('name');
            }

            // 年齢の処理
            if ($request->input('age')) {
                $data['age'] = $request->input('age');
            }

            // 自己紹介の処理
            if ($request->input('introduction')) {
                $data['introduction'] = $request->input('introduction');
            }

            // 都道府県IDの処理
            if ($request->input('prefecture_id')) {
                $data['prefecture_id'] = $request->input('prefecture_id');
            }

            // 更新処理
            Auth::user()->update($data);

            // コミット
            DB::commit();

            return response()->noContent(); // 正常終了
        } catch (\Exception $e) {
            // エラー処理
            Log::debug($e);
            DB::rollback(); // ロールバック
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateTags(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $tagIds = $request->input('tags');
        $user->tags()->sync($tagIds);

        return response()->json(['message' => 'Tags updated successfully']);
    }
}
