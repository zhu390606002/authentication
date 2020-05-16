<?php

namespace qimao\authentication\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use qimao\authentication\traits\Auth;
use qimao\authentication\traits\BaseController as Base;

class SiteController extends Controller
{
    use Auth;
    use Base;
    public $layout = false;
    public $noAuthUser = [
        1
    ];
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
//        if (!in_array($uid,$this->noAuthUser)){
//            $route = '/'. Yii::$app->request->pathInfo;
//            $code = $this->authenticate($route,$uid);
//            if ($code){
//                return $this->verifyReponse([], Yii::t('authentication/auth', 'authentication_fail_'.$code));
//            }
//        }
        return $this->render('index');
    }
}
