<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @className Workorder
 * @namespace App
 * @description 工单实体类
 */
class Workorder extends Model
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
