# INSTALLATION

## TABLE OF CONTENTS
- [Before you begin](#before-you-begin)
- [Manual installation](#manual-installation)
    - [Requirements](#requirements)
    - [Setup application](#setup-application)
    - [Configure your web server](#configure-your-web-server)

- [Docker installation](#docker-installation)
- [Vagrant installation](#vagrant-installation)
- [Single domain installtion](#single-domain-installation)
- [Demo users](#demo-users)
- [Important-notes](#important-notes)

## Before you begin
1. If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

2. Install composer-asset-plugin needed for yii assets management
```bash
composer global require "fxp/composer-asset-plugin"
```

3. Install NPM or Yarn to build frontend scripts
- [NPM] (https://docs.npmjs.com/getting-started/installing-node)
- Yarn (https://yarnpkg.com/en/docs/install)

### Get source code
#### Download sources
https://github.com/ankyestudio/aktracking/archive/master.zip

#### Or clone repository manually
```
git clone https://github.com/ankyestudio/aktracking.git
```
#### Install composer dependencies
```
composer install
```


## Manual installation

### REQUIREMENTS
The minimum requirement by this application template that your Web server supports PHP 5.5.0.
Required PHP extensions:
- intl
- gd
- mcrypt
- com_dotnet (for Windows)

### Setup application
1. Copy `.env.dist` to `.env` in the project root.
2. Adjust settings in `.env` file
	- Set debug mode and your current environment
	```
	YII_DEBUG   = true
	YII_ENV     = dev
	```
	- Set DB configuration
	```
	DB_DSN           = mysql:host=127.0.0.1;port=3306;dbname=yii2-starter-kit
	DB_USERNAME      = user
	DB_PASSWORD      = password
	```

	- Set application canonical urls
	```
	FRONTEND_HOST_INFO    = http://yii2-starter-kit.dev
	BACKEND_HOST_INFO     = http://backend.yii2-starter-kit.dev
	STORAGE_HOST_INFP     = http://storage.yii2-starter-kit.dev
	```

3. Run in command line
```
php console/yii app/setup
npm install
npm run build
```

### Configure your web server
- Copy `docker/vhost.conf` to your nginx config directory
- Change it to fit your environment

## Docker installation
1. Follow [docker install](https://docs.docker.com/engine/installation/) instruction to install docker
2. Add ``127.0.0.1 yii2-starter-kit.dev backend.yii2-starter-kit.dev storage.yii2-starter-kit.dev``* to your `hosts` file
2. Copy `.env.dist` to `.env` in the project root
3. Run `docker-compose build`
4. Run `docker-compose up -d`
5. Log into the app container via `docker-compose exec app bash`
6. Install composer per instuctions available at [Composer](https://getcomposer.org/download/)
7. Run `php composer.phar global require "fxp/composer-asset-plugin"` and `composer install --profile --prefer-dist -o -v`
- If asked for a token aquire one from your [github account](https://github.com/settings/tokens).
8. Setup application with `php ./console/yii app/setup --interactive=0`
9. Exit the app container by using `exit`
10. That's all - your application is accessible on http://yii2-starter-kit.dev

 * - docker host IP address may vary on Windows and MacOS systems
 
*PS* Also you can use bash inside application container. To do so run `docker-compose exec app bash`

### Docker FAQ
1. How do i run yii console commands from outside a container?

`docker-compose exec app console/yii help`

`docker-compose exec app console/yii migrate`

`docker-compose exec app console/yii rbac-migrate`

2. How to connect to the application database with my workbench, navicat etc?
MySQL is available on `yii2-starter-kit.dev`, port `3306`. User - `root`, password - `root`

## Vagrant installation
If you want, you can use bundled Vagrant instead of installing app to your local machine.

1. Install [Vagrant](https://www.vagrantup.com/)
2. Copy files from `docs/vagrant-files` to application root
3. Copy `./vagrant/vagrant.yml.dist` to `./vagrant/vagrant.yml`
4. Create GitHub [personal API token](https://github.com/blog/1509-personal-api-tokens)
5. Edit values as desired including adding the GitHub personal API token to `./vagrant/vagrant.yml`
6. Run:
```
vagrant plugin install vagrant-hostmanager
vagrant up
```
That`s all. After provision application will be accessible on http://yii2-starter-kit.dev

## Demo data
### Demo Users
```
Login: admin
Password: admin

```

## Single domain installation
### Setup application
Adjust settings in `.env` file

```
FRONTEND_BASE_URL   = /
BACKEND_BASE_URL    = /backend/web
STORAGE_BASE_URL    = /storage/web
```

Adjust settings in `backend/config/web.php` file
```
    ...
    'components'=>[
        ...
        'request' => [
            'baseUrl' => '/admin',
        ...
```
Adjust settings in `frontend/config/web.php` file
```
    ...
    'components'=>[
        ...
        'request' => [
            'baseUrl' => '',
        ...
```

### Configure your web server
#### Single domain apache config
This is an example single domain config for apache
```
<VirtualHost *:80>
    ServerName yii2-starter-kit.dev

    RewriteEngine on
    # the main rewrite rule for the frontend application
    RewriteCond %{HTTP_HOST} ^yii2-starter-kit.dev$ [NC]
    RewriteCond %{REQUEST_URI} !^/(backend/web|storage/web)
    RewriteRule !^/frontend/web /frontend/web%{REQUEST_URI} [L]
    # redirect to the page without a trailing slash (uncomment if necessary)
    #RewriteCond %{REQUEST_URI} ^/backend/$
    #RewriteRule ^(/backend)/ $1 [L,R=301]
    # disable the trailing slash redirect
    RewriteCond %{REQUEST_URI} ^/backend$
    RewriteRule ^/backend /backend/web/index.php [L]
    # the main rewrite rule for the backend application
    RewriteCond %{REQUEST_URI} ^/backend
    RewriteRule ^/backend(.*) /backend/web$1 [L]

    DocumentRoot /your/path/to/yii2-starter-kit
    <Directory />
        Options FollowSymLinks
        AllowOverride None
        AddDefaultCharset utf-8
    </Directory>
    <Directory "/your/path/to/yii2-starter-kit/frontend/web">
        RewriteEngine on
        # if a directory or a file exists, use the request directly
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        # otherwise forward the request to index.php
        RewriteRule . index.php

        Require all granted
    </Directory>
    <Directory "/your/path/to/yii2-starter-kit/backend/web">
        RewriteEngine on
        # if a directory or a file exists, use the request directly
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        # otherwise forward the request to index.php
        RewriteRule . index.php

        Require all granted
    </Directory>
    <Directory "/your/path/to/yii2-starter-kit/storage/web">
        RewriteEngine on
        # if a directory or a file exists, use the request directly
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        # otherwise forward the request to index.php
        RewriteRule . index.php

        Require all granted
    </Directory>
    <FilesMatch \.(htaccess|htpasswd|svn|git)>
        Require all denied
    </FilesMatch>
</VirtualHost>
```

#### Single domain nginx config
This is an example single domain config for nginx

```
server {
    listen 80;

    root /var/www;
    index index.php index.html;

    server_name yii2-starter-kit.dev;

    charset utf-8;

    # location ~* ^.+\.(jpg|jpeg|gif|png|ico|css|pdf|ppt|txt|bmp|rtf|js)$ {
    #   access_log off;
    #   expires max;
    # }

    location / {
        root /var/www/frontend/web;
        try_files $uri /frontend/web/index.php?$args;
    }

    location /backend {
        try_files  $uri /backend/index.php?$args;
    }

    # storage access
    location /storage {
        try_files  $uri /storage/web/index.php?$args;
    }

    client_max_body_size 32m;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass php-fpm;
        fastcgi_index index.php;
        include fastcgi_params;

        ## Cache
        # fastcgi_pass_header Cookie; # fill cookie valiables, $cookie_phpsessid for exmaple
        # fastcgi_ignore_headers Cache-Control Expires Set-Cookie; # Use it with caution because it is cause SEO problems
        # fastcgi_cache_key "$request_method|$server_addr:$server_port$request_uri|$cookie_phpsessid"; # generating unique key
        # fastcgi_cache fastcgi_cache; # use fastcgi_cache keys_zone
        # fastcgi_cache_path /tmp/nginx/ levels=1:2 keys_zone=fastcgi_cache:16m max_size=256m inactive=1d;
        # fastcgi_temp_path  /tmp/nginx/temp 1 2; # temp files folder
        # fastcgi_cache_use_stale updating error timeout invalid_header http_500; # show cached page if error (even if it is outdated)
        # fastcgi_cache_valid 200 404 10s; # cache lifetime for 200 404;
        # or fastcgi_cache_valid any 10s; # use it if you want to cache any responses
    }
}

## PHP-FPM Servers ##
upstream php-fpm {
    server app:9000;
}
```

## Important notes
- There is a VirtualBox bug related to sendfile that can lead to corrupted files, if not turned-off
Uncomment this in your nginx config if you are using Vagrant:
```sendfile off;```
