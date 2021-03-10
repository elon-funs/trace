<?php
// +----------------------------------------------------------------------
// | 文件说明：用户-幻灯片
// +----------------------------------------------------------------------
// | Copyright (c) 2013-present http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com>
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Date: 2017-5-25
// +----------------------------------------------------------------------

namespace api\home\model;

use think\Model;

class ThemeFileModel extends Model
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'theme_file';

    //类型转换
    protected $type = [
        'more' => 'array',
    ];

}

