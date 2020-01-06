<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/19
 * Time: 14:32
 */

namespace app\vendor\model;


use Jasmine\library\Model;

class Vendor extends Model
{
    /**
     * @param $vendor_id
     * @param $user_id
     * @return bool|mixed
     * itwri 2019/11/19 14:36
     */
    public function check($vendor_id,$user_id){
        return $this->where(['id'=>$vendor_id,'user_id'=>$user_id,'status'=>1])->find();
    }
}