<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2020 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: https://thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/ThinkAdmin
// | github 代码仓库：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\admin\Controller;
use think\admin\service\ModuleService;

/**
 * 系统模块管理
 * Class Module
 * @package app\admin\controller
 */
class Module extends Controller
{
    /**
     * 系统模块管理
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '系统模块管理';
        $this->modules = ModuleService::instance()->change();
        $this->fetch();
    }

    /**
     * 安装模块代码
     * @auth true
     */
    public function install()
    {
        $data = $this->_vali(['name.require' => '模块名称不能为空！']);
        [$state, $message] = ModuleService::instance()->install($data['name']);
        $state ? $this->success($message) : $this->error($message);
    }

    /**
     * 查看模块更新
     * @auth true
     */
    public function change()
    {
        $data = $this->_vali(['name.require' => '模块名称不能为空！']);
        $modules = ModuleService::instance()->online();
        if (isset($modules[$data['name']])) {
            $this->module = $modules[$data['name']];
            foreach ($this->module['changes'] as $key => &$change) {
                $change['datetime'] = preg_replace("|^(\d{4})\.(\d{2})\.(\d{2}).*?$|", '$1年$2月$3日', $key);
            }
            $this->fetch();
        } else {
            $this->error('未查询到模块更新记录！');
        }
    }
}