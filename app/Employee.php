<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @className Employee
 * @namespace App
 * @description 物业员工实体类
 */
class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'job'
    ];
}
