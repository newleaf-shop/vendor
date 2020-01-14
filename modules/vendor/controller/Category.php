<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/30
 * Time: 13:25
 */

namespace app\vendor\controller;


use app\vendor\common\VendorBase;
use app\vendor\model\VendorCategoryRela;
use Jasmine\library\http\Request;
use Jasmine\util\Arr;

class Category extends VendorBase
{

    /**
     * 商家可经营的分类
     * @param Request $request
     * @return array|false|string
     * itwri 2019/11/30 13:26
     */
    public function index(Request $request)
    {
        return $this->getFlatList($request);
    }

    /**
     * 扁平的
     * @param Request $request
     * @return array|false|string
     * itwri 2019/12/16 22:49
     */
    public function getFlatList(Request $request){
        /**
         * 实例
         */
        $VendorCategoryRelaM = new VendorCategoryRela();

        /**
         * 获取所有数据
         */
        $list = $VendorCategoryRelaM->getCategoryListByVendorId($this->vendor['id']);

        /**
         * 构扁平数据
         * 全链路
         */
        $this->buildCategory($list,$categories);

        /**
         *
         */
        return $this->success('获取成功', $categories);
    }

    /**
     * @param Request $request
     * @return array|false|string
     * itwri 2019/12/16 22:52
     */
    public function getChildren(Request $request){

        $parent_id = $request->get('parent_id',0);
        /**
         * 实例
         */
        $VendorCategoryRelaM = new VendorCategoryRela();

        /**
         * 从数据库中获取数据
         */
        $list = $VendorCategoryRelaM->getCategoryListByVendorId($this->vendor['id']);

        /**
         * 查找子项
         */
        $result = Arr::findChildren($list,$parent_id);

        foreach ($result['children'] as &$child) {

            /**
             * 生成上游路径
             */
            $paths = array_reverse($this->getPaths($child,$result['remain']));

            /**
             * 组成数据
             */
            $child['id'] = (int)$child['id'];
            $child['parent_id'] = (int)$child['parent_id'];
            $child['is_leaf'] = false;
            $child['names'] = array_column($paths,'name');
            $child['paths'] = $paths;
            $child['full_name'] = implode('>',$child['names']);

            /**
             * 判断是否还有子节点
             */
            $res = Arr::findChildren($result['remain'],$child['id']);
            if(empty($res['children'])){
                $child['is_leaf'] = true;
            }
        }
        return $this->success('获取成功',$result['children']);
    }

    /**
     * @param $item
     * @param $remainData
     * @param array $names
     * @return array
     * itwri 2019/12/17 18:14
     */
    protected function getPaths($item,$remainData){
        $result = [$item];
        $parent = $this->findItem($item['parent_id'],$remainData);
        if($parent){
            $list = $this->getPaths($parent,$remainData);
            foreach ($list as $value){
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $id
     * @param $data
     * @param string $field
     * @return mixed|null
     * itwri 2020/1/7 17:19
     */
    protected function findItem($id,$data,$field = 'id'){
        if(is_array($data)){
            foreach ($data as $datum) {
                if(isset($datum[$field]) && $datum[$field] == $id){
                    return $datum;
                }
            }
        }
        return null;
    }

    /**
     * @param $list
     * @param array $result
     * @param int $parent_id
     * @param array $names
     * itwri 2019/12/16 22:48
     */
    protected function buildCategory($list, &$result = [], $parent_id = 0, $names = [])
    {
        /**
         * 找到子项
         */
        $res = Arr::findChildren($list, $parent_id);
        if (!empty($res['children'])) {
            //如果存在子项
            foreach ($res['children'] as $child) {
                //复制使用
                $paths = $names;
                //追加名称
                array_push($paths, $child['name']);

                /**
                 * 继续查找下一级
                 */
                $res1 = Arr::findChildren($res['remain'], $child['id']);
                if (empty($res1['children'])) {
                    unset($child['parent_id']);

                    $child['id'] = intval($child['id']);
                    $child['path'] = $paths;
                    $child['full_name'] = implode('>', $paths);
                    $result[] = $child;
                } else {
                    $this->buildCategory($res['remain'], $result, $child['id'], $paths);
                }
            }
        }
    }
}