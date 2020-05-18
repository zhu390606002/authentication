<?php

namespace qimao\authentication\models;

use Yii;

/**
 * This is the model class for table "user_auth_role".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $role_id 权限角色id
 * @property int|null $delete_flag 是否删除 1是 0否
 */
class UserAuthRoleTable extends \qimao\authentication\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'qma_user_auth_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id', 'delete_flag'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'role_id' => 'Role ID',
            'delete_flag' => 'Delete Flag',
        ];
    }
}
