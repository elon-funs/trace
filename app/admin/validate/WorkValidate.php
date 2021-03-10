<?php

namespace app\admin\validate;

use think\Validate;

class WorkValidate extends Validate
{
    protected $rule = [
        'order_type'  => 'require',
        'description' => 'require',
    ];

    protected $message = [
        'order_type.require'  => '名称不能为空',
        'description.require' => '链接地址不能为空',
    ];
}
