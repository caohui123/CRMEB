<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\adminapi\controller\v1\diy;


use app\adminapi\controller\AuthController;
use app\services\diy\DiyServices;
use app\services\diy\PageCategoryServices;
use app\services\diy\PageLinkServices;
use app\services\product\product\StoreCategoryServices;
use think\facade\App;

/**
 * Class PageLink
 * @package app\controller\admin\v1\diy
 */
class PageLink extends AuthController
{

    /**
     * PageLink constructor.
     * @param App $app
     * @param PageLinkServices $services
     */
    public function __construct(App $app, PageLinkServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 获取页面链接分类
     * @return mixed
     */
    public function getCategory(PageCategoryServices $services)
    {
        return app('json')->success($services->getCategroyList());
    }

    /**
     * 获取页面链接
     * @param $cate_id
     * @return mixed
     */
    public function getLinks($cate_id, PageCategoryServices $pageCategoryServices)
    {
        if (!$cate_id) return app('json')->fail('缺少参数');
        $category = $pageCategoryServices->get((int)$cate_id);
        if (!$category) {
            return app('json')->fail('页面分类不存在');
        }
        switch ($category['type']) {
            case 'special':
                /** @var DiyServices $diyServices */
                $diyServices = app()->make(DiyServices::class);
                $data = $diyServices->getDiyList(['type' => [1, 2]]);
                break;
            case 'product_category':
                /** @var StoreCategoryServices $storeCategoryServices */
                $storeCategoryServices = app()->make(StoreCategoryServices::class);
                $data = $storeCategoryServices->getList(['cate_name' => '', 'pid' => '', 'is_show' => '']);
                break;
            default:
                $data = $this->services->getLinkList(['cate_id' => $cate_id]);
                break;
        }
        return app('json')->success($data);
    }

    /**
     * 保存链接
     * @param $cate_id
     * @param PageCategoryServices $pageCategoryServices
     * @return mixed
     */
    public function saveLink($cate_id, PageCategoryServices $pageCategoryServices)
    {
        $data = $this->request->getMore([
            ['name', ''],
            ['url', '']
        ]);
        if (!$cate_id || !$data['name'] || !$data['url']) return app('json')->fail('缺少参数');
        $category = $pageCategoryServices->get((int)$cate_id);
        if (!$category) {
            return app('json')->fail('页面分类不存在');
        }
        $data['cate_id'] = $cate_id;
        $data['add_time'] = time();
        if (!$this->services->save($data)) {
            return app('json')->fail('添加失败');
        }
        return app('json')->success('添加成功');
    }

    /**
     * 删除链接
     * @param $id
     * @return mixed
     */
    public function del($id)
    {
        if (!$id) return app('json')->fail('参数错误');
        $this->services->del($id);
        return app('json')->success('删除成功!');
    }

}
