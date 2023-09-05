<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    // タイムスタンプを使わない場合↓の設定が必要らしい。
    public $timestamps = false;


    use HasFactory;
    // 実際のテーブルが、クラス名の複数形＋スネークケースであれば、書かなくてもOK
    protected $table = 'prefectures';

    // Eloquentを通して更新や登録が可能なフィールド（ホワイトリストを定義）
    protected $fillable = ['name', 'latitude', 'longitude'];

    public function users()
    {
        // return $this->hasMany(User::class, 'prefecture_id', 'id');// Laravelの規約に沿った名前付けをしているで余計な引数の指定をせずに済む。

        return $this->hasMany(User::class);
    }
}
