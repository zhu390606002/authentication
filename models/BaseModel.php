<?php
/**
 * 公共model方法
 * User: cuiyanhui
 * Date: 2018/8/24
 * Time: 11:04
 */
namespace qimao\authentication\models;

class BaseModel extends \yii\db\ActiveRecord
{
    protected static $error;

    /**
     * 获取流程错误
     *
     * @author cuiyh<cuiyanhui@km.com>
     * @date: 2018/8/24
     * @return mixed
     */
    public static function errors()
    {
        return self::$error;
    }

    /**
     * 重写getErrors方法
     *
     * @param null $attribute
     * @author cuiyh<cuiyanhui@km.com>
     * @date 2018/11/07
     * @return array
     */
    public function getErrors($attribute = null)
    {
        $error = parent::getErrors($attribute);
        $res = array_shift($error);
        return $res[0];
    }
}
