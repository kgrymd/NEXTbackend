<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RecruitmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array //←いるんかいらんのか後で調べる
    {
        // 認証されたユーザーを取得
        $user = Auth::user();

        // ユーザーがこの募集を「いいね」しているかどうかを確認
        $isLikedByUser = $user ? $this->favoritedByUsers->contains($user->id) : false;


        return [
            'id' => $this->id,
            'user' => new PublicUserResource($this->creator), //募集を作成したUser  Todo:: userじゃなくてcreatorの方が良くね？あとで集中力のある時にフロントも合わせて変える
            'title' => $this->title,
            'description' => $this->description,
            'youtube_url' => $this->youtube_url,
            'reference_url' => $this->reference_url,
            'prefecture' => $this->prefecture,
            'prefecture_name' => optional($this->prefecture)->name, // 都道府県名を追加

            'distance' => $this->distance, // 追加　0920 21:30

            'age_from' => $this->age_from,
            'age_to' => $this->age_to,
            'min_people' => $this->min_people,
            'max_people' => $this->max_people,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'tags' => $this->tags,
            'images' => $this->images,
            'appliedUsers' => PublicUserResource::collection($this->appliedUsers),
            'approvedUsers' => PublicUserResource::collection($this->approvedUsers),
            // 'participants' => UserResource::collection($this->participants),
            'participants' => $this->participants,
            // 'comments' => $this->comments,
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_liked' => $isLikedByUser, // 追加: ユーザーが「いいね」しているかどうかの情報

        ];
    }
}
