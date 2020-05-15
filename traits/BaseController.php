<?php


namespace qimao\authentication\traits;

use Yii;
use qimao\authentication\common\AuthenticationConstant;
trait BaseController
{
    /**
     * ajax请求响应
     *
     * @param string|array $data
     * @param string $message
     * @param int $code
     * @author wangjs<wangjs@km.com>
     * @date: 2018/08/29
     * @return string
     */
    public function ajaxResponse($data = '', $message = 'success', $code = AuthenticationConstant::SUCCESS_CODE)
    {
        if(empty($data)){
            $data = [];
        }
        $result = [
            'code' => $code,
            'message' => $message,
            'data' => $this->toString($data),
        ];
        return $this->asJson($result);
    }

    public function error($message = 'error', $code = AuthenticationConstant::FAIL_CODE, $data = '')
    {
        return $this->ajaxResponse($data, $message, $code);
    }

    public function verifyReponse($data = '', $message = 'fail', $code = AuthenticationConstant::VERIFY_CODE)
    {
        if(empty($data)){
            $data = ['empty' => ''];
        }
        $result = [
            'code' => $code,
            'message' => $message,
            'data' => $this->toString($data),
        ];
        $response = Yii::$app->response;
        $response->data = $result;
        $response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->end(0, $response);
    }

    /**
     * 通过post方式获取数据
     *
     * @param   mixed $key 参数名
     * @param   mixed $default 如果没有获取到对应参数值 则返回该参数结果
     * @date    2018/08/13
     * @author  Clarence
     * @return  mixed 对应的参数值
     */
    public function post($key, $default = '')
    {
        return Yii::$app->request->post($key, $default);
    }

    /**
     * 通过get方式获取数据
     *
     * @param   mixed $key 参数名
     * @param   mixed $default 如果没有获取到对应参数值 则返回该参数结果
     * @date    2018/08/13
     * @author  Clarence
     * @return  mixed 对应的参数值
     */
    public function get($key, $default = '')
    {
        return Yii::$app->request->get($key, $default);
    }

    /**
     * 把数组中的所有值都转化为字符串
     *
     * @param   array $data 数据信息
     * @date    2018/10/23
     * @author  Clarence
     * @return  array
     */
    private function toString($data)
    {
        if (empty($data) || is_object($data)) {
            return $data;
        }
        if (!is_array($data)) {
            return trim(strval($data));
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $res[$key] = static::toString($value);
            } else {
                $res[$key] = trim(strval($value));
            }
        }
        return $res;
    }
}