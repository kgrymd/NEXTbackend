<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_group_id', 'user_id', 'message_text'];

    // ミリ秒対応
    protected $dateFormat = 'Y-m-d H:i:s.v';


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chat_group()
    {
        return $this->belongsTo(ChatGroup::class);
    }
}
