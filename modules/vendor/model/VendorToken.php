<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/19
 * Time: 14:59
 */

namespace app\vendor\model;


use Jasmine\library\Model;

class VendorToken extends Model
{

    /**
     * 校验
     * @param $token
     * @param $vendor_id
     * @param $user_id
     * @param $ip
     * @return bool|mixed
     * itwri 2019/11/28 13:10
     */
    function check($token,$ip)
    {
        /**
         * 查询对应的token
         */
        return $this->alias('vt')
            ->fields('v.id,v.name,v.create_time,v.status,vt.login_ip')
            ->join('vendor v', 'v.id = vt.vendor_id')
            ->where('vt.expire_time', '>', date('Y-m-d H:i:s'))
            ->where(['vt.token' => $token])
            ->where('login_ip', '=', $ip)
            ->find();
    }

    function checkUser($token,$vendor_id,$user_id, $ip)
    {
        /**
         * 查询对应的token
         */
        return $this->alias('vt')
            ->fields('v.id,v.name,v.create_time,v.status,vt.login_ip')
            ->join('vendor u', 'v.id = vt.vendor_id')
            ->where('vt.expire_time', '>', date('Y-m-d H:i:s'))
            ->where(['vt.token' => $token])
            ->where('login_ip', '=', $ip)
            ->where('vendor_id', '=', $vendor_id)
            ->where('user_id', '=', $user_id)
            ->find();
    }

    /**
     * @param $token
     * @param $vendor_id
     * @param $user_id
     * @param $user_token
     * @param $login_ip
     * @param null|mixed $expire_time
     * @return int
     * itwri 2019/11/28 13:02
     */
    function save($token, $vendor_id, $user_id, $user_token, $login_ip,$expire_time = null)
    {
        $expire_time = is_null($expire_time) ? date('Y-m-d H:i:s', strtotime('+1 hours')) : $expire_time;
        $where = ['vendor_id' => $vendor_id, 'user_id' => $user_id];
        if ($this->where($where)->count() > 0) {
            return $this
                ->where($where)
                ->update(['token' => $token,'user_token' => $user_token, 'login_ip' => $login_ip,  'expire_time' => $expire_time, 'update_time' => date('Y-m-d H:i:s')]);
        }
        return $this->insert(['token' => $token, 'vendor_id' => $vendor_id, 'user_id' => $user_id, 'user_token' => $user_token, 'login_ip' => $login_ip, 'expire_time' => $expire_time, 'update_time' => date('Y-m-d H:i:s'), 'create_time' => date('Y-m-d H:i:s')]);
    }
}