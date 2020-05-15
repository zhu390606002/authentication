<?php


namespace qimao\models;

class AuthRole extends AuthRoleTable
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
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'name',
        ];
        return $scenarios;
    }
}