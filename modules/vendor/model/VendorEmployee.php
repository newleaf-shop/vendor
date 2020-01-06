<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/19
 * Time: 14:41
 */

namespace app\vendor\model;


use Jasmine\library\Model;

class VendorEmployee extends Model
{
    /**
     * 检查是否是员工
     * @param $vendor_id
     * @param $user_id
     * @return bool|mixed
     * itwri 2019/11/19 14:50
     */
    public function check($vendor_id,$user_id){
        return $this->where(['vendor_id'=>$vendor_id,'user_id'=>$user_id,'status'=>1])->find();
    }
}