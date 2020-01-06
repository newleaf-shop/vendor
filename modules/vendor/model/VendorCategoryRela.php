<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/30
 * Time: 13:27
 */

namespace app\vendor\model;

use Jasmine\library\db\query\capsule\Expression;
use Jasmine\library\Model;

class VendorCategoryRela extends Model
{

    /**
     * @param $vendor_id
     * @return array
     * itwri 2019/11/30 14:05
     */
    public function getCategoryListByVendorId($vendor_id){
        return $this->alias('vc')
            ->distinct(true)
            ->fields('c.id,c.parent_id,c.name')
            ->join('sys_category c','c.id = vc.category_id')
            ->where(['vc.vendor_id'=>$vendor_id])
            ->select();
    }

    /**
     * @param $vendor_id
     * @param $category_id
     * @return bool
     * itwri 2019/12/5 14:31
     */
    public function check($vendor_id,$category_id){
        return $this->alias('vc')
            ->leftJoin('sys_category c','c.id = vc.category_id')
            ->where(['vc.vendor_id'=>$vendor_id,'c.id'=>$category_id,'c.status'=>1])
            ->count() > 0;
    }

    /**
     * @param $vendor_id
     * @param $category_id
     * @return bool|mixed
     * itwri 2019/12/7 11:46
     */
    public function getFullPathCategory($vendor_id,$category_id){
        return $this
//            ->debug(1)
            ->alias('vc')
            ->fields('c.id,c.parent_id,c.name')
            ->join('sys_category c','c.id = vc.category_id')
            ->where(['vc.vendor_id'=>$vendor_id])
            ->where("AND (FIND_IN_SET(c.id,(select parent_ids FROM sys_category WHERE id = {$category_id})) > 0 or c.id = {$category_id})")
            ->where("AND FIND_IN_SET(c.parent_id,(select parent_ids FROM sys_category WHERE id = {$category_id})) > 0")
            ->orderBy(new Expression("FIND_IN_SET(c.id,(select parent_ids FROM sys_category WHERE id = {$category_id})) desc"))
            ->select();
    }
}