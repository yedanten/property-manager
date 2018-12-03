<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @className Bill
 * @namespace App
 * @description 缴费实体类
 */
class Bill extends Model
{
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
