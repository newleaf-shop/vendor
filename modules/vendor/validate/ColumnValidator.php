<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/12/4
 * Time: 22:49
 */

namespace app\vendor\validate;


use Jasmine\library\validate\Validate;

class ColumnValidator extends Validate
{
    protected $messages = [
        'parent_id.require'                        => '缺少上级ID',
        'parent_id.number'                         => '上级ID必须是数字',
        'name.require'                             => '名称不能为空',
    ];

    protected $rules = [
        'parent_id' => 'require|number',
        'name' => 'require|string'
    ];

    protected $scenes = [
        'add' => ['parent_id','name']
    ];
}