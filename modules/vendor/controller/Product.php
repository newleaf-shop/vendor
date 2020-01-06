<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/12/5
 * Time: 0:13
 */

namespace app\vendor\controller;


use app\vendor\common\VendorBase;
use app\vendor\model\SysCategorySpecificationRela;
use app\vendor\model\SysColor;
use app\vendor\model\SysProductAttribute;
use app\vendor\model\SysSpecification;
use app\vendor\model\VendorCategoryRela;
use app\vendor\validate\ProductValidator;
use Jasmine\library\db\query\capsule\Expression;
use Jasmine\library\file\File;
use Jasmine\library\http\Request;

class Product extends VendorBase
{

    public function index(Request $request)
    {

        print_r($request->input('data'));
    }

    public function add(Request $request)
    {

        try {
            $validator = new ProductValidator();

            $data = $request->input();
            //加规则
            $validator->addRule('category_id', 'available', function ($value) {
                $VendorCategoryRelaM = new VendorCategoryRela();
                return $VendorCategoryRelaM->check($this->vendor['id'], $value);
            }, '分类不可用');

            /**
             * 验证参数
             */
            if (!$validator->check('add', $data)) {
                throw new \Exception($validator->getError());
            }

            foreach ($data['sku_list'] as $specification) {
                if (!$validator->check('sku', $specification)) {
                    throw new \Exception($validator->getError());
                }
            }

            $productData = [];
            $productData['category_id'] = $data['category_id'];
            $productData['brand_id'] = $data['brand_id'];
            $productData['name'] = $data['name'];
            $productData['detail'] = $data['detail'];
            $productData['unit'] = $data['unit'];
            $productData['freight_template_id'] = $data['freight_template_id'];

            $productData['category_id'] = $data['category_id'];
            $productData['category_id'] = $data['category_id'];
            $productData['category_id'] = $data['category_id'];
            $productData['category_id'] = $data['category_id'];
            $productData['category_id'] = $data['category_id'];

            return $this->success('', $request->input());
        } catch (\Exception $exception) {

            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @param Request $request
     * @return array|false|string
     * @throws \ErrorException
     * itwri 2019/12/10 0:01
     */
    public function getPrepareInfo(Request $request)
    {
        $category_id = $request->get('category_id', 0);

        $VendorCategoryRelaM = new VendorCategoryRela();

        /**
         * 查询分类对应的完整链路
         */
        $list = $VendorCategoryRelaM->getFullPathCategory($this->vendor['id'], $category_id);
        if (empty($list)) {
            return $this->error('分类不存在');
        }

        $SysCategoryProductAttributeM = new SysProductAttribute();
        $attributes = $SysCategoryProductAttributeM->where(['category_id' => $category_id])->select(['id','name', "'' as value",'require']);

        $SysCategorySpecificationRelaM = new SysCategorySpecificationRela();
        $specifications = $SysCategorySpecificationRelaM->getSpecificationsWithCategoryId($category_id);
//        print_r($specifications);die();
        foreach ($specifications as &$specification) {
            switch ($specification['type']){
                case SysSpecification::TYPE_COLOR:
                    $ColorM = new SysColor();
                    $colors = $ColorM->where(['status'=>1])->select('id,name,color as rgb,parent_id');

                    $specification['x'] = 0;
                    $specification['y'] = 0;
                    $specification['hasBeenHover'] = false;
                    $specification['inputFocusIn'] = false;
                    $specification['curGroupIndex'] = 0;
                    $specification['curEditInputIndex'] = 0;
                    $specification['curElementEvent'] = null;
                    $specification['checkedItems'] = [['name'=>'','rgb'=>'','checked'=>false]];
                    $specification['options'] = toTree($colors);
                    break;
                case SysSpecification::TYPE_SIZE_FOR_CLOTHES:

                    $specification['x'] = 0;
                    $specification['y'] = 0;
                    $specification['curEditInputIndex'] = 0;
                    $specification['curElementEvent'] = null;
                    $specification['checkedItems'] = [['name'=>'','checked'=>false]];
                    $specification['options'] = [];
                    break;
                case SysSpecification::TYPE_SIZE_FOR_SHOES:
                    break;
                default:
                    $specification['curEditInputIndex'] = 0;
                    $specification['curElementEvent'] = null;
                    $specification['checkedItems'] = [['name'=>'','checked'=>false]];
                    $specification['options'] = [];
            }
        }

        /**
         * 返回相应的数据结构
         */
        $product = [
            'category' => [
                'names' => implode('>', array_column($list, 'name')),
                'paths' => $list,
                'last' => end($list),
            ],
            'attributes' => $attributes,
            'specifications' => $specifications,
        ];

        return $this->success('加载成功', ['product'=>$product]);
    }
}