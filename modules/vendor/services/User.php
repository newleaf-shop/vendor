<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2020/1/19
 * Time: 14:59
 */

namespace app\vendor\services;

use Jasmine\App;
use Jasmine\helper\Config;
use Jasmine\library\http\Curl;
use Jasmine\library\http\Url;

class User
{

    public static function isLogin($user_token){

        $url  = (new Url())->set(Config::get('services.api.user'))->toString().\url('user/index/isLogin',[],'');

        /**
         * 调用
         */
        $response = Curl::get($url,[], ['User-Token' => $user_token,'Ip'=>App::init()->getRequest()->ip()]);

        $response = json_decode($response, 1);

        if($response && isset($response['code']) && $response['code'] == 200){
            return $response['data'];
        }

        return false;
    }
}