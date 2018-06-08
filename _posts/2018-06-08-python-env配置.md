---
layout: post
title: python多版本的安装及配置，虚拟环境的安装及配置多版本的python(windows)
date: 2018-06-08
categories: blog
tags: [python2,python3,virtualenv,配置安装]
description: 人生苦短,我学python。
---


## 多版本的安装配置

> 下载地址:  [python下载地址](www.python.org)


### 1、 安装python2.x

![grpc流程图](https://raw.githubusercontent.com/gaoy13800/gaoy13800.GitHub.io/master/_mdimg/python2.x_download.png)

 	双击 msi文件 然后一路Next就哦了。 需要注意的是 要自定义python2.x的安装目录，便于之后的环境变量及虚拟环境的配置。

### 2、 安装python3.x

	安装3.x也是一路的next，另外要注意勾选 "Add Python 3.x to Path",它会帮你自动配置环境变量,而python2.x是手动配置的

### 3、 解决冲突问题，同时让两个环境都生效

	* 配置python2.x的环境变量
	* 将2.x和3.x 的环境变量区分开来

配置环境变量是很简单的，就是把2.x的安装目录及Scripts目录放到Path生效即可。
区分2.x和3.x 的最简单办法就是在python3.x 的命令后面都加一个3 
例如 python.exe 变为python3.exe, pip.exe 变为pip3.exe就成了然后删除 python3.x/Scripts 下的pip.exe 
重启命令终端。但发现输入pip3的时候会出现一个问题，其实就是pip的版本太低了，升级一下就可以了 python3 -m pip install -U pip

工欲善其事必先利其器，一个好的开发环境会带动整体的开发节奏。

## 安装 virtualenv及其管理工具virtualenvwapper

1、 安装virtualenv

```
pip install virtualenv

```

2、 安装virtualenv的管理工具virtualenvwrapper

因为一旦开发环境的增多，每次的进入虚拟环境步骤太过繁琐，所以我们使用它的管理工具virtualenvwrapper

```
pip install virtualenvwrapper-win
```

3、 设置WORK_HOME 变量

这个环境变量设置的目录就是今后所有虚拟环境的安装目录

```
mkvirtualenv testenv #创建一个虚拟环境

workon #查看所有安装的虚拟环境

workon testenv #进入一个虚拟环境

deactivate # 退出一个虚拟环境 

```

oh my god! 这样的效率提升的简直不是一个量级。

下次把linux上的多环境也出一版。 happy gao