<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @className Apartment
 * @namespace App
 * @description 楼宇实体类
 */
class Apartment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'unit', 'doorplate'
    ];

    /**
     * @description 定义反向关联至User模型
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
