<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role_auth_relation".
 *
 * @property int $id
 * @property int $role_id 角色id
 * @property int $auth_id 权限id
 * @property int|null $delete_flag 是否删除 1是 0否
 */
class RoleAuthRelationTable extends \app\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_auth_relation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'auth_id', 'delete_flag'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'auth_id' => 'Auth ID',
            'delete_flag' => 'Delete Flag',
        ];
    }
}
