<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    
    // 参照させたいSQLのテーブル名を指定してあげる
    //protected $table = 'taskstable';
    
    protected $fillable = ['content','status'];
    

    /**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
