<?php

namespace qimao\authentication\models;

use Yii;

/**
 * This is the model class for table "auth_role".
 *
 * @property int $role_id 角色id
 * @property string $name 角色名称
 * @property int|null $delete_flag 是否删除 1是 0否
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 */
class AuthRoleTable extends \qimao\authentication\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'qma_auth_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delete_flag'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'name' => 'Name',
            'delete_flag' => 'Delete Flag',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
