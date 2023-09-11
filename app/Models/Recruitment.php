<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;
    // 実際のテーブルが、クラス名の複数形＋スネークケースであれば、書かなくてもOK
    protected $table = 'recruitments';

    // Eloquentを通して更新や登録が可能なフィールド（ホワイトリストを定義）
    protected $fillable = [
        'title',
        'user_id',
        'description',
        'youtube_url',
        'reference_url',
        'prefecture_id',
        'age_from',
        'age_to',
        'min_people',
        'max_people',
        'start_date',
        'end_date',
    ];

    // 募集を作成したUser
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 募集に申請したUsers（承認前含む）
    public function appliedUsers()
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    // 承認されたUsers（正式に参加）
    public function approvedUsers()
    {
        return $this->belongsToMany(User::class, 'participants')->wherePivot('is_approved', true);
    }

    // 募集の参加詳細
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function images()
    {
        return $this->hasMany(RecruitmentImage::class);
    }

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function tags()
    {
        // return $this->belongsToMany(Tag::class, 'recruitment_tag');
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
