<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    const TYPE_ALIYUN = 1;
    const TYPE_YOUKU = 2;

    const LEVEL_ALWAYS = 1;
    const LEVEL_ONE_MONTH = 2;
    const LEVEL_THREE_MONTH = 3;
    const LEVEL_ONE_YEAR = 4;
    const LEVEL_NEVER = 5;

    protected $table='video';

    protected $appends = ['levelName','currentTypeName'];

    protected function getCurrentTypeNameAttribute() {
        return $this->current_type == self::TYPE_ALIYUN ? '阿里云':'优酷';
    }

    protected function  getLevelNameAttribute(){
        switch ($this->level){
            case self::LEVEL_ALWAYS:
                $levelName = '一直使用阿里云';
                break;
            case self::LEVEL_ONE_MONTH:
                $levelName = '一个月内使用阿里云';
                break;
            case self::LEVEL_THREE_MONTH:
                $levelName = '三个月内使用阿里云';
                break;
            case self::LEVEL_ONE_YEAR:
                $levelName = '一年内使用阿里云';
                break;
            case self::LEVEL_NEVER:
                $levelName = '永远不使用阿里云';
                break;
            default:
                break;
        }
        return $levelName;
    }

}
