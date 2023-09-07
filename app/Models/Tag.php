<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // タイムスタンプを使わない場合↓の設定が必要らしい。
    // public $timestamps = false;

    use HasFactory;

    // 実際のテーブルが、クラス名の複数形＋スネークケースであれば、書かなくてもOK
    protected $table = 'tags';

    // Eloquentを通して更新や登録が可能なフィールド（ホワイトリストを定義）
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
