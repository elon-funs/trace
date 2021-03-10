<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-present http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\facade\Db;
use think\Model;

class UserModel extends Model
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'user';

    protected $type = [
        'more' => 'array',
    ];

    public static function UserRole($search)
    {

        return Db::table('cmf_role_user')
            ->alias('ru')
            ->join(['cmf_role' => 'r'], 'ru.role_id=r.id')
            ->where('ru.user_id', cmf_get_current_admin_id())
            ->where('r.' . $search[0], $search[1])
            ->value('r.id', 0);
    }
}
