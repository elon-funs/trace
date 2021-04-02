<?php

namespace app\admin\model;

use think\Model;

class Work extends Model
{
    protected $name = 'work';

    public static $website = ['请选择', 'UU球', '狐狸', '雷速', '红杉'];

    public static $action = ['请选择', '发起人', '处理人'];

    public static $client_type = ['请选择', 'PC', 'H5', 'Android', 'IOS'];

    public static $order_type = ['请选择', '发起工单', '发布需求'];

    public static $order_status = ['待处理', '处理中', '已结单'];

    public static $work_order_type = ['请选择', 'BUG', '故障', '优化', '事故'];

    public static $order_step = [
        1 => '已提交',
        2 => '已规划方案',
        3 => '已规划版本待排期',
        4 => '预期上线',
        5 => '后续优化排期',
    ];

}
