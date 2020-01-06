<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/12/5
 * Time: 11:21
 */

namespace app\vendor\validate;


use Jasmine\library\validate\Validate;

class ProductValidator extends Validate
{
    protected $messages = [
        'category_id.require'                       => '分类ID不能为空',
        'category_id.int'                           => '分类ID必须是整数',
        'name.require'                              => '名称不能为空',
        'name.string'                               => '名称不能是数字',
        'attributes.require'                        => '属性传值必须',
        'attributes.array'                          => '属性传值应该是数组',
        'specifications.require'                    => '规格传值必须',
        'specifications.array'                      => '规格传值应该是数组',
        'sku_list.require'                          => 'SKU列表必须',
        'sku_list.array'                            => 'SKU列表应该是数组',
        'images.require'                            => '图片列表必须',
        'images.array'                              => '图片列表应该是数组',
        'detail.require'                            => '商品详情必须',
        'freight_template_id'                       => '运费模板ID必须',

        'sku.require'                               => 'SKU列表中的sku不能为空',
        'sku.array'                                 => 'SKU列表中的sku应该是列表',
        'price.require'                             => '价格不能为空',
        'price.number'                              => '价格必须是数字',
        'stock.require'                             => '库存不能为空',
        'stock.integer'                             => '库存必须是整数',
    ];

    protected $rules = [
        'category_id'           =>'require|int',
        'name'                  =>'require|string',
        'attributes'            =>'require|array',
        'specifications'        =>'require|array',
        'sku_list'              =>'require|array',
        'images'                =>'require|array',
        'detail'                =>'require',
        'freight_template_id'   =>'require|int',

        //
        'sku'                   =>'require|array',
        'price'                 =>'require|number',
        'stock'                 =>'require|integer'
    ];

    protected $scenes = [
        'add' => ['category_id','name','attributes','specifications','sku_list','images','detail','freight_template_id'],
        //用于校验单条sku数据
        'sku' => ['sku','price','stock']
    ];

}
