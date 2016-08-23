## Viblo

### About

* Vietnam It BLOg

### Set up

#### Checkout repository

```bash
% git clone https://github.com/framgia/ngheroi.git
```

#### Install composer and update

```bash
% curl -s http://getcomposer.org/installer | php
% composer update
```

#### Check environment as "local"

```bash
% php artisan env
local
```

#### Local database config file

```bash
app/config/local/database.php
```

#### Create database

Create database
Create user and grant permission on database

```bash
% php artisan migrate
% php artisan db:seed
```

#### Run server on local

```bash
% php artisan serve
http://localhost:8000
```

#### Or create a virtual host in Apache (for how to do, Google it yourself)



#Deploy to staging server
- Connect to server
- change working directory to `/var/www/ngheroi`
- Change Application stage to Maintain Mode
```bash
$php artisan down
```
- Pull new code from remote
```bash
$git pull origin develop
```
- Run migrate (if need)
```bash
$php artisan migrate
```
- Run composer update (if need)
```bash
$php composer update
```
- CHMOD folder for upload file
- - `/public/uploads/`
- - `/public/css/`
- - `/public/img/categories/`

-Change Application tage
```bash
$php artisan up
```


#Deploy to product server

- Connect to server
- Change to user deploy
```bash
$sudo su - deploy
```

- change working directory to `/usr/share/nginx/viblo/`
- Change Application stage to Maintain Mode
```bash
$php artisan down
```
- Pull new code from remote
```bash
$git pull origin develop
```
- Run migrate (if need)
```bash
$php artisan migrate
```
- Run composer update (if need)
```bash
$php composer update
```
- CHMOD folder for upload file
- - `/public/uploads/`
- - `/public/css/`
- - `/public/img/categories/`

-Change Application tage
```bash
$php artisan up
```

```bash
apacheが起動されていますが、vibloはnginxを利用するので
vibloにアクセスできませんでした。
apacheを停止してからnginxを再起動したことによりシステム復活しました
```
