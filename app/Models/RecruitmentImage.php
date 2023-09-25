<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_id',
        'image_path',
    ];

    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class);
    }
}
