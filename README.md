##环境
1. Laravel 5.5.* 
2. Mysql 5.7
3. PHP 7.2.*
4. redis
5. RabbitMQ
 
##安装步骤
1. 下载或克隆项目，进入项目根目录执行,等待框架安装

``composer install``

2. 将.env.example修改为.env,并进行相关配置,然后在项目根目录执行

``php artisan key:generate``
3. 手动创建数据库,执行迁移数据库表结构和数据

``php artisan migrate:refresh --seed``

4. 运行根目录count_event.sql里面的sql语句

#5. 去掉数据库sql_model中的ONLY_FULL_GROUP_BY

6. 查看数据库事件是否开启

``SHOW VARIABLES LIKE 'event_scheduler'``

7. 数据库事件开启运行

``set GLOBAL event_scheduler = 1``

8. 开启定时任务

``alter event user_order_day_event on completion preserve enable``

``alter event user_yesterdyay_order_event on completion preserve enable``

``alter event account_day_event on completion preserve enable``

``alter event account_yesterday_event on completion preserve enable``

9. 查看事件运行状态

``select * from mysql.event;``

##安装报错解决
安装时报错，提示 Your requirements could not be resolved to an installable set of packages?

``composer install --ignore-platform-reqs``

10. 安装supervisor

``yum install python-setuptools``

``easy_install supervisor``

``echo_supervisord_conf > /etc/supervisord.conf``

``vim /etc/supervisord.conf`` 拉到最底下开启
#####[include]
#####files = /etc/supervisord.d/*.conf

``cd /etc/supervisord.d``创建文件 laravel-worker.conf 复制如下内容
````

然后依次运行

``sudo supervisorctl reread`` 重新启动配置中的所有程序

``sudo supervisorctl update`` 更新新的配置

``sudo supervisorctl start laravel-worker:*`` 启动进程

## supervisorctl shutdown 停止命令，运行重启命令报错：运行 supervisord
##这里值得注意的是，如果 Laravel 处理队列的代码更改了，需要重启 Supervisor 的队列管理才能生效。






##使用扩展包：
1. 验证码 [mews/captcha](https://github.com/mewebstudio/captcha)
2. php-amqplib": "^2.8"


##使用前端资源：
1. AdminLTE
2. toastr.js
3. sweetalert
4. bootstrap-switch
5. bootstrap-fileinput-4.5.1
6. Chart.min.js
7. jquery.qrcode.min.js

##命令使用---项目根目录下使用
1. ``nohup php artisan queue:work --queue=orderNotify &`` 运行异步下游通知队列
2. ``nohup php artisan phone:get &`` 运行手机监听
3. ``nohup php artisan order:callback &`` 运行免签订单回调监听
4. ``nohup php artisan wechatQrcode:get & ``运行微信二维码生成监听

## ``php artisan queue:restart`` 队列重启命令

 
##日志
以天为单位,订单回调详情日志：orderCallback; 异步通知只记录通知失败的信息：orderAsyncNotify;


