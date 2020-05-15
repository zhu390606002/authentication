<?php

namespace qimao\authentication\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property int $auth_id 权限id
 * @property string $name 权限名称
 * @property string $url 权限url
 * @property int $type 权限类型：0-页面权限，1-接口权限
 * @property int $parent_id 上级权限id
 * @property int|null $delete_flag 是否删除 1是 0否
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 */
class AuthTable extends \qimao\authentication\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'parent_id', 'delete_flag'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'auth_id' => 'Auth ID',
            'name' => 'Name',
            'url' => 'Url',
            'type' => 'Type',
            'parent_id' => 'Parent ID',
            'delete_flag' => 'Delete Flag',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
