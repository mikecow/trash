# trash
基于thinkphp的垃圾管理网站

一个基于thinkphp开发的垃圾管理系统，内部包括权限管理，筛选机制等功能

分为三个用户： 
1. 管理员公户， 数据库中存储type为0， 可以添加管理员，添加农家肥，添加地区等
2. 普通用户，普通用户可以可以进行添加垃圾操作以及积分兑换操作等，积分可以通过上交有机垃圾获得
3. 公司代理人账户，经过管理员添加公司后，公司代理人可以以公司的名义进行登录，购买农家肥等操作

部署要求：
下载安装apache， mysql， php
将文件目录放置在本地的www目录下
在mysql中建立数据库
在浏览器中输入自己放置的地址中即可运行。


注意：
相关数据库结构创建在mysql.sql中，想要运行文件必须得要有相应的数据库！
