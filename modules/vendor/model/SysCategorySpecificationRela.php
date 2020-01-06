<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/12/26
 * Time: 10:58
 */

namespace app\vendor\model;


use Jasmine\library\Model;

class SysCategorySpecificationRela extends Model
{
    function getSpecificationsWithCategoryId($categoryId){
        return $this->alias('csr')
            ->join('sys_specification s','csr.specification_id = s.id')
            ->where(['csr.category_id'=>$categoryId])
            ->distinct(true)
            ->select('s.*');
    }
}