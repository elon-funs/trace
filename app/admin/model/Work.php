<?php

namespace app\admin\model;

use think\Model;

class Work extends Model
{
    protected $name = 'work';

    public static $website = ['请选择', '虎扑', '狐狸', 'U球', 'UU球', '红杉'];

    public static $action = ['请选择', '发起人', '处理人'];

    public static $client_type = ['请选择', 'PC', 'H5', 'Android', 'IOS'];

    public static $order_type = ['请选择', '发起工单', '发布需求'];

    public static $order_status = ['待处理', '处理中', '已结单'];

}
