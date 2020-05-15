<?php


namespace app\models;

use app\common\AuthenticationConstant;
use Yii;

class RoleAuthRelation extends RoleAuthRelationTable
{
    static public function updateRoleAuthRelationByRoleId($roleId)
    {
        return Yii::$app->db->createCommand()->update('role_auth_relation', ['delete_flag' => AuthenticationConstant::IS_DELETE_TRUE], 'role_id in (:role_id)',[':role_id'=>implode(',',$roleId)])->execute();
    }

    static public function batchInsertByRoleAndAuth($insert)
    {
        return Yii::$app->db->createCommand()->batchInsert('role_auth_relation', ['role_id','auth_id'], $insert)->execute();
    }
}