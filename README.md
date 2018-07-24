电魂播放器后台

###安装项目
git clone 到本地

执行 composer install,创建好数据库

复制.env.example命名为.env 配置 .env 中数据库、redis连接信息

执行 php artisan key:generate

chmod -R 777 storage
chmod -R 777 bootstrap

先调试 结束之后  修改APP_DEBUG=false