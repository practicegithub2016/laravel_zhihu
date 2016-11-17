版本v1
##常规api调用原则
- 所有的api都以/api开头
- api分两部分,如/api/part1/part2
- part1为模块名,如 user,question
- part2为行为,如add,change
- 传参数方式基本用get,如/api/login?username=test1&password=123456
***
#api
***
##版本api
## /api/version
****
##注册api
## /api/signup
- 参数
  - username 用户名 必须
  - password 密码 必须
***
##登录api
## /api/login
 - 参数
  - username 用户名 必须
  - password 密码 必须
***
##登出api
## /api/logout
***
##更改密码api
##/api/user/change_password
 - 权限:已登录
 - 参数
   -  old_password(旧密码) 必须
   -  new_password(新密码) 必须
***
##找回密码api
## /api/user/reset_password
 - 参数
  - phone 手机号码 必须
***
##验证找回密码api
## /api/user/validate_reset_password
- 参数
 - phone 手机号码 必须
 - phone_captcha 手机验证码 必须
 - new_password  新密码 必须
***
##查看会员api
## /api/user/read
- 参数
 - id     用户id 必须
***
##是否已经登录api
## api/is_logged_in
***
##添加问题api
## /api/question/add
- 权限
  - 已登录
- 参数
 - title 问题标题 必须
 - desc 问题描述 可选
***
##更改问题api
## /api/question/change
- 权限:已登录，且为问题所有者
  - 参数
    - id 问题id 可选
    - title 问题标题 可选
    - desc 问题描述 可选 
***
##查看问题api
## /api/question/read
- 权限:已登录
 - 参数
    - id 问题id 可选
    - page 页码 可选
    - limit 每页显示 可选
***
##删除问题api
## /api/question/remove
  - 权限:已登录，且为问题所有者
    - 参数
      - id 问题id 必须
***
##添加回答api
## /api/answer/add
- 权限:已登录
  - 参数
    - question_id 问题id 必须
    - content 内容 必须
***
##更改回答api
## /api/answer/change
- 权限 已登录，且为回答所有者
- 参数
   - id 答案id 必须 
  - content 内容 必须
***
##查看回答api
## /api/answer/read
- 参数
  - id 回答id 可选 
***
##对回答投票api
## /api/answer/vote
- 权限:已登录
- 参数 
    - answer_id 回答id
    - vote 1为同意 2为反对
***
##添加评论api
## /api/comment/add
  - 权限:已登录
  - 参数
    - question_id 问题id
    - answer_id 回答id
    - reply_to 评论id 
    - question_id or answer_id or reply_to 至少其中一个参数,如果参数同时有值,question_id 优先于 answer_id 优先于 reply_to
    - content 内容 必须
***
##查看评论api
## /api/comment/read
- 参数
  - question_id 问题id
  - answer_id 答案id 
  - 参数二选一,至少一个,question_id 优先于 answer_id
***
##删除评论api
## /api/comment/remove
- 权限:已登录且评论所有者
  - 参数
    - id 评论id 必须
*** 
##问题答案时间线api
## /api/timeline
- 参数
  - page 页码 
  - limit 每页显示