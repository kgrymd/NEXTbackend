<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RecruitmentCreationRequest;
use App\Http\Resources\RecruitmentResource;
use App\Models\ChatGroup;
use App\Models\Participant;
use App\Models\Recruitment;
use App\Models\RecruitmentImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RecruitmentController extends Controller
{
    public function index()
    {
        $now = Carbon::now(); // 現在の日時を取得

        $recruitments = Recruitment::with([
            'creator', // 募集を作成したUser
            'prefecture',
            'tags',
            'images',
            'appliedUsers', // 募集に申請したUsers（承認前含む）
            'approvedUsers', // 承認されたUsers（正式に参加）
            'participants', // 募集の参加詳細
            'comments'
        ])
            ->where('start_date', '<=', $now) // 開始日が現在の日時以前
            ->where('end_date', '>=', $now)   // 終了日が現在の日時以降
            ->orderBy('created_at', 'desc')
            // ->paginate(20);
            ->get();

        return RecruitmentResource::collection($recruitments);
    }

    public function show($id)
    {
        // RecruitmentモデルをIDで検索
        $recruitment = Recruitment::with('tags')->findOrFail($id);

        return new RecruitmentResource($recruitment);
    }

    public function creation(RecruitmentCreationRequest $request)
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




            $name = $request->input('title'); // chat_groupの名前を募集のタイトルにする

            // Eloquentを使ってDBに保存
            $storedChatGroup = ChatGroup::create([
                'name' => $name,
                'uuid' => \Str::uuid(),
                'recruitment_id' => $recruitmentResponse->id,
            ]);

            // 現在の時間を取得
            $now = Carbon::now();

            // 募集をを作った人はそのまま募集のにグループチャットに参加している状態を作りたい
            // つまり、chat_group_userテーブルの中間テーブルに紐付けデータを作成
            // $storedChatGroup->users()->sync([Auth::id()]);
            $storedChatGroup->users()->sync([
                Auth::id() => [
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            ]);




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





    public function update(Request $request, $id)
    {
        // return response()->json($request->all());
        Log::info('Received Request Data:', $request->all());
        DB::beginTransaction();

        try {
            $recruitment = Recruitment::find($id);

            // 基本情報の更新処理
            $recruitmentData = [];
            $fields = ['title', 'description', 'youtube_url', 'reference_url', 'prefecture_id', 'age_from', 'age_to', 'min_people', 'max_people', 'start_date', 'end_date'];


            foreach ($fields as $field) {
                // if ($request->input($field) !== null) {
                $value = $request->input($field);
                $recruitmentData[$field] = $value === "null" ? null : $value;
                // }
            }



            $recruitment->update($recruitmentData);




            // 1. 既存の画像の処理
            $existingImageIds = $request->input('images', []);
            foreach ($recruitment->images as $storedImage) {
                if (!in_array($storedImage->id, $existingImageIds)) {
                    // S3から画像を削除
                    Storage::disk('s3')->delete($storedImage->image_path);
                    // DBから画像情報を削除
                    $storedImage->delete();
                }
            }

            // 2. 新しい画像の処理
            if ($request->hasFile('newImages')) {
                foreach ($request->file('newImages') as $image) {
                    $savedPath = $image->store('recruitment/images', 's3');
                    $recruitment->images()->create(['image_path' => $savedPath]);
                }
            }




            // タグの更新
            $recruitment->tags()->sync($request->tags);

            DB::commit();

            return response()->json(['message' => 'Successfully updated!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }









    public function join(Request $request)
    {
        $participantData = [
            'user_id' => $request->user_id,
            'recruitment_id' => $request->recruitment_id,
            'is_approved' => $request->is_approved,
            'joined_at' => now()
        ];

        $participant = Participant::create($participantData);

        // is_approvedが1の場合、該当するchat_groupにユーザーを追加
        if ($participant->is_approved == 1) {
            // 既に存在しているchat_groupを検索
            $chatGroup = ChatGroup::where('recruitment_id', $participant->recruitment_id)->first();

            if ($chatGroup) {
                // そのchat_groupにユーザーがすでに存在しているかをチェック
                $existingChatGroupUser = $chatGroup->users()->where('user_id', $participant->user_id)->first();

                // ユーザーがまだchat_groupに存在していなければ、追加
                if (!$existingChatGroupUser) {
                    $chatGroup->users()->attach($participant->user_id);
                }
            }
        }


        return response()->json($participant, 201);
    }


    // 自分の設定位置と、募集の設定位置の距離の近い順に取得（緯度と経度を使用して、ハーバーサイン公式を使用して各募集の距離を計算）
    // 注意: ここで使用されている3959は、地球の半径をマイル単位で表したもの。キロメートルを使用する場合は、この数値を6371に変更。
    public function nearbyRecruitments(Request $request)
    {
        // 認証済みのユーザーを取得
        $user = auth()->user();

        // ユーザーの都道府県からの緯度・経度を取得
        $userLocation = [
            'latitude' => $user->prefecture->latitude,
            'longitude' => $user->prefecture->longitude
        ];


        $query = Recruitment::with(['tags', 'prefecture'])
            ->join('prefectures', 'recruitments.prefecture_id', '=', 'prefectures.id')
            ->select(
                'recruitments.*',
                'prefectures.latitude',
                'prefectures.longitude',
                DB::raw('(3959 * acos(cos(radians(?)) * cos(radians(prefectures.latitude)) * cos(radians(prefectures.longitude) - radians(?)) + sin(radians(?)) * sin(radians(prefectures.latitude)))) AS distance')
            )
            ->orderBy('distance');

        // バインド変数の値をセット
        $query->addBinding($userLocation['latitude'], 'select');
        $query->addBinding($userLocation['longitude'], 'select');
        $query->addBinding($userLocation['latitude'], 'select');

        $recruitments = $query->get();



        return RecruitmentResource::collection($recruitments);
    }


    // おすすめ表示
    // ユーザーの位置と募集の設定位置の距離が近い順
    // &年齢範囲内or年齢範囲未設定
    // &ユーザーが持っているタグと一致するタグの数が多い募集順
    // &募集人数がmax超えてないやつ
    // &現在の時刻が募集期間ないのやつ
    public function suggestRecruitments(Request $request)
    {
        // 認証済みのユーザーを取得
        $user = auth()->user();
        $now = Carbon::now(); // 現在の日時を取得

        // ユーザーの都道府県からの緯度・経度を取得
        $userLocation = [
            'latitude' => $user->prefecture->latitude,
            'longitude' => $user->prefecture->longitude
        ];

        // ユーザーが持っているタグのIDを取得
        $userTags = $user->tags->pluck('id');

        $query = Recruitment::with(['tags', 'prefecture'])
            ->join('prefectures', 'recruitments.prefecture_id', '=', 'prefectures.id')
            ->select(
                'recruitments.*',
                'prefectures.latitude',
                'prefectures.longitude',
                DB::raw('(3959 * acos(cos(radians(?)) * cos(radians(prefectures.latitude)) * cos(radians(prefectures.longitude) - radians(?)) + sin(radians(?)) * sin(radians(prefectures.latitude)))) AS distance')
            )
            ->orderBy('distance');

        // バインド変数の値をセット
        $query->addBinding($userLocation['latitude'], 'select');
        $query->addBinding($userLocation['longitude'], 'select');
        $query->addBinding($userLocation['latitude'], 'select');

        // ユーザーの年齢が募集の年齢の範囲内、または年齢範囲が設定されていない募集をフィルタリング
        $query->where(function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where(function ($q) use ($user) {
                    $q->whereNull('age_from')
                        ->orWhere('age_from', '<=', $user->age);
                })
                    ->where(function ($q) use ($user) {
                        $q->whereNull('age_to')
                            ->orWhere('age_to', '>=', $user->age);
                    });
            });
        });


        // ユーザーが持っているタグと一致するタグの数を計算
        $query->withCount(['tags' => function ($q) use ($userTags) { // withCountは、リレーション（関連）の数をカウントするためのメソッド
            $q->whereIn('tags.id', $userTags); // whereInは、指定されたカラムの値が指定された配列の中のいずれかの値と一致するデータをフィルタリングするためのメソッド
        }]);


        // approvedUsersの数がmax_peopleより少ない募集をフィルタリング
        $query->whereDoesntHave('participants', function ($q) {
            $q->select(DB::raw('count(*) as count'))
                ->where('is_approved', 1)
                ->groupBy('recruitment_id')
                ->having('count', '>=', DB::raw('recruitments.max_people'));
        });

        // 募集期間内のフィルタリングを追加
        $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);


        // 一致するタグの数で降順ソート
        $query->orderByDesc('tags_count');


        $recruitments = $query->get();

        return RecruitmentResource::collection($recruitments);
    }

    public function searchRecruitments(Request $request)
    {
        // キーワードの取得
        $keyword = $request->input('keyword');
        // 現在の日時を取得
        $now = Carbon::now(); // 現在の日時を取得

        // キーワードを含む募集を検索
        $recruitments = Recruitment::with(['tags', 'prefecture'])
            // ->where('title', 'LIKE', '%' . $keyword . '%')
            // ->orWhere('description', 'LIKE', '%' . $keyword . '%')
            // ->orWhereHas('tags', function ($query) use ($keyword) {
            //     $query->where('name', 'LIKE', '%' . $keyword . '%');
            // })
            // ->whereDate('start_date', '<=', $now)  // 募集開始日が現在日時以前であること
            // ->whereDate('end_date', '>=', $now)    // 募集終了日が現在日時以降であること
            // ->get();
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('description', 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('tags', function ($subQuery) use ($keyword) {
                        $subQuery->where(
                            'name',
                            'LIKE',
                            '%' . $keyword . '%'
                        );
                    });
            })
            ->whereDate('start_date', '<=', $now)  // 募集開始日が現在日時以前であること
            ->whereDate('end_date', '>=', $now)    // 募集終了日が現在日時以降であること
            ->get();

        return RecruitmentResource::collection($recruitments);
    }
}
