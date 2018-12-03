<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @className Role
 * @namespace App
 * @description 角色实体类
 */
class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @description 定义一对多关联至User模型
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany('App\User');
    }
}
