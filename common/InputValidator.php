<?php


namespace qimao\common;


class InputValidator extends \app\models\BaseModel
{
    /**
     * @var array 验证规则
     */
    private $_rules = [];

    /**
     * @var array 虚拟属性
     */
    private $_visionAttributes = [];

    // 设置验证规则
    public function setRules($rules)
    {
        $this->_rules = $rules;

        foreach ($rules as $item) {
            $this->_visionAttributes = array_unique(array_merge($this->_visionAttributes, (array)$item[0]));
        }
    }

    // 重写获取验证规则
    public function rules()
    {
        return $this->_rules;
    }

    // 设置可用属性列表
    public function attributes()
    {
        return $this->_visionAttributes;
    }

    public function customValidate(&$data, $rules)
    {
        $this->setRules($rules);

        $this->load($data, '');
        // 进行验证
        $valid = $this->validate();

        // 覆盖值，使 default 验证器生效。
        $data = $this->attributes + $data;

        return $valid;
    }
}