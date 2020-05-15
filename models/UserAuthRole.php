<?php


namespace app\models;

use Yii;
use app\common\AuthenticationConstant;
class UserAuthRole extends UserAuthRoleTable
{
    static public function getUserAuthByUserId($userId)
    {
        return self::find()
            ->select(['user_auth_role.user_id','user_auth_role.role_id','rar.auth_id','a.name','a.url','a.type','a.parent_id'])
            ->leftJoin('role_auth_relation rar','user_auth_role.role_id = rar.role_id and rar.delete_flag = '.AuthenticationConstant::IS_DELETE_FALSE)
            ->leftJoin('auth a','rar.auth_id = a.auth_id and a.delete_flag = '.AuthenticationConstant::IS_DELETE_FALSE)
            ->where(['user_auth_role.delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE,'user_auth_role.user_id'=>$userId])
            ->asArray()->all();
    }

    static public function updateUserAuthRoleByRoleId($roleId)
    {
        return Yii::$app->db->createCommand()->update('user_auth_role', ['delete_flag' => AuthenticationConstant::IS_DELETE_TRUE], 'role_id in (:role_id)',[':role_id'=>implode(',',$roleId)])->execute();
    }

    static public function updateUserAuthRoleByUserId($userId)
    {
        return Yii::$app->db->createCommand()->update('user_auth_role', ['delete_flag' => AuthenticationConstant::IS_DELETE_TRUE], 'user_id in (:user_id)',[':user_id'=>implode(',',$userId)])->execute();
    }

    static public function batchInsertByUserAndRole($insert)
    {
        return Yii::$app->db->createCommand()->batchInsert('user_auth_role', ['user_id','role_id'], $insert)->execute();
    }

}