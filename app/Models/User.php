<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'icon_path',
        'introduction',
        'age',
        'uncharted_challenge',
        'prefecture_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function prefecture()
    {
        // return $this->belongsTo(Prefecture::class, 'prefecture_id', 'id'); // Laravelの規約に沿った名前付けをしているで余計な引数の指定をせずに済む。
        return $this->belongsTo(Prefecture::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }


    // Userが作成した募集
    public function createdRecruitments()
    {
        return $this->hasMany(Recruitment::class, 'user_id');
    }

    // Userが申請した募集（承認前含む）
    public function appliedRecruitments()
    {
        return $this->belongsToMany(Recruitment::class, 'participants');
    }

    // 承認された募集（正式に参加）
    public function approvedRecruitments()
    {
        return $this->belongsToMany(Recruitment::class, 'participants')->wherePivot('is_approved', true);
    }

    // Userの参加詳細（Participantテーブルに直接アクセスする場合）
    public function participations()
    {
        return $this->hasMany(Participant::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function chat_groups()
    {
        return $this->belongsToMany(ChatGroup::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function favoritedRecruitments()
    {
        return $this->belongsToMany(Recruitment::class, 'recruitment_user')
            ->withTimestamps(); // タイムスタンプを同期する場合
    }
}
