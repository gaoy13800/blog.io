---
layout: post
title: 干货集锦(二）
date: 2018-06-11
categories: blog
tags: [nginx+php的交互]
description: 干货集锦，nginx和php交互。
---

## nginx和php之间的交互



CGI是通用网关协议，FastCGI则是一种常住进程的CGI模式程序。我们所熟知的PHP-FPM的全称是PHP FastCGI Process Manager，即PHP-FPM会通过用户配置来管理一批FastCGI进程，例如在PHP-FPM管理下的某个FastCGI进程挂了，PHP-FPM会根据用户配置来看是否要重启补全，PHP-FPM更像是管理器，而真正衔接Nginx与PHP的则是FastCGI进程。

![nginx+php](https://raw.githubusercontent.com/gaoy13800/gaoy13800.GitHub.io/master/_mdimg/nginx_php_fork.png)


如上图所示，FastCGI的下游，是CGI-APP，在我们的LNMP架构里，这个CGI-APP就是PHP程序。而FastCGI的上游是Nginx，他们之间有一个通信载体，即图中的socket。在我们上文图3的配置文件中，fastcgi_pass所配置的内容，便是告诉Nginx你接收到用户请求以后，你该往哪里转发，在我们图3中是转发到本机的一个socket文件，这里fastcgi_pass也常配置为一个http接口地址（这个可以在php-fpm.conf中配置）。而上图5中的Pre-fork，则对应着我们PHP-FPM的启动，也就是在我们启动PHP-FPM时便会根据用户配置启动诸多FastCGI触发器（FastCGI Wrapper）。

fastcgi_param所声明的内容，将会被传递给“FastCGI server”，那这里指的就是fastcgi_pass所指向的server，也就是我们Nginx+PHP模式下的PHP-FPM所管理的FastCGI进程，或者说是那个socket文件载体。这时，有的同学会问：“为什么PHP-FPM管理的那些FastCGI进程要关心这些参数呢？”，好问题，我们一起想想我们做PHP应用开发时候有没有用到 $_SERVER 这个全局变量，它里面包含了很多服务器的信息，比如包含了用户的IP地址。同学们不想想我们的PHP身处socket文件之后，为什么能得到远端用户的IP呢？聪明的同学应该注意到图4中的一个fastcgi_param配置 REMOTE_ADDR ，这不正是我们在PHP中用 $_SERVER[‘REMOTE_ADDR’] 取到的用户IP么。的确，Nginx这个模块里fastcgi_param参数，就是考虑后端程序有时需要获取Webserver外部的变量以及服务器情况


对PHP有一定了解的同学，应该会知道PHP提供SAPI面向Webserver来提供扩展编程。但是这样的方式意味着你要是自主研发一套Webserver，你就需要学习SAPI，并且在你的Webserver程序中实现它。这意味着你的Webserver与PHP产生了耦合。在互联网的大趋势下，一般大家都不喜欢看到耦合。譬如Nginx在最初研发时候也不是为了和PHP组成黄金搭档而研发的，相信早些年的Nginx后端程序可能是其他语言开发。那么解决耦合的办法，比较好的方式是有一套通用的规范，上下游都兼容它。那么CGI协议便成了Nginx、PHP都愿意接受的一种方式，而FastCGI常住进程的模式又让上下游程序有了高并发的可能。那么，FastCGI的作用是Nginx、PHP的接口载体，就像插座与插销，让流行的WebServer与“世界上最好的语言”有了合作的可能。


Nginx+PHP的工程模式下，两位主角分工明确，Nginx负责承载HTTP请求的响应与返回，以及超时控制记录日志等HTTP相关的功能，而PHP则负责处理具体请求要做的业务逻辑，它们俩的这种合作模式也是常见的分层架构设计中的一种，在它们各有专注面的同时，FastCGI又很好的将两块衔接，保障上下游通信交互，这种通过某种协议或规范来衔接好上下游的模式，在我们日常的PHP应用开发中也有这样的思想落地，譬如我们所开发的高性能API，具体的Client到底是PC、APP还是某个其他程序，我们不关心，而这些PC、APP、第三方程序也不关心我们的PHP代码实现，他们按照API的规范来请求做处理即可。同学们是不是发现技术思想是可以在各个环节融会贯通的


#### 该篇文章转自 https://www.imooc.com/article/19278，若有侵权请直接联系我删除掉 

```

email: gaoy13800@aliyun.com

或 wexin告知我 gaoy13800

Thanks!


```