<?php


namespace app\logic;

use Yii;
use app\models\AuthRole as daoAuthRole;
use app\models\RoleAuthRelation as daoRoleAuthRelation;
use app\models\UserAuthRole as daoUserAuthRole;
use app\models\Auth as daoAuth;
use app\common\AuthenticationConstant;
use app\common\funcionts;
class AuthRole extends AbstractBaseLogic
{
    const TABLE_HEADER_LIST = [];

    public function authRoleList()
    {
        $query = daoAuthRole::find()->where(['delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE]);
        $count = $query->count();

        if (empty($count)){
            return $this->formatList([],$count,self::TABLE_HEADER_LIST);
        }

        $query = $this->addPaginate($query,$count);
        $list = $query->asArray()->all();
        return $list;
    }

    public function addAuthRole()
    {
        //校验角色名称
        $same = daoAuthRole::find()->where(['name'=>$this->params['name'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        if (!empty($same)){
            throw new \Exception(Yii::t('authentication/authRole','auth_role_name_exist'));
        }
        $authRole = new daoAuthRole();
        $authRole->scenario = $this->scenario;
        $authRole->load($this->params,'');
        if (!$authRole->save()){
            throw new \Exception($authRole->errors);
        }
    }

    public function delAuthRole()
    {
        $authRole = daoAuthRole::find()->where(['role_id'=>$this->params['role_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        if (empty($authRole)){
            throw new \Exception(Yii::t('authentication/authRole','auth_role_not_exist'));
        }
        $roleAuthRelation = daoRoleAuthRelation::find()->where(['role_id'=>$this->params['role_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        $userAuthRole = daoUserAuthRole::find()->where(['role_id'=>$this->params['role_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        if (!empty($roleAuthRelation)){
            daoRoleAuthRelation::updateRoleAuthRelationByRoleId($this->params['role_id']);
        }
        if (!empty($userAuthRole)){
            daoUserAuthRole::updateUserAuthRoleByRoleId($this->params['role_id']);
        }
        $authRole->delete_flag = AuthenticationConstant::IS_DELETE_TRUE;
        if (!$authRole->save()){
            throw new \Exception($authRole->errors);
        }
    }

    public function updateAuthRole()
    {
        $authRole = daoAuthRole::find()->where(['role_id'=>$this->params['role_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        if (empty($authRole)){
            throw new \Exception(Yii::t('authentication/authRole','auth_role_not_exist'));
        }
        $authRole->scenario = $this->scenario;
        if (!empty($this->params['name'])){
            $ano = daoAuthRole::find()->where(['name'=>$this->params['name'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->andWhere(['not',['role_id'=>$this->params['role_id']]])->one();
            if (!empty($ano)){
                throw new \Exception(Yii::t('authentication/authRole','auth_role_name_exist'));
            }
        }
        $authRole->load($this->params,'');
        if (!$authRole->save()){
            throw new \Exception($authRole->errors);
        }
    }

    public function authRoleBindAuth()
    {
        $authRole = daoAuthRole::find()->where(['role_id'=>$this->params['role_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        if (empty($authRole)){
            throw new \Exception(Yii::t('authentication/authRole','auth_role_not_exist'));
        }
        //清除原绑定权限
        daoRoleAuthRelation::updateRoleAuthRelationByRoleId([$this->params['role_id']]);
        $auth = [];
        if (!empty($this->params['auth_id'])){
            $auth = daoAuth::find()->where(['auth_id'=>$this->params['auth_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->asArray()->all();
        }
        if (!empty($auth)){
            $insert = [];
            foreach ($auth as $v){
                $insert[] = [
                    'role_id'=>$this->params['role_id'],
                    'auth_id'=>$v['auth_id']
                ];
            }
            daoRoleAuthRelation::batchInsertByRoleAndAuth($insert);
        }
    }

    public function userAuthRole()
    {
        //清除用户原绑定角色
        daoUserAuthRole::updateUserAuthRoleByUserId([$this->params['user_id']]);
        $authRole = [];
        if (!empty($this->params['role_id'])){
            $authRole = daoAuthRole::find()->where(['role_id'=>$this->params['role_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->asArray()->all();
        }
        if (!empty($authRole)){
            $insert = [];
            foreach ($authRole as $v){
                $insert[] = [
                    'user_id'=>$this->params['user_id'],
                    'role_id'=>$v['role_id']
                ];
            }
            daoUserAuthRole::batchInsertByUserAndRole($insert);
        }
        $this->updateUserAuthCache($this->params['user_id']);
    }

    public function updateUserAuthCache($userId)
    {
        $data = daoUserAuthRole::getUserAuthByUserId($userId);
        $this->setUserAuthCache($data,$userId);
    }

    public function setUserAuthCache($data,$userId)
    {
        $path = Yii::getAlias('@static').'/authentication/user';
        funcionts::saveFile($path,$userId,serialize($data));
    }
}