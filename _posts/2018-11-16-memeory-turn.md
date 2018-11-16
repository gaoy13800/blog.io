---
layout: post
title: php内存溢出问题重现及解决
date: 2018-11-16
categories: blog
tags: [php,memory]
description: ...
---

# php内存溢出问题

标签（空格分隔）： php

---

> 因为之前没有接触过内存溢出的问题，但有一天在一个博客中看到有人问所以我重现了一下

```
<?php
ini_set('memory_limit', '1M') #初始化内存限制为1M
$info=file_get_contents("./test.log"); # 读取的日志文件为20M+
var_dump（$info）; 
```

直接在Centos上运行了一下
```
php tess_memory.php
```

![oper_img](https://raw.githubusercontent.com/gaoy13800/gaoy13800.GitHub.io/master/_mdimg/1116-memory_result.png)

**果然内存溢出了**

### 分析内存溢出的原因

脚本一次性读取了大量的数据(可能是读的文件,可能是读取的数据库)，但是分配的内存或者本身不够用。

### 解决方案

a. 增大给脚本分配的内存 ->治标不治本

```
ini_set('memory_limit','100M');
```
b. 循环,分段读取数据,读数据库的话可以用limit

c. 还有一些其他的解决方案

* 应当尽可能减少静态变量的使用，在需要数据重用时，可以考虑使用引用(&)。
* 数据库操作完成后，要马上关闭连接；
* 一个对象使用完，要及时调用析构函数（__destruct()）
* 用过的变量及时销毁(unset())掉
* 可以使用memory_get_usage()函数,获取当前占用内存 根据当前使用的内存来调整程序
* unset()函数只能在变量值占用内存空间超过256字节时才会释放内存空间。(PHP内核的gc垃圾回收机制决定)