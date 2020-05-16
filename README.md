# 使用指南

本包是基于yii2框架实现的一套简单权限管理工具

## 数据结构

```mysql
CREATE TABLE `auth` (
  `auth_id` int(10) unsigned NOT NULL AUTO_INCREMENT comment '权限id',
  `name` varchar(50) NOT NULL default '' comment '权限名称',
  `url` varchar(255) NOT NULL default '' comment '权限url',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '权限类型：0-页面权限，1-接口权限',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '上级权限id',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是 0否',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '权限表';

CREATE TABLE `auth_role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT comment '角色id',
  `name` varchar(50) NOT NULL default '' comment '角色名称',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是 0否',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '权限角色表';

CREATE TABLE `role_auth_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '角色id',
  `auth_id` int(10) NOT NULL DEFAULT '0' COMMENT '权限id',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '角色权限关联表';

CREATE TABLE `user_auth_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  `role_id` int(10) NOT NULL DEFAULT '0' COMMENT '权限角色id',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '用户权限角色关联表';
```

安装时需要在数据库中生成以上数据表

auth：存储具体的权限信息，分为接口权限和页面权限

auth_role：存储角色信息

role_auth_relation：存储角色与权限的绑定关系

user_auth_role：存储用户与角色的绑定关系

## 目录

```
/authentication
	/common
		AuthenticationConstant.php
		funcionts.php
		InputValidator.php
	/controllers
		AuthController.php
		AuthRoleController.php
		BaseController.php
		SiteController.php
	/logic
		AbstractBaseLogic.php
		Auth.php
		AuthRole.php
	/messages
	/models
		Auth.php
		AuthRole.php
		AuthRoleTable.php
		AuthTable.php
		BaseModel.php
		RoleAuthRelation.php
		RoleAuthRelationTable.php
		UserAuthRole.php
		UserAuthRoleTable.php
	/traits
		Auth.php
		BaseController.php
	sql
```

common：该目录存放一下公共常量、函数和工具类

controllers：该目录为控制器目录，AuthController.php和AuthRoleController.php实现权限数据的增删改查接口，BaseController.php控制器实现后端接口的权限校验，SiteController.php控制器实现页面路由的权限校验

logic：实现具体应用逻辑的类

messages：输出信息

models：数据模型

traits：实现具体校验逻辑的方法

## 安装步骤

1. 项目路径下执行composer命令

   ```
   composer require qimao/authentication:dev-master
   ```

2. 配置db信息，执行sql语句，生成相关数据结构

3. 将vendor/qimao/authentication/messages/zh-CN/authentication文件夹复制到项目路径/messages/zh-CN/下

4. 修改配置文件，增加配置

   ```php
   'i18n' => [
               'translations' => [
                   '*' => [
                       'class'    => 'yii\i18n\PhpMessageSource',
                       'basePath' => '@app/messages',
                   ],
               ],
           ],
   'urlManager' => [
               'enablePrettyUrl' => true,
               'showScriptName' => false,
               'rules' => [
               ],
           ],
   ```

   在项目合适的位置增加路径配置，如bootstrap.php或者入口php文件

   ```php
   Yii::setAlias('@static', dirname(__DIR__) . '/web/static' );
   ```

   

5. 将vendor/qimao/authentication/controllers下的文件复制到项目应用中的controllers路径下，并修改所有文件的命名空间为app/controllers

6. 修改BaseController.php实现接口权限控制，项目其他接口需继承该类，也可以修改该类的继承类实现前置校验。刚安装时实例代码进行了注释，目的是在使用接口生成权限数据前不进行权限校验，因为此时数据表中尚无权限数据。使用时需要根据实际项目情况修改实例代码，实现权限管理。

   ```php
      //实例代码，uid为当前用户id，noAuthUser为无需校验的用户id集合
   	public function beforeAction($action)
       {
           if (parent::beforeAction($action)){
               if (!in_array($uid,$this->noAuthUser)){
                   $route = '/'.$action->controller->id.'/'.$action->id;
                   $code = $this->authenticate($route,$uid);
                   if ($code){
                       $this->verifyReponse([], Yii::t('authentication/auth', 'authentication_fail_'.$code));
                       return false;
                   }
               }
               return true;
           }else{
               return false;
           }
       }
   ```

7. 修改SiteController.php实现页面路由控制，为达到这一目的，需要在配置文件中将相关url定向到该控制器

   ```php
   	//页面路由控制实例，uid为当前用户id，noAuthUser为无需校验的用户id集合
   	public function actionIndex()
       {
           if (!in_array($uid,$this->noAuthUser)){
               $route = '/'. Yii::$app->request->pathInfo;
               $code = $this->authenticate($route,$uid);
               if ($code){
                   return $this->verifyReponse([], Yii::t('authentication/auth', 'authentication_fail_'.$code));
               }
           }
           return $this->render('index');
       }
   
   
   	//配置文件定向实例
           'urlManager' => [
               'enablePrettyUrl' => true,
               //'enableStrictParsing' => true,
               'showScriptName' => false,
               'rules' => [
                   'GET <controller:(admin)>/<action:[\w-]+>' => 'site/index',
                   'GET <modules:(admin)>/<controller:[\w-]+>/<action:[\w-]+>' => 'site/index',
                   'GET <modules:(admin)>/<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>' => 'site/index',
                   'GET <modules:(admin)>/<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<action1:[\w-]+>' => 'site/index',
                   'GET <modules:(admin)>/<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<action1:[\w-]+>/<action2:[\w-]+>' => 'site/index',
               ],
           ],
   ```

8. 配置完以上步骤，先将BaseController.php和SiteController.php控制器中的权限校验代码关闭，然后调用接口生成权限数据，之后再开启权限校验。

9. 配置权限，权限分为两种：页面权限和接口权限，页面权限指前端页面地址权限，接口权限指数据访问接口地址权限，均可实现树级管理。新增权限时，url必须以'/'开头，顶级权限的parent_id为0，其他权限均为顶级权限子权限。权限校验时，会先校验子权限是否满足，不满足时会校验该权限的所有父级权限，只要有一个符合，则认为该用户具有访问权限。实际应用中，通常将控制器id设为父权限，如'/auth'为权限控制器顶级权限，'/auth/add-auth'为权限控制器中新增权限的接口，则该权限即为权限控制器子权限。当用户仅配置父权限时，会认为该用户同时具有该父权限下所有子权限的访问权限，即当用户配置了'/auth'，则自动拥有'/auth/add-auth'的权限，可以不再重复配置。