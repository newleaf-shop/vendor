<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/12/5
 * Time: 12:20
 */

namespace app\vendor\validate;


use Jasmine\library\validate\Validate;

class ProductSkuValidator extends Validate
{
    protected $messages = [
        'price.require'                        => '缺少价格',
        'price.number'                         => '价格必须是数字',
        'sku_name.require'                             => '名称不能为空',
        'sku_name.string'                             => '名称应该是字符串',
        'stock.require'                        => '库存数',
        'stock.integer'                         => '价格必须是正整数',
    ];

    protected $rules = [
        'sku_name' => 'require|string',
        'price' => 'require|number',
        'stock' => 'require|integer',
    ];

    protected $scenes = [
        'add' => ['sku_name','price','stock'],
    ];

}