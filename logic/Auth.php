<?php


namespace app\logic;

use Yii;
use app\models\Auth as daoAuth;
use app\common\AuthenticationConstant;
use app\common\funcionts;
class Auth extends AbstractBaseLogic
{
    public function authList()
    {
        $page = daoAuth::getAllAuth(AuthenticationConstant::AUTH_TYPE_PAGE);
        $interface = daoAuth::getAllAuth(AuthenticationConstant::AUTH_TYPE_INTERFACE);
        $pageTree = $this->getTree($page,0);
        $interfaceTree = $this->getTree($interface,0);
        return ['page_tree'=>$pageTree,'interface_tree'=>$interfaceTree];
    }

    public function addAuth()
    {
        //校验上级权限
        if ($this->params['parent_id'] != 0){
            $parent = daoAuth::find()->where(['auth_id'=>$this->params['parent_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
            if (empty($parent)){
                throw new \Exception(Yii::t('authentication/auth','parent_auth_not_exist'));
            }
            if ($parent->type != $this->params['type']){
                throw new \Exception(Yii::t('authentication/auth','type_not_same'));
            }
        }
        //校验权限名称
        $same = daoAuth::find()->where(['name'=>$this->params['name'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        if (!empty($same)){
            throw new \Exception(Yii::t('authentication/auth','auth_name_exist'));
        }
        $auth = new daoAuth();
        $auth->scenario = $this->scenario;
        $auth->load($this->params,'');
        if (!$auth->save()){
            throw new \Exception($auth->errors);
        }
        //更新缓存
        $this->updateAuthCache();
    }

    public function delAuth()
    {
        //校验权限id
        $auth = daoAuth::find()->where(['auth_id'=>$this->params['auth_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        if (empty($auth)){
            throw new \Exception(Yii::t('authentication/auth','auth_not_exist'));
        }
        //获取子权限id
        $childId = $this->getChildAuthId($auth);
        $allId = array_merge($childId,[$auth->auth_id]);
        daoAuth::updateAuthById($allId);
        //更新缓存
        $this->updateAuthCache();
    }

    public function updateAuth()
    {
        $auth = daoAuth::find()->where(['auth_id'=>$this->params['auth_id'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->one();
        if (empty($auth)){
            throw new \Exception(Yii::t('authentication/auth','auth_not_exist'));
        }
        $auth->scenario = $this->scenario;
        if (!empty($this->params['name'])){
            $ano = daoAuth::find()->where(['name'=>$this->params['name'],'delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->andWhere(['not',['auth_id'=>$this->params['auth_id']]])->one();
            if (!empty($ano)){
                throw new \Exception(Yii::t('authentication/auth','auth_name_exist'));
            }
        }
        $auth->load($this->params,'');
        if (!$auth->save()){
            throw new \Exception($auth->errors);
        }
        //更新缓存
        $this->updateAuthCache();
    }

    public function getChildAuthId($auth)
    {
        $all = daoAuth::getAllAuth($auth->type);
        $tree = $this->getTree($all,$auth->auth_id);
        $child = [];
        if (!empty($tree)){
            $child = $this->getChildId($tree);
        }
        return $child;
    }

    public function calculateTree($auth)
    {
        $all = daoAuth::getAllAuth($auth->type);
        $tree = $this->getTree($all,$auth->auth_id);
        return $tree;
    }

    public function getTree($data,$pid)
    {
        $tree = [];
        foreach ($data as $k => $v){
            if ($v['parent_id'] == $pid){
                unset($data[$k]);
                $v['child'] = $this->getTree($data,$v['auth_id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    public function getChildId(array $tree)
    {
        static $level = [];
        foreach ($tree as $k => $v){
            $level[] = $v['auth_id'];
            if (!empty($v['child'])){
                $this->getChildId($v['child']);
            }
        }
        return $level;
    }

    public function getAncestors($data,$child)
    {
        $ancestors = [];
        $ancestors[] = $child;
        foreach ($data as $k=>$v){
            if ($v['auth_id'] == $child['parent_id']){
                unset($data[$k]);
                $re = $this->getAncestors($data,$v);
                $ancestors = array_merge($re,$ancestors);
            }
        }
        return $ancestors;
    }

    public function updateAuthCache()
    {
        $cache = $this->getAllCache();
        $this->setAuthCache($cache);
    }

    public function getAllCache()
    {
        $all = daoAuth::find()->where(['delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE])->asArray()->all();
        $url = [];
        $ancestors = [];
        foreach ($all as $k => $v){
            $url[$v['auth_id']] = $v['url'];
            $ancestors[$v['auth_id']] = $this->getAncestors($all,$v);
        }
        return ['url'=>$url,'ancestors'=>$ancestors];
    }

    public function setAuthCache($data)
    {
        $path = Yii::getAlias('@static').'/authentication';
        $name = 'auth';
        funcionts::saveFile($path,$name,serialize($data));
    }
}