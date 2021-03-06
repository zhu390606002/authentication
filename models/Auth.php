<?php


namespace qimao\authentication\models;

use qimao\authentication\common\AuthenticationConstant;
use Yii;
class Auth extends AuthTable
{
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';
    /**
     *增加场景
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'name',
            'url',
            'type',
            'parent_id',
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'name',
            'url',
            'type',
            'parent_id',
        ];
        return $scenarios;
    }

    static public function getAllAuth($type)
    {
        return self::find()->where(['delete_flag'=>AuthenticationConstant::IS_DELETE_FALSE,'type'=>$type])->asArray()->all();
    }

    static public function updateAuthById($authId)
    {
        return Yii::$app->db->createCommand('update auth set delete_flag = '.AuthenticationConstant::IS_DELETE_TRUE.' where auth_id in ('.implode(',',$authId).')')->execute();
    }
}