<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruitment;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RecruitmentTagController extends Controller
{
    /**
     * 既存の募集にタグを追加するメソッド
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $recruitmentId
     * @return \Illuminate\Http\Response
     */
    public function attachTagToRecruitment(Request $request, $recruitmentId)
    {
        // 1. 募集とタグの存在確認
        $recruitment = Recruitment::find($recruitmentId);
        $tag = Tag::find($request->input('tag_id'));

        if (!$recruitment || !$tag) {
            return response()->json(['message' => '募集またはタグが存在しません。'], 404);
        }

        // 2. すでにタグが紐づけられているかの確認
        if ($recruitment->tags->contains($tag->id)) {
            return response()->json(['message' => 'この募集は既に該当のタグと紐づいています。'], 400);
        }

        // 3. タグを募集に紐づける
        $recruitment->tags()->attach($tag->id);

        return response()->json(['message' => 'タグを募集に追加しました。'], 200);
    }

    // 募集のタグを更新するメソッド
    public function update(Request $request, $recruitmentId)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // 募集を見つける
        $recruitment = Recruitment::findOrFail($recruitmentId);

        // 認証されたユーザーが募集のオーナーであるかを確認 後でつかう
        // if (Auth::id() !== $recruitment->user_id) {
        //     return response()->json(['error' => 'Permission Denied'], 403);
        // }

        // 募集のタグを更新
        $recruitment->tags()->sync($request->tags);

        return response()->json(['message' => 'Tags updated successfully.']);
    }
}
