<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message_text' => $this->message_text,
            'chat_group_name' => $this->whenLoaded('chat_group', fn () => $this->chat_group->name), // この行を追加
            'user' => PublicUserResource::make($this->whenLoaded('user')),
            'ts' => $this->created_at->getTimestampMs(),
        ];
    }
}
