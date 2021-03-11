<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-present http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\UserModel;
use app\admin\model\Work;
use app\admin\model\WorkTimeLine;
use cmf\controller\AdminBaseController;
use think\facade\Db;

class WorkController extends AdminBaseController
{

    public function index()
    {
        $content = hook_one('admin_work_index_view');

        if (!empty($content)) {
            return $content;
        }
        $works = Work::order('id desc')->paginate();
        $this->assign('order_status', Work::$order_status);
        $this->assign('works', $works);
        $this->assign('page', $works->render());

        return $this->fetch();
    }

    public function add()
    {
        $role = UserModel::UserRole(['name', '发起人']);
        if (intval($role) < 1) {
            $this->error('您不是工单发起人');
        }
        $this->assign('website', Work::$website);
        $this->assign('client_type', Work::$client_type);
        return $this->fetch();
    }

    public function add2()
    {
        $role = UserModel::UserRole(['name', '发起人']);
        if (intval($role) < 1) {
            $this->error('您不是工单发起人');
        }
        return $this->fetch();
    }

    public function addPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data, 'Work');
            if ($result !== true) {
                $this->error($result);
            }
            $workdata = [
                'creator_id'   => cmf_get_current_admin_id(),
                'creator_name' => UserModel::where('id', cmf_get_current_admin_id())->value('user_login'),
                'order_status' => 0,
                'operator'     => 0,
            ];

            //1发工单
            $keys = ['website', 'url', 'client_type', 'pixel', 'browser', 'client_place', 'order_type', 'screen', 'description'];

            //2发需求
            if ($data['order_type'] == 2) {
                $keys = ['title', 'order_type', 'screen', 'description'];
            }
            $workdata = array_merge(arr_from_keys($data, $keys), $workdata);
            $wtldata  = [
                'user_id'   => $workdata['creator_id'],
                'user_name' => $workdata['creator_name'],
                'remark'    => '创建此订单',
            ];

            Db::startTrans();
            $Work         = new Work();
            $worktimeline = new WorkTimeLine();
            if (!$Work->save($workdata)) {
                Db::rollback();
                $this->error("操作失败");
            }
            $wtldata['work_id'] = $Work->id;
            if ($worktimeline->save($wtldata)) {
                Db::commit();
                $this->success("添加成功！", url('Work/index'));
            }
            Db::rollback();
            $this->error("操作失败！");
        }
    }

    public function edit()
    {
        $id       = $this->request->param('id', 0, 'intval');
        $work     = new work();
        $work     = $work->find($id);
        $workflow = WorkTimeLine::where('work_id', $id)->field('remark,created_at,user_name,work_id,pics,id')->select();

        $this->assign('work', $work);
        $this->assign('client_type', Work::$client_type);
        $this->assign('website', Work::$website);
        $this->assign('workflow', $workflow);
        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
            $role = UserModel::UserRole(['name', '处理人']);
            if (intval($role) < 1) {
                $this->error('您不是工单处理人');
            }
            $data = $this->request->param();
            $work = work::find($data['id']);

            $workdata = [
                'operator_id'   => cmf_get_current_admin_id(),
                'operator_name' => UserModel::where('id', cmf_get_current_admin_id())->value('user_login'),
                'order_status'  => $data['order_status'],
            ];
            if ($work['order_status'] == '0') {
                $workdata['start_time'] = date('Y-m-d H:i:s');
            } elseif ($data['order_status'] == 2) {
                $workdata['close_time'] = date('Y-m-d H:i:s');
            }

            if ($work['creator_id'] !== cmf_get_current_admin_id()) {
                unset($data['title']);
                unset($work['description']);
                unset($data['screen']);
            }

            $wtldata['work_id']   = $work->id;
            $wtldata['remark']    = $data['remark'];
            $wtldata['user_id']   = $workdata['operator_id'];
            $wtldata['user_name'] = $workdata['operator_name'];

            WorkTimeLine::Insert($wtldata);

            $work->save(array_merge($data, $workdata));
            $this->success("保存成功！", url("work/index"));
        }
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (Work::where('creator_id', cmf_get_current_admin_id())->value('id', 0) != $id) {
            $this->error("删除失败,只有创建人可以删除！");
        }
        Work::destroy($id);
        $this->success("删除成功！", url("work/index"));
    }

    public function waiting()
    {
        $content = hook_one('admin_work_index_view');

        if (!empty($content)) {
            return $content;
        }
        $works = Work::where('order_status', '<', '2')->order('id desc')->paginate();
        $this->assign('order_status', Work::$order_status);
        $this->assign('works', $works);
        $this->assign('page', $works->render());

        return $this->fetch('index');
    }

    public function done()
    {
        $content = hook_one('admin_work_index_view');

        if (!empty($content)) {
            return $content;
        }
        $works = Work::where('order_status', '2')->order('id desc')->paginate();
        $this->assign('order_status', Work::$order_status);
        $this->assign('works', $works);
        $this->assign('page', $works->render());

        return $this->fetch('index');
    }

    public function my()
    {
        $content = hook_one('admin_work_index_view');

        if (!empty($content)) {
            return $content;
        }
        $works = Work::where('creator_id', cmf_get_current_admin_id())->whereOr('operator_id', cmf_get_current_admin_id())->order('id desc')->paginate();
        $this->assign('order_status', Work::$order_status);
        $this->assign('works', $works);
        $this->assign('page', $works->render());

        return $this->fetch('index');
    }
}
