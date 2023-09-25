<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['recruitment_id', 'user_id', 'comment_text'];

    // ミリ秒対応
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class);
    }
}
