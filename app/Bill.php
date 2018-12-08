<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @className Bill
 * @namespace App
 * @description 缴费实体类
 */
class Bill extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'pay_type', 'cost'
    ];

    /**
     * 需要转换成日期的属性
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at','deleted_at'];

    /**
     * 定义cost访问器
     * @param  int
     * @return float
     */
    public function getCostAttribute($value)
    {
        return $value/100;
    }

    /**
     * 定义cost修改器
     * @param  int
     * @return void
     */
    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = $value * 100;
    }
    
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
