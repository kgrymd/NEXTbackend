<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'age' => $this->age,
            'prefecture_id' => $this->prefecture_id,
            'prefecture_name' => optional($this->prefecture)->name, // 都道府県名を追加
            'introduction' => $this->introduction,
            'icon_path' => $this->icon_path,
            'tags' => TagResource::collection($this->whenLoaded('tags')),

        ];
    }
}
