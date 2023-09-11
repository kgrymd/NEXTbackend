<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array //←いるんかいらんのか後で調べる
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->creator), //募集を作成したUser
            'title' => $this->title,
            'description' => $this->description,
            'youtube_url' => $this->youtube_url,
            'reference_url' => $this->reference_url,
            'prefecture' => $this->prefecture,
            'prefecture_name' => optional($this->prefecture)->name, // 都道府県名を追加
            'age_from' => $this->age_from,
            'age_to' => $this->age_to,
            'min_people' => $this->min_people,
            'max_people' => $this->max_people,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'tags' => $this->tags,
            'images' => $this->images,
            'appliedUsers' => UserResource::collection($this->appliedUsers),
            'approvedUsers' => UserResource::collection($this->approvedUsers),
            // 'participants' => UserResource::collection($this->participants),
            'participants' => $this->participants,
            // 'comments' => $this->comments,
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
