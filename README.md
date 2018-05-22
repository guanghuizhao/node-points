# node-points

# 调整说明

1. 为V1.0版本提供了后台服务，后台使用PHP作为开发语言

# 接口说明

|接口名|功能|接口地址|完成度|请求参数|返回结果|返回结果示例| 
|-| :-: |:- |-:|
|提交反馈 | 提交课程打分及评论内容|/feedback|完成|stars(int)、content(string)|data:空|{"code":200,"message":ok,"data":{}}|
|抽奖 | 课堂抽Node积分，抽奖范围是1～200 | /giveaways |70%（使用js发送node积分部分未完成）|wechatid(string)|data中增加nodes字段，为node积分|{"code":200,"message":ok,"data":{"nodes":199}}|
|提交钱包地址 | 绑定用户钱包地址 |/student/address/|完成|wechatid(string)|data中增加address字段，为用户钱包地址|{"code":200,"message":ok,"data":{"address":"xxxfffxxxaaeee"}}|
|查看钱包地址| 获取用户的钱包地址|/student/address/|完成|wechatid(string)、address(string)|data:空|{"code":200,"message":ok,"data":{}}|
|注册|填写用户姓名、电话|/student/register|完成（还需获取用户头像信息，用作排行榜展示）|wechatid(string)、phone(string)、name(string)|data:空|{"code":200,"message":ok,"data":{}}|
|查看是否已注册|查看当前微信id的注册状态|/student/exist|完成|wechatid(string)|data:增加exist字段(boolean)|{"code":200,"message":ok,"data":{"exist":true}}|
|查看当前是否在课程时间|用来前端判断展示按钮是否可点击|/course|待完成|wechatid(string)|data:增加onCourse字段(boolean)|{"code":200,"message":ok,"data":{"onCourse":true}}|
|排名列表|查看全球用户榜||未完成|
|用户排名|查看当前用户排名||未完成|

