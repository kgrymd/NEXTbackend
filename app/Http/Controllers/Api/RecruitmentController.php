<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecruitmentResource;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function index()
    {
        $recruitments = Recruitment::with([
            'creator', // 募集を作成したUser
            'prefecture',
            'tags',
            'images',
            'appliedUsers', // 募集に申請したUsers（承認前含む）
            'approvedUsers', // 承認されたUsers（正式に参加）
            'participants', // 募集の参加詳細
            'comments'
        ])->get();

        return RecruitmentResource::collection($recruitments);
    }
}
