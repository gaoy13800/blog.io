---
layout: post
title: golang之ECHO框架Http请求及响应
date: 2018-04-24
categories: blog
tags: [golang,http,request,response,echo]
description: golang之echo框架的使用，数据解析及拼装
---


>> 开发环境:windows 
>> 开发语言：golang
>> 开发框架：echo集成
>> 主要功能： http 路由，不同数据的接收，不同数据类型的数据发送，time out超时的设置及异常抛出


### echo 简介

    高性能，可扩展，一个简约的  GO WEB 框架
    
    
#### echo 安装
    安装这个玩意只需要在gopath 目录下执行 **go get github.com/labstack/echo**
    but，有一个问题因为我国的特殊环境下载某些东西的时候可能下载不下来，所以你就需要想一些小办法用其他的方式
    替代这个东西。
    当然安装ehco的时候的确也是有这个问题的，它会报一个错误，don't worry，它是缺少一个**crypto**需要下载
    
    https://github.com/golang/crypto 我们直接到goroot/src/golang.org/x/位置，文件夹名称为crypto，
    以我的主机为例，我的goroot目录是E:/go
    
    继续安装 **go get github.com/labstack/echo**，你会发现安装成功了。echo框架我们就已经下载下来了
    
    好了，开启你们的echo之旅吧，顺带说一句，以后再碰到无法安装golang.org/x/***/之类的错误，
    
    只需要到https://github.com/golang/***去下载，放到${GOROOT}/src/golang/x/**目录下
    
    
    
####  echo 搭建

![img_c_compile](https://raw.githubusercontent.com/gaoy13800/gaoy13800.GitHub.io/master/_mdimg/0424_echo1.png)

    搭建echo就更简单了，完全自定义的web框架，只需要在使用的时候引用echo组件，然后搭建使用的
    路由。
    
    只需要一个 e := echo.New() 然后就可以设置路由及逻辑处理方法了，并进行启动
    
    
#### request response

    在web框架中最难搞的其实就是处理不同格式的输入和输出了。
    
    我们先说处理echo框架下的请求(request)：
        formdata和普通的json处理直接可以通过 
        
        c.FormParams() //取所有的请求参数
        c.FormValue("value") //取单个请求参数
        
        比较麻烦的是取一些硬件或者c#取得无格式或者原始数据需要用一些特殊的方法
        
        var v interface{}
        
        if err := json.NewDecoder(c.Request().Body).Decode(&v); err != nil {
        	return err
        }
        
        v 就可以直接处理了
        
    然后我们说一下响应
      因为可能有时候要返回有格式的响应所以我们需要自己搭建自己的struct然后转成json
      
      最常用的应该就是返回json了
      
      那么我们就这么处理:
      
      c.Response().Header().Set(echo.HeaderContentType, echo.MIMEApplicationJSONCharsetUTF8)
      		c.Response().WriteHeader(http.StatusOK)
      		return json.NewEncoder(c.Response()).Encode(result.Data)
    


### 几个比较好用的第三方扩展

1、 github.com/tidwall/gjson
    可对多种格式的数据进行解析处理，不需要再进行复杂的map循环取值

### 全局变量

因为之前的技术限制，一直以为整个http的请求进程是单向的所以请求的开始和结束所有的变量就是从开始创建
结束销毁，所以一直没有理解go中全局变量可以使用，今天实现了全局变量的使用，发现在服务开启之前初始化
全局变量并且使用通用的struct method newClass 进行全局变量的增删改查即可


![img_c_compile](https://raw.githubusercontent.com/gaoy13800/gaoy13800.GitHub.io/master/_mdimg/0424_echo2.png)
   