<?php


namespace qimao\authentication\models;

use qimao\authentication\common\AuthenticationConstant;
use Yii;

class RoleAuthRelation extends RoleAuthRelationTable
{
    static public function updateRoleAuthRelationByRoleId($roleId)
    {
        return Yii::$app->db->createCommand('update role_auth_relation set delete_flag = '.AuthenticationConstant::IS_DELETE_TRUE.' where role_id in ('.implode(',',$roleId).')')->execute();
    }

    static public function batchInsertByRoleAndAuth($insert)
    {
        return Yii::$app->db->createCommand()->batchInsert('role_auth_relation', ['role_id','auth_id'], $insert)->execute();
    }
}