<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/29
 * Time: 14:13
 */

namespace app\vendor\controller;


use app\vendor\common\VendorBase;
use app\vendor\model\VendorColumn;
use app\vendor\validate\ColumnValidator;
use Jasmine\App;
use Jasmine\library\http\Request;

class Column extends VendorBase
{

    public function __construct(App $app = null)
    {
        parent::__construct($app);
    }

    /**
     * @param Request $request
     * @return array|false|string
     * itwri 2019/12/4 22:48
     */
    public function index(Request $request){
        $VendorColumnM = new VendorColumn();

        $list = $VendorColumnM->where(['vendor_id'=>$this->vendor['id']])->select();


        /**
         * 数据转成树形
         */
        $result['columns'] = toTree($list,0);

        return $this->success('',$result);
    }

    /**
     * 新增栏目
     * @param Request $request
     * @return array|false|string
     * itwri 2019/12/4 23:06
     */
    public function add(Request $request){

        try{
            $validator = new ColumnValidator();

            /**
             * 检查参数
             */
            if(!$validator->check('add',$request->input())){
                throw new \Exception($validator->getError());
            }

            $name = $request->input('name','');
            $parent_id = $request->input('parent_id',0);


            /**
             * 数据模型实例化
             */
            $VendorColumnM = new VendorColumn();


            /**
             * 插入数据库
             */
            $res = $VendorColumnM->insert(['name'=>$name,'parent_id'=>$parent_id,'vendor_id'=>$this->vendor['id'],'create_time'=>date('Y-m-d H:i:s')]);
            if(!$res){
                throw new \Exception('新增失败');
            }
            return $this->success('新增成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(),$exception->getCode());
        }

    }
}