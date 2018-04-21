---
layout: post
title: golang mongodb使用
date: 2018-04-20
categories: blog
tags: [golang,mongodb,mgo]
description: golang中使用mongodb
---

## golang下mongodb数据库的使用


> 这几天要用go开发一个集成到windows服务的服务，数据库用到的是mongodb，下面就是对mongodb在golang下的使用

MongoDB 是一个基于分布式文件存储的数据库。由 C++ 语言编写。旨在为 WEB 应用提供可扩展的高性能数据存储解决方案。

MongoDB 是一个介于关系数据库和非关系数据库之间的产品，是非关系数据库当中功能最丰富，最像关系数据库的。



#### collection 集合

MongoDB 文档类似于 JSON 对象。字段值可以包含其他文档，数组及文档数组。

```

{

	name:"gaoy",
	age:23,
	status:"A",
	group:["news", "port"]

}

键值对，   field -> value


```
Mongo支持丰富的查询表达式。查询指令使用JSON形式的标记，可轻易查询文档中内嵌的对象及数组


database  database  数据库

table    collection 数据库表/集合

row       document 文档/数据库记录行

column		field


一个mongodb中可以建立多个数据库。


use local  选择数据库


## mongodb 在golang中的使用

在这次的项目中使用的一个很强大的第三方库 **"gopkg.in/mgo.v2/bson"**

DB 类中 init初始化一个mongo句柄

```
package Db

import mgo "gopkg.in/mgo.v2"

session, err := mgo.Dial("mongodb://127.0.0.1:27017")

if err != nil {
		fmt.Println("mongodb 连接失败")

		os.Exit(0)
	}

MongoServiceInfo = session.DB(DB_NAME)  //选择数据库



引用mongo句柄

DocTable = Db.MongoServiceInfo.C("collectionName") //选择表/文档


搜索文档的数据源

1、

  使用map进行搜索

  searchCondition := make(map[string]string) //声命一个map类型的条件筛选

  searchCondition["deviceId"] = DeviceId // 给map赋值

  searchResult := make(map[string]interface{}) //声明一个map类型的结果集

  DocTable.Find(searchCondition).One(&searchResult) //执行筛选


2、 
	使用 bson(gopkg.in/mgo.v2库)

	在筛选两个条件时使用map发现并不能查出来，阅读mgo.v2源码时发现有一个struct也就是bson.D可以直接去筛选

	使用方法:
		Find
		orderResultMap := make(map[string]interface{})
		DocParking.Find(bson.D{{"carnumber", License}, {"orderstatus", 2}}).One(&orderResultMap)

		Remove
		DocWaitOrder.RemoveAll(bson.D{{"carnumber", License}, {"orderstatus", 2}})

insert 新增数据

新增数据很简单只需要给struct赋值即可


type insertData struct{
	
	name string 
	age  int
}

DocTable.Insert(insertData)

```


### windows 安装mongodb

	安装mongodb时还需要把mongodb做成一个服务。

	安装包: ![mongodb_url]("https://pan.baidu.com/s/1_aTsqTqwXLQGuPYXIk_jwQ") 
	百度云密码:4wdc

	在bin文件夹下新建一个 log和db
	
	然后在bin目录以管理员权限打开cmd，执行一句指令 --install --dbpath "刚刚新建的db目录绝对路径" --logpath "log目录txt" 
	

	服务安装后执行 net start mongodb

	服务安装成功 默认端口号:27017

### 客户端工具

	MongoDb Compass Community 

	链接：https://pan.baidu.com/s/1Ik0nFkcl8OXgYtDDppuvIQ 密码：ao11