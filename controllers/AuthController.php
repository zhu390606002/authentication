<?php


namespace qimao\authentication\controllers;

use Yii;
use qimao\authentication\logic\Auth as logicAuth;
class AuthController extends BaseController
{
    public function actionAuthList()
    {
        $params = Yii::$app->request->get();
        try {
            $logicAuth = new logicAuth($params);
            $data = $logicAuth->authList();
            return $this->ajaxResponse($data);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function actionAddAuth()
    {
        $params = Yii::$app->request->post();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['name'],'required','message'=>Yii::t('authentication/auth','name_lose')],
            [['url'],'required','message'=>Yii::t('authentication/auth','url_lose')],
            [['type'],'required','message'=>Yii::t('authentication/auth','type_lose')],
            [['parent_id'],'required','message'=>Yii::t('authentication/auth','parent_id_lose')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $logicAuth = new logicAuth($params,'create');
            $logicAuth->addAuth();
            $transaction->commit();
            return $this->ajaxResponse();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionDelAuth()
    {
        $params = Yii::$app->request->post();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['auth_id'],'required','message'=>Yii::t('authentication/auth','auth_id_lose')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $logicAuth = new logicAuth($params);
            $logicAuth->delAuth();
            $transaction->commit();
            return $this->ajaxResponse();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionUpdateAuth()
    {
        $params = Yii::$app->request->post();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['auth_id'],'required','message'=>Yii::t('authentication/auth','auth_id_lose')],
            [['name'],'string','message'=>Yii::t('authentication/auth','name_invalid')],
            [['url'],'string','message'=>Yii::t('authentication/auth','url_invalid')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }
        $params = array_filter($params,function ($value){return !is_null($value);});
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $logicAuth = new logicAuth($params,'update');
            $logicAuth->updateAuth();
            $transaction->commit();
            return $this->ajaxResponse();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
}