<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateUserDataRequest;
use App\Http\Resources\ChatGroupResource;
use App\Http\Resources\UserResource;
use App\Models\ChatGroup;
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
        $me = Auth::user();

        // tagsリレーションを事前にロード
        $me->load('tags');
        return new UserResource($me);
    }


    public function updateData(UpdateUserDataRequest $request)
    {
        DB::beginTransaction(); // トランザクションを開始

        try {
            $data = [];

            // 画像がアップロードされているか確認
            if ($request->hasFile('iconFile')) {
                $savedPath = $request->iconFile->store('users/images'); // FILESYSTEM_DISK=s3と記述しているので第二引数いらない

                // 以前のアイコン画像を削除
                $previous_icon_path = Auth::user()->icon_path;
                if ($previous_icon_path) {
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

            Auth::user()->tags()->sync($request->tags);




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

    public function myParticipations(Request $request)
    {
        // 認証済みのユーザーを取得
        $user = Auth::user();

        // ユーザーが参加している募集を取得
        $participatedRecruitments = $user->participations()->with('recruitment:title,id,user_id')->orderBy('created_at', 'desc')->get();

        // return response()->json($participatedRecruitments);
        $result = $participatedRecruitments->map(
            function ($participation) {

                return [
                    'id' => $participation->id,
                    'recruitment_id' => $participation->recruitment_id,
                    'creator_id' => $participation->recruitment->user_id,
                    'recruitment_title' => $participation->recruitment->title,
                    'is_approved' => $participation->is_approved,
                    'joined_at' => $participation->joined_at,
                ];
            }
        );
        return response()->json($result);
    }

    public function chat_groups(Request $request)
    {
        $chat_groups = ChatGroup::with('users')
            ->whereHas('users', function (Builder $query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return ChatGroupResource::collection($chat_groups);
    }

    public function likedRecruitments(Request $request)
    {
        // 認証済みのユーザーを取得
        $user = Auth::user();

        // ユーザーがいいねした募集を取得（いいねした日時でソート）
        $likedRecruitments = $user->favoritedRecruitments()
            ->orderBy('recruitment_user.created_at', 'desc')
            ->get();

        $result = $likedRecruitments->map(
            function ($recruitment) {

                return [
                    'id' => $recruitment->id,
                    'creator_id' => $recruitment->user_id,
                    'title' => $recruitment->title,
                    'liked_at' => $recruitment->pivot->created_at,  // いいねした日時

                ];
            }
        );
        return response()->json($result);
    }

    public function createdRecruitments(Request $request)
    {
        // 認証済みのユーザーを取得
        $user = Auth::user();

        // ユーザーが作成した募集を取得
        $createdRecruitments = $user->createdRecruitments()->orderBy('created_at', 'desc')->get();

        $result = $createdRecruitments->map(function ($recruitment) {
            return [
                'id' => $recruitment->id,
                'title' => $recruitment->title,
                'description' => $recruitment->description,
                'created_at' => $recruitment->created_at,
            ];
        });

        return response()->json($result);
    }
}
