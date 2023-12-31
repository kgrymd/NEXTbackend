<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) //: array
    {
        return [
            'id' => $this->id,
            'comment_text' => $this->comment_text,
            'user' => PublicUserResource::make($this->user),
            'ts' => $this->created_at->getTimestampMs(),
        ];
    }
}
