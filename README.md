A Simple Authentication Library Based Yii2

安装步骤：
1、执行composer命令：composer require zhuyuzhu/yii2-authentication:dev-master
2、配置db信息
3、将vendor/qimao/yii2-authentication/messages/zh-CN/authentication文件夹复制到项目路径/messages/zh-CN/下
4、修改配置文件，增加配置
'i18n' => [
            'translations' => [
                '*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ]
5、