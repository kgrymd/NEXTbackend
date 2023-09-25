<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;



    protected $fillable = [
        'user_id',
        'recruitment_id',
        'is_approved',
        'joined_at',
    ];

    // 参加申請をしたUser
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 参加申請されているRecruitment
    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class);
    }

    protected $dates = ['joined_at'];
}
