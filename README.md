A Simple Authentication Library Based Yii2

安装步骤：
1、执行composer命令：composer require qimao/authentication:dev-master
2、配置db信息
3、将vendor/qimao/authentication/messages/zh-CN/authentication文件夹复制到项目路径/messages/zh-CN/下
4、修改配置文件，增加配置
'i18n' => [
            'translations' => [
                '*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ]
5、在bootstrap文件或者项目入口文件添加
Yii::setAlias('@qimao', dirname(__DIR__).'/vendor/qimao' );
6、将vendor/qimao/authentication/controllers下的文件复制到项目应用中的controllers路径下，并修改所有文件的命名空间为app/controllers