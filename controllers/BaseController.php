<?php


namespace app\controllers;

use app\traits\Auth;
use app\traits\BaseController as Base;
use yii\web\Controller;
use Yii;
class BaseController extends Controller
{
    use Auth;
    use Base;

    public $noAuthUser = [
        1
    ];

    public function beforeAction($action)
    {
//        if (parent::beforeAction($action)){
//            if (!in_array($uid,$this->noAuthUser)){
//                $route = '/'.$action->controller->id.'/'.$action->id;
//                $code = $this->authenticate($route,$uid);
//                if ($code){
//                    $this->verifyReponse([], Yii::t('authentication/auth', 'authentication_fail_'.$code));
//                    return false;
//                }
//            }
//            return true;
//        }else{
//            return false;
//        }
        return true;
    }

    public function getInputValidate()
    {
        return new \app\common\InputValidator();
    }
}