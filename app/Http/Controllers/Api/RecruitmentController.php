<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecruitmentResource;
use App\Models\Participant;
use App\Models\Recruitment;
use App\Models\RecruitmentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        ])->orderBy('created_at', 'desc')->get(); //とりあえず新着順で返してる

        return RecruitmentResource::collection($recruitments);
    }

    public function creation(Request $request)
    {
        DB::beginTransaction();
        try {
            // recruitmentsテーブルにデータを保存
            $recruitmentData = [];

            $recruitmentData['user_id'] = Auth::user()->id;

            if ($request->input('title')) {
                $recruitmentData['title'] = $request->input('title');
            }
            if ($request->input('description')) {
                $recruitmentData['description'] = $request->input('description');
            }
            if ($request->input('youtube_url')) {
                $recruitmentData['youtube_url'] = $request->input('youtube_url');
            }
            if ($request->input('reference_url')) {
                $recruitmentData['reference_url'] = $request->input('reference_url');
            }
            if ($request->input('prefecture_id')) {
                $recruitmentData['prefecture_id'] = $request->input('prefecture_id');
            }
            if ($request->input('age_from')) {
                $recruitmentData['age_from'] = $request->input('age_from');
            }
            if ($request->input('age_to')) {
                $recruitmentData['age_to'] = $request->input('age_to');
            }
            if ($request->input('min_people')) {
                $recruitmentData['min_people'] = $request->input('min_people');
            }
            if ($request->input('max_people')) {
                $recruitmentData['max_people'] = $request->input('max_people');
            }
            if ($request->input('start_date')) {
                $recruitmentData['start_date'] = $request->input('start_date');
            }
            if ($request->input('end_date')) {
                $recruitmentData['end_date'] = $request->input('end_date');
            }



            $recruitmentResponse = Recruitment::create($recruitmentData);


            // logger('All files in the request: ', $request->allFiles());

            // 画像がアップロードされているか確認
            if ($request->hasFile('images')) {
                // logger('Images found in the request.'); // ログにメッセージを記録

                $recruitmentImages = $request->file('images');
                if (!is_array($recruitmentImages)) {
                    $recruitmentImages = [$recruitmentImages];
                }
                // logger('Number of images: ' . count($recruitmentImages)); // 画像の数をログに出力

                foreach ($recruitmentImages as $recruitmentImage) {

                    // $savedPath = $request->iconFile->store('users/images', 's3'); // 画像保存とs3に保存
                    // $savedPath = $recruitmentImage->images->store('users/images'); // FILESYSTEM_DISK=s3と記述しているので第二引数いらない↑
                    $savedPath = $recruitmentImage->store('recruitment/images');

                    $recruitmentImageData = [
                        'recruitment_id' => $recruitmentResponse->id,
                        'image_path' => $savedPath,
                    ];
                    $recruitmentImagesResponse = RecruitmentImage::create($recruitmentImageData);
                }
            } else {
                logger('No images found in the request.'); // ログにメッセージを記録
            }




            // tags関連の処理

            // logger('Tags data: ', $request->tags);


            $recruitmentResponse->tags()->sync($request->tags);

            $participantData = [
                'user_id' => Auth::user()->id,
                'recruitment_id' => $recruitmentResponse->id,
                'is_approved' => 1,
                'joined_at' => now()
            ];

            $participant = Participant::create($participantData);


            // 全ての操作が成功したらコミット
            DB::commit();
            return response()->json(['message' => 'Successfully created!'], 200);
            // return response()->json(['savedPath' => $savedPath]);
        } catch (\Exception $e) {
            // エラーが発生したらロールバック
            DB::rollback();
            // return response()->json(['message' => 'Failed to create!'], 500);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function join(Request $request)
    {
        $participant = Participant::create($request->all());
        return response()->json($participant, 201);
    }
}
