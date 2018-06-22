# node-points

# 调整说明

1. 为V1.0版本提供了后台服务，后台使用PHP作为开发语言
2. 对原始的数据进行了改动，改动的SQL在sql目录下，主要为增加字段，建议在测试库先运行

# 接口说明

|接口名|功能|接口地址|完成度|请求参数|返回结果|返回结果示例| 
|---|---|---|---|---|---|---|
|提交反馈 | 提交课程打分及评论内容|/feedback|完成|stars(int)、content(string)|data:空|{"code":200,"message":ok,"data":{}}|
|抽奖 | 课堂抽Node积分，抽奖范围是1～200 | /giveaways |完成|sessionid(string)|data中增加nodes字段，为node积分|{"code":200,"message":ok,"data":{"nodes":199}}|
|提交钱包地址 | 绑定用户钱包地址 |/student/address/|完成|sessionid(string)|data中增加address字段，为用户钱包地址|{"code":200,"message":ok,"data":{"address":"xxxfffxxxaaeee"}}|
|查看钱包地址| 获取用户的钱包地址|/student/address/|完成|sessionid(string)、address(string)|data:空|{"code":200,"message":ok,"data":{}}|
|登录|根据code获取sessionid|/student/login|完成|code(string)|data包含是否绑定用户信息和sessionid|{code: 200,message: "ok",data: {bind: 1,sessionid: "39bf07012c89aa0a0a83a332b4477bfd"}}|
|退出登录|清除当前sessionid|/student/logout|完成|sessionid(string)|{"code":200,"message":ok,"data":[]}|
|绑定用户信息|填写用户姓名、电话、钱包地址等|/student/bindInfo|完成|sessionid(string)、phone(string)、name(string)、wallet_address(string,钱包地址),nickname(string,微信昵称),profile(微信头像url)|data:空|{"code":200,"message":ok,"data":{}}|
|获取用户信息|获取用户信息,姓名、头像、钱包地址等|/student/getInfo|完成|sessionid(string)|data:空|{"code":200,"message":"ok","data":{"name":"zhangsan","phone":"18700183","wallet_address":"123a18sdaf8dg7s8g0fg8ag","profile":"url","comments":null,"created_at":"2018-05-31 18:06:18","updated_at":"2018-05-31 18:50:21"}}|
|查看当前是否在课程时间|用来前端判断展示按钮是否可点击|/course/isOnCourse|完成|无|data:增加onCourse字段(boolean)|{"code":200,"message":ok,"data":{"onCourse":true}}|
|排名列表|查看全球用户榜|/nodeRank/rankPage|完成|start(int)默认0,num(int)默认20|data包含list和total|{"code":200,"message":"ok","data":{"list":[{"name":"\u4f55\u7476","profile":null,nickName: null,"total":"120"},{"name":"\u674e\u5b8f\u4f1f","profile":null,nickName: null,"total":"100"},{"name":"\u96f7\u529b","profile":"url_test",nickName: null,"total":"10"}],"total":3}}|
|用户排名|查看当前用户排名|/nodeRank/selfRank|完成|sessionid(string)|data包含排名|{"code":200,"message":"ok","data":{"self_rank":1}}|
|签到|课程签到,获取积分|/sign|完成|sessionid(string)、point(string,经纬度逗号拼接)|data包含nodes字段|{"code":200,"message":ok,"data":{"nodes":350}}|

# 脚本

有一个用于更新NODE积分的定时任务,脚本位置在application/controllers/Crontab.php,使用方法见注释。

# 代码中需要配置的地方
1. 数据库配置: application/config/database.php
2. 初试环境配置: index.php中更改ENVIRONMENT常量
3. 微信appid和secret配置: application/libraries/WeChat.php

# 可以优化的配置
1. 签到时距离要求(目前1000m): application/libraries/Map.php中的isInCourseRange()函数默认值
2. 课程允许前后时间范围设置(目前前后60分钟): application/models/Courses_model.php中的isOnCourse()函数默认值

# 注意事项
1. 多数接口需要sessionid参数,由后端生成,用来标识一个用户,有效时间目前设置为一天
2. 抽奖结果由后端生成,采用普通随机数,范围为1~200。
3. 签到过程主要根据用户经纬度坐标判断,需要小程序端获取地理位置。

#未完成的地方
1. 合约查询脚本目前未完成,代码中采用了contracts_query.js这个名称,后续脚本名称需要与之对应,参见application/libraries/Node.php