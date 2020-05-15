<?php
namespace qimao\common;

class AuthenticationConstant{
    const AUTH_TYPE_PAGE = 0;
    const AUTH_TYPE_INTERFACE = 1;

    const IS_DELETE_FALSE = 0;
    const IS_DELETE_TRUE = 1;

    //成功输出CODE
    const SUCCESS_CODE = 200;

    //错误时输出CODE,前端不捕捉错误信息
    const FAIL_CODE = 400;

    //参数验证错误,beforeAction调用
    const VERIFY_CODE = 402;
}