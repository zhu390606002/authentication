<?php


namespace qimao\controllers;

use Yii;
use qimao\logic\AuthRole as logicAuthRole;
class AuthRoleController extends BaseController
{
    public function actionAuthRoleList()
    {
        $params = Yii::$app->request->get();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['name'],'string','message'=>Yii::t('authentication/authRole','name_invalid')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }
        try {
            $logicAuthRole = new logicAuthRole($params);
            $data = $logicAuthRole->authRoleList();
            return $this->ajaxResponse($data);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function actionAddAuthRole()
    {
        $params = Yii::$app->request->post();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['name'],'string','message'=>Yii::t('authentication/authRole','name_lose')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $logicAuthRole = new logicAuthRole($params,'create');
            $logicAuthRole->addAuthRole();
            $transaction->commit();
            return $this->ajaxResponse();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionDelAuthRole()
    {
        $params = Yii::$app->request->post();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['role_id'],'required','message'=>Yii::t('authentication/authRole','role_id_lose')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $logicAuthRole = new logicAuthRole($params);
            $logicAuthRole->delAuthRole();
            $transaction->commit();
            return $this->ajaxResponse();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionUpdateAuthRole()
    {
        $params = Yii::$app->request->post();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['role_id'],'required','message'=>Yii::t('authentication/authRole','role_id_lose')],
            [['name'],'string','message'=>Yii::t('authentication/authRole','name_invalid')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }
        $params = array_filter($params,function ($value){return !is_null($value);});
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $logicAuthRole = new logicAuthRole($params,'update');
            $logicAuthRole->updateAuthRole();
            $transaction->commit();
            return $this->ajaxResponse();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionUserAuthRole()
    {
        $params = Yii::$app->request->post();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['user_id'],'required','message'=>Yii::t('authentication/authRole','user_id_lose')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $logicAuthRole = new logicAuthRole($params);
            $logicAuthRole->userAuthRole();
            $transaction->commit();
            return $this->ajaxResponse();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionAuthRoleBindAuth()
    {
        $params = Yii::$app->request->post();
        $validator = $this->getInputValidate();
        $res = $validator->customValidate($params,[
            [['role_id'],'required','message'=>Yii::t('authentication/authRole','role_id_lose')],
        ]);
        if (!$res){
            return $this->error($validator->errors);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $logicAuthRole = new logicAuthRole($params);
            $logicAuthRole->authRoleBindAuth();
            $transaction->commit();
            return $this->ajaxResponse();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
}