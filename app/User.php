<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @className User
 * @namespace App
 * @description 用户实体类
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'role_id', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * @description 定义反向关联至Role模型
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * @description 定义一对多关联至Apartment模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apartment()
    {
        return $this->hasMany('App\Apartment');
    }

    /**
     * @description 定义一对多关联至Bill模型
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bill()
    {
        return $this->hasMany('App\Bill');
    }

    /**
     * @description 定义一对多关联至Workorder模型
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workorder()
    {
        return $this->hasMany('App\Workorder');
    }
}
