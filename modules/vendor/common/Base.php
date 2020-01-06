<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2019/11/19
 * Time: 12:20
 */

namespace app\vendor\common;


use Jasmine\App;
use Jasmine\library\Controller;

class Base extends Controller
{


    function __construct(App $app = null)
    {
        parent::__construct($app);
    }
}