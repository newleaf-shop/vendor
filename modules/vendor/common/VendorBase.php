<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/19
 * Time: 15:21
 */

namespace app\vendor\common;


use app\vendor\model\VendorToken;
use Jasmine\App;

class VendorBase extends Base
{

    protected $escapeRoutes = [];
    protected $vendor_token = '';
    protected $vendor = null;

    function __construct(App $app = null)
    {
        parent::__construct($app);

        $route = implode('@', array(ucfirst($this->request()->getController()), strtolower($this->request()->getAction())));

        // vendor_token
        $this->vendor_token = $this->request()->header('Vendor-Token', '');
        $ip = $this->request()->ip();


        //  如果不在非验证列表里，则需要验证登录
        if (!in_array($route, $this->escapeRoutes)) {

            $VendorTokenM = new VendorToken();

            //校验token
            if (($this->vendor = $VendorTokenM->check($this->vendor_token,$ip))==false) {
                echo $this->error("请登录");
                die();
            }
        }
    }

    /**
     * 添加非验证路由
     * Controller @ action
     * 可在继承实现的initialize()方法里使用
     * @param $route
     * @return $this
     */
    protected function addEscapeRoute($route)
    {
        if (is_array($route)) {
            foreach ($route as $item) {
                $this->addEscapeRoute($item);
            }
        } elseif (is_string($route)) {
            $arr = explode('@', $route);
            if (count($arr) > 1) {
                $this->escapeRoutes[] = implode('@', array(ucfirst($arr[0]), strtolower($arr[1])));
            }
        }
        return $this;
    }

    /**
     * 生成授权token
     * @param $vendor_id
     * @param $user_id
     * @return string
     * itwri 2019/11/19 15:22
     */
    protected function getAuthToken($vendor_id,$user_id){
        return md5(implode(':',[$user_id,$vendor_id,$this->request()->ip(),date('Y-m-d')]));
    }
}