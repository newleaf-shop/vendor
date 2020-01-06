<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/19
 * Time: 12:19
 */

namespace app\vendor\controller;

use app\vendor\common\VendorBase;
use app\vendor\model\Vendor;
use app\vendor\model\VendorEmployee;
use app\vendor\model\VendorLog;
use app\vendor\model\VendorToken;
use Jasmine\App;
use Jasmine\helper\Config;
use Jasmine\library\http\Curl;
use Jasmine\library\http\Request;
use Jasmine\library\http\Url;

class Index extends VendorBase
{
    function __construct(App $app = null)
    {
        $this->addEscapeRoute("Index@authLogin");
        parent::__construct($app);
    }

    /**
     * itwri 2019/11/27 15:55
     */
    function index()
    {
        $data = [];
        return $this->success('', $data);
    }

    /**
     * @param Request $request
     * @return array|false|string
     * itwri 2019/11/26 21:38
     */
    public function authLogin(Request $request)
    {

        try {

            $user_token = $request->header('User-Token', '');
            $vendor_id = $request->get('vendor_id', 0, 'intval');

            $url = (new Url())->set(Config::get('user.api_config'))->set('path', "/user/index/isLogin")->toString();
            /**
             * 调用
             */
            $response = Curl::get($url,[], ['User-Token' => $user_token]);

            $response = json_decode($response, 1);
            if (!$response || !isset($response['code']) || $response['code'] != 200) {
                return $this->error('请登录',-1,$response);
            }

            /**
             * 返回正常时
             */
            $user = $response['data'];

            $remark = "主账号登录";
            /**
             * 是否是拥有者
             */
            $VendorM = new Vendor();
            $vendor = $VendorM->check($vendor_id, $user['id']);
            if ($vendor == false) {
                /**
                 * 检查是否是员工
                 */
                $VendorEmployeeM = new VendorEmployee();
                $employee = $VendorEmployeeM->check($vendor_id, $user['id']);
                if ($employee == false) {
                    return $this->error('您没有权限');
                }

                $remark = "子账号授权登录";
            }


            $VendorTokenM = new VendorToken();

            /**
             * 商家授权登录
             */
            $authToken = $this->getAuthToken($vendor_id, $user['id']);

            /**
             * 是否已登录，避免被刷登录
             */
            if ($info = $VendorTokenM->checkUser($user_token, $vendor['id'], $user['id'], $request->ip()) !== false) {
                $VendorTokenM->setInc('login_count', 1);
                return $this->success('已经登录', ['ip' => $this->request()->ip(), 'vendor_token' => $authToken]);
            }

            $VendorTokenM->save($authToken,$vendor['id'],$user['id'],$user_token,$request->ip(),date('Y-m-d H:i:s',strtotime('+1 day')));

            /*
             * 记录登录动作
             */
            VendorLog::record($user['id'], $vendor['id'], __METHOD__, __FUNCTION__, $this->request()->ip(), $remark, json_encode(['user' => $user, 'vendor' => $vendor]));

        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }

        return $this->success('授权成功', ['ip' => $this->request()->ip(), 'vendor_token' => $authToken]);
    }
}