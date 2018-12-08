<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @className Employee
 * @namespace App
 * @description 物业员工实体类
 */
class Employee extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'job'
    ];
    
    /**
     * 需要转换成日期的属性
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at','deleted_at'];
}
