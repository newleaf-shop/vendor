<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/27
 * Time: 4:19
 */

namespace app\vendor\model;


use Jasmine\library\Model;

class VendorLog extends Model
{
    /**
     * @param $user_id
     * @param $vendor_id
     * @param $action
     * @param $event
     * @param $ip
     * @param $remark
     * @param string $content
     * @return int
     * itwri 2019/11/27 4:20
     */
    static function record($user_id,$vendor_id,$action,$event,$ip,$remark,$content = ''){
        return (new static())->insert(['user_id' => $user_id, 'vendor_id'=>$vendor_id, 'action' => $action, 'event' => $event, 'ip' => $ip, 'remark' => $remark,'content'=>$content,'create_time'=>date('Y-m-d H:i:s')]);
    }
}