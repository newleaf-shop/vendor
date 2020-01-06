<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/12/4
 * Time: 21:56
 */

/**
 * 将列表转成树形
 * @param array $list
 * @param int $parent_id
 * @param string $parent_key
 * @return array
 * itwri 2019/12/4 21:52
 */
function toTree(array $list, $parent_id = 0, $parent_key = 'parent_id'){
    $result = [];
    if(is_array($list)){
        $res1 = findChildren($list,$parent_id,$parent_key);

        if(!empty($res1['children'])){
            foreach ($res1['children'] as $child) {

                $children = toTree($res1['remain'], $child['id'],$parent_key);
                if(!empty($children)){
                    $child['children'] = $children;
                }

                $result[] = $child;
            }
        }

    }
    return $result;
}

/**
 * 找出子项 和 剩下项
 * @param $data
 * @param $parent_id
 * @param string $parent_key
 * @return array
 * itwri 2019/12/4 21:52
 */
function findChildren($data,$parent_id, $parent_key = 'parent_id'){
    $result = ['children'=>[],'remain'=>[]];
    if(is_array($data)){
        foreach ($data as $datum) {
            if(isset($datum[$parent_key]) && $datum[$parent_key] == $parent_id){
                $result['children'][] = $datum;
            }else{
                $result['remain'][] = $datum;
            }
        }
    }
    return $result;
}

/**
 * 生成密码
 * @param $pass
 * @return string
 * itwri 2019/12/16 20:29
 */
function password($pass)
{
    return md5(sha1($pass));
}