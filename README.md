
AB   -  App Backed 框架
======================

基于 ThinkPHP框架, 用于快速开发APP的后台管理系统

## 主要新特性

* 采用`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 原生多应用支持
* 系统服务注入支持
* ORM作为独立组件使用
* 增加Filesystem
* 全新的事件系统
* 模板引擎分离出核心
* 内部功能中间件化
* SESSION机制改进
* 日志多通道支持
* 规范扩展接口
* 更强大的控制台
* 对Swoole以及协程支持改进
* 对IDE更加友好
* 统一和精简大量用法


> AB的运行环境要求PHP7.2+。

## 安装

~~~
composer create-project topthink/think tp
~~~

启动服务

~~~
cd tp
php think run
~~~

更新服务

~~~
composer update topthink/framework
~~~

## 相关文档

[完全开发手册](https://www.kancloud.cn/manual/thinkphp6_0/content)

## 参与开发

直接提交PR或者Issue即可

