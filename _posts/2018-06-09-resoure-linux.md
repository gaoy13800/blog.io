---
layout: post
title: 干货集锦(一)
date: 2018-06-09
categories: blog
tags: [lnmp,docker安装,shell脚本]
description: 干货集锦，shell脚本。
---


## 干货集锦（一） centos7基本环境安装及shell脚本分享


### 搭建centos环境

从接触linux环境以来就一直想从零搭建一个完整的Centos开发生产环境并记录下来，知道最近这段时间才正好自己部署新的东西，然后得空搭了一套
完整的lnmp环境并写成了一个脚本能够一键搭建。

步骤 
#### 首先需要一个极简的centos7环境

针对这一步，我安装了最小化的centos7，并配置网卡，更改虚拟机设置，配置桥接网络，更改centos7默认的网卡设置。


```

cat /etc/sysconfig/network-scripts/ifcfg-ens33
vi /etc/sysconfig/network-scripts/ifcfg-ens33

TYPE=Ethernet
PROXY_METHOD=none
BROWSER_ONLY=no
#BOOTPROTO=dhcp   
DEFROUTE=yes
IPV4_FAILURE_FATAL=no
IPV6INIT=yes
IPV6_AUTOCONF=yes
IPV6_DEFROUTE=yes
IPV6_FAILURE_FATAL=no
IPV6_ADDR_GEN_MODE=stable-privacy
NAME=ens3
UUID=9d18ab7f-c2ad-42a8-915c-914755bfbd51
ONBOOT=yes
IPADDR=192.168.0.212
NETMASK=255.255.255.0
GATEWAY=192.168.0.1
DNS1=192.168.0.1


```



#### 其次，就该配置yum源了

这次我的yum源是跟我们的运维小哥要的一份，这个我会分享到百度网盘

```
# 链接：https://pan.baidu.com/s/18JRN0HGYwtY3wDzcU1exmw 密码：coxr


#在删除 /etc/yum.repo.d/中所有的文件之前下载一个 lrzsz 让其能下载刚刚从云盘拿下来的文件

#之后执行

mv rpm-gpg-key-epel-7 RPM-GPG-KEY-EPEL-7
\cp RPM-GPG-KEY-* /etc/pki/rpm-gpg/

yum clean all

```

这样，yum源就配置完成了。


#### 


#### 最后就执行lnmp的脚本就完事大吉了

暂且先把脚本放到这里，若之后有不妥再换成文件分享

```

#!/bin/bash



NGINX_CONF="/etc/nginx/nginx.conf"
PHP_FPM_CONF="/etc/php-fpm.d/www.conf"
PHP_LOG="/var/log/php-fpm"
INIT_FILE="/etc/rc.local"
REDIS_CONF="/etc/redis.conf"
NGINX_USER="walle"
mkdir /data
yum install -y epel-release
rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm 
yum install net-tools openssh-server nginx php70w-fpm php70w-pdo php70w-pdo \
                  php70w-mysql php70w-xml php70w-gd php70w-mcrypt php70w-mbstring \
                  bzip2 php70w-pecl-redis php70w-devel mariadb mariadb-server  nginx  lrzsz redis postgresql-devel gcc gcc-c++ wget  -y 
useradd -m ${NGINX_USER} -s /bin/bash
wget -c https://github.com/swoole/swoole-src/archive/v2.0.6.tar.gz
tar -zxvf v2.0.6.tar.gz && cd swoole-src-2.0.6 && phpize && ./configure && make && make install
sed -i '/dba.default_handler/a\extension=swoole.so' /etc/php.ini
sed -i "/events/ause epoll;" $NGINX_CONF 
sed -i "s/^user.*/user $NGINX_USER;/" $NGINX_CONF 
sed -i "s/worker_connections.*/worker_connections 65535;/" $NGINX_CONF 
sed -i "/access_log/aunderscores_in_headers on;" $NGINX_CONF 
sed -i "/access_log/agzip_http_version 1.1;" $NGINX_CONF 
sed -i "/access_log/agzip_buffers 4 16k;" $NGINX_CONF 
sed -i "/access_log/agzip_vary on;" $NGINX_CONF 
sed -i "/access_log/agzip_types text/plain application/javascript text/css application/xml application/x-httpd-php image/jpeg image/gif image/png;" $NGINX_CONF 
sed -i "/access_log/agzip_comp_level 4;" $NGINX_CONF 
sed -i "/access_log/agzip_min_length 1k;" $NGINX_CONF 
sed -i "/access_log/agzip on;" $NGINX_CONF 
sed -i '/server/,/$#/d' $NGINX_CONF 
sed -i '$a }' $NGINX_CONF 
sed -i "s/^user.*/user = $NGINX_USER/" $PHP_FPM_CONF 
sed -i "s/^group.*/group = $NGINX_USER/" $PHP_FPM_CONF 
sed -i "s/bind 127.0.0.1/#bind 127.0.0.1/" $REDIS_CONF
sed -i "s/# requirepass foobared/requirepass 123456/" $REDIS_CONF
chown -R ${NGINX_USER}.root $PHP_LOG 
chown -R ${NGINX_USER}.${NGINX_USER} /var/lib/nginx 
chown -R ${NGINX_USER}.${NGINX_USER} /var/log/nginx 
ulimit -n 65535
sed -i '$a ulimit -n 65535' $INIT_FILE 
sed -i '$a source /etc/profile' $INIT_FILE 
curl -sS https://getcomposer.org/installer | php 
mv composer.phar /usr/local/bin/composer
systemctl start nginx mariadb sshd php-fpm redis 
systemctl enable  nginx mariadb php-fpm redis
chown -R ${NGINX_USER}.${NGINX_USER} /data
yum clean all
su - walle -c "composer config -g repo.packagist composer https://packagist.phpcomposer.com" 				
# docker 安装
yum install docker-io -y
echo "OPTIONS='--registry-mirror=https://mirror.ccs.tencentyun.com'" >> /etc/sysconfig/docker
systemctl daemon-reload
systemctl restart docker




```





## 这些真的满满都是干货，一般人我可不告诉他！