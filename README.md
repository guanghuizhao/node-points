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
|绑定用户信息|填写用户姓名、电话|/student/bindUser|完成|sessionid(string)、phone(string)、name(string)|data:空|{"code":200,"message":ok,"data":{}}|
|绑定钱包地址|填写用户Qtum钱包地址|/student/bindAddress|完成|sessionid(string)、address(string)|data:空|{"code":200,"message":ok,"data":{}}|
|获取用户信息|获取用户信息,姓名、头像、钱包地址等|/student/getInfo|完成|sessionid(string)|data:空|{"code":200,"message":"ok","data":{"name":"zhangsan","phone":"18700183","wallet_address":"123a18sdaf8dg7s8g0fg8ag","profile":"url","comments":null,"created_at":"2018-05-31 18:06:18","updated_at":"2018-05-31 18:50:21"}}|
|查看当前是否在课程时间|用来前端判断展示按钮是否可点击|/course/isOnCourse|完成|无|data:增加onCourse字段(boolean)|{"code":200,"message":ok,"data":{"onCourse":true}}|
|排名列表|查看全球用户榜|/nodeRank/rankPage|完成|start(int)默认0,num(int)默认20|data包含list和total|{"code":200,"message":"ok","data":{"list":[{"name":"\u4f55\u7476","profile":null,"total":"120"},{"name":"\u674e\u5b8f\u4f1f","profile":null,"total":"100"},{"name":"\u96f7\u529b","profile":"url_test","total":"10"}],"total":3}}|
|用户排名|查看当前用户排名|/nodeRank/selfRank|完成|sessionid(string)|data包含排名|{"code":200,"message":"ok","data":{"self_rank":1}}|

# TODO

1. 定时获取所有用户的排行榜信息,存入数据库（每小时一次）。
2. 生成二维码和签到功能

