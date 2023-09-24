<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function addFavorite($recruitmentId)
    {
        $user = auth()->user(); // 認証済みのユーザー取得
        $recruitment = Recruitment::findOrFail($recruitmentId);

        $user->favoritedRecruitments()->attach($recruitmentId); // お気に入りに追加

        return response()->json(['message' => 'Successfully added to favorites']);
    }

    public function removeFavorite($recruitmentId)
    {
        $user = auth()->user();
        $recruitment = Recruitment::findOrFail($recruitmentId);

        $user->favoritedRecruitments()->detach($recruitmentId); // お気に入りから削除

        return response()->json(['message' => 'Successfully removed from favorites']);
    }
}
