<?php


namespace app\traits;

use Yii;
trait Auth
{
    public function getAuthCache()
    {
        $path = Yii::getAlias('@static').'/authentication';
        $name = $path.'/auth';
        if (is_file($name)){
            return unserialize(file_get_contents($name));
        }else{
            return [];
        }
    }

    public function getUserAuthCache($userId)
    {
        $path = Yii::getAlias('@static').'/authentication/user';
        $name = $path.'/'.$userId;
        if (is_file($name)){
            return unserialize(file_get_contents($name));
        }else{
            return [];
        }
    }

    public function authenticate($route,$userId)
    {
        $auth = $this->getAuthCache();
        if (empty($auth['url'])){
            return 1001;
        }
        if (!in_array($route,$auth['url'])){
            return 1002;
        }
        $userAuth = $this->getUserAuthCache($userId);
        $authId = array_search($route,$auth['url']);
        $ancestors = array_column($auth['ancestors'][$authId],'url');
        if (empty(array_intersect($ancestors,array_column($userAuth,'url')))){
            return 1002;
        }
        return 0;
    }
}