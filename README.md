## 运行环境

- php(ThinkPHP **V5.1.34 LTS**)、mysql、nginx

- 配置nginx时需要开启对php的path_info支持：

  ```nginx
          location ~ \.php(.*)$ {
              fastcgi_pass 127.0.0.1:9000;
              fastcgi_index index.php;
              fastcgi_split_path_info ^((?U).+\.php)(/?.+)$;
              fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
              fastcgi_param PATH_INFO $fastcgi_path_info;
              fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
              include fastcgi_params;
          }
  ```

## 预约系统后台API文档

- 所有API的路径均为这种模式:`服务器地址/控制器名/函数名`
- 未特别说明，HTTP METHOD均为`POST`

- 所有API的返回均为这种格式

  ```json
  {
      "status":bool,		//表示请求成功与否
      "errCode":int,		//错误代码
      "errMsg":string,	//错误信息
      "data":string			//可能有，表示返回的数据
  }
  ```

- 错误代码和错误信息对应表

|   错误代码    |      错误信息      |              说明              |
| :-----------: | :----------------: | :----------------------------: |
|      200      |      操作成功      |               无               |
|      201      |    身份验证成功    |               无               |
|      202      |       已登陆       |               无               |
|      11       |   非法的参数传递   |  出现此错误请仔细检查接口文档  |
|      12       |       未登陆       |               无               |
|      13       |  用户名或密码错误  |               无               |
|      14       |    不可重复报名    |               无               |
|      15       |    该操作需要id    | 执行更新或者删除操作需要提供id |
|      16       | 该沙龙报名人数已满 |               无               |
|      17       |    该沙龙不存在    |               无               |
| $e->getCode() |  $e->getMessage()  |           数据库报错           |



# User控制器

功能：用于处理普通用户操作，例如预约教师、预约教室、辅导员报名等

## order

- 功能：用于提交预约信息

- Request:

  - `"type" = "person"`

    ```json
    {
      	"name": string,							//学生姓名
      	"id": string, 							//学生学号或者工资号
      	"school": string,						//学院名称
      	"person_id": int,				//讲师id
      	"order_date": string,				//预约日期，YYYY-MM-DD格式
      	"order_period": int,				//预约时间段，暂定为0表示中午，1～11表示第一节课到第十一节课
      	"phone_number": string,			//联系方式
      	"type": string,							//表示预约教室还是预约教师
      	"open_id": string
    }
    ```

  - `"type" = "room"`

    ```json
    {
      	"name": string,							//学生姓名
      	"id": string, 							//学生学号或者工资号
      	"school": string,						//学院名称
      	"room_usage": string,				//预约用途
      	"order_date": string,				//预约日期，YYYY-MM-DD格式
      	"order_period": int,				//预约时间段，暂定为0表示中午，1～11表示第一节课到第十一节课
      	"phone_number": string,			//联系方式
      	"type": string,							//表示预约教室还是预约教师
      	"open_id": string
    }
    ```

  - `"type" = "team"`

    ```json
    {
      	"name": string,							//学生姓名
      	"id": string, 							//学生学号或者工资号
      	"school": string,						//学院名称
      	"team_id": int,					//预约讲师团id
      	"order_date": string,				//预约日期，YYYY-MM-DD格式
      	"order_period": int,				//预约时间段，暂定为0表示中午，1～11表示第一节课到第十一节课
      	"phone_number": string,			//联系方式
      	"type": string,							//表示预约教室还是预约教师
      	"open_id": string
    }
    ```

- Return:

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功"
  }
  ```

## signUp

- 功能：用于提交辅导员报名

- Request:

  ```json
  {
    	"id":	string,					 //辅导员工资号
    	"name": string,				 //辅导员姓名
    	"salonId": int,				 //要报名的沙龙id
    	"open_id":string
  }
  ```

- Return:

  - 成功：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功"
    }
    ```

  - 失败：

    ```json
    {
        "status": false,
        "errCode": 14,
        "errMsg": "不可重复报名"
    }
    ```

    

## getHistory

- 功能：获取个人的历史操作记录（包括预约教师、预约教室、预约讲师团、报名辅导员沙龙）

- Request:

  ```json
  {
    "id or open_id": string						//可选的键名有"id"或者"open_id"，传入的键名会决定搜索时蚕蛹什么作为搜索依据
    
  }
  ```

- Return:

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功",
      "data": {
          "PersonOrderHistory": [
              {
                  "order_id": 1,
                  "name": "RioChen",
                  "id": "2016060201029",
                  "school": "计算机",
                  "phone_number": "15525881104",
                  "order_date": "2019-04-12",
                  "order_period": [
                      1,
                      2,
                      3,
                      4
                  ],
                  "time": "2019-04-25 14:27:42",
                  "status": 0
              }
          ],
          "TeamOrderHistory": [
              {
                  "order_id": 7,
                  "name": "RioChen",
                  "id": "2016060201029",
                  "team_id": 2,
                  "school": "计算机",
                  "phone_number": "15525881104",
                  "order_date": "2019-04-12",
                  "order_period": [
                      1,
                      2,
                      3,
                      4
                  ],
                  "time": "2019-04-25 14:29:48",
                  "status": 0
              }
          ],
          "RoomOrderHistory": [
              {
                  "order_id": 2,
                  "name": "RioChen",
                  "id": "2016060201029",
                  "school": "计算机",
                  "room_usage": "自习",
                  "phone_number": "15525881104",
                  "order_date": "2019-04-12",
                  "order_period": "[1, 2, 3, 4]",
                  "status": 0,
                  "time": "2019-04-17 15:44:21"
              }
          ],
          "TeacherSignUpHistory": [
              {
                  "sign_up_id": 3,
                  "id": "2016060201029",
                  "teacher_name": "riowu",
                  "time": "2019-04-18 15:30:35",
                  "salon_id": 2
              }
          ]
      }
  }
  ```

## speaker

- 功能：获取所有讲师信息

- HTTP METHOD:POST

- Request:

  ```json
  {
    	"team_id": int				//讲师所属的讲师团id
  }
  ```

- Return：

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功",
      "data": [
          {
              "id": 8,
              "name": "吴桐",
              "team_id": 2,
              "images": [
                  "1234",
                  "123"
              ],
              "description": "hehe"
          }
      ]
  }
  ```

## salon

- 功能：获取所有沙龙信息

- HTTP METHOD:GET

- Return:

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功",
      "data": [
          {
              "id": 1,
              "start_time": "2019-04-12 00:00:00",
              "end_time": "2019-04-26 00:00:00",
              "location": "品学楼",
              "speaker": "吴桐",
              "title": "大学生怎么能\b谈恋爱呢",
              "images": [
                  "123",
                  "123"
              ],
              "capacity": 1,
              "count": 1
          }
      ]
  }
  ```

## team

- 功能：获取所有讲师团信息

- HTTP METHOD:GET

- Return:

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功",
      "data": [
          {
              "id": 2,
            	"name": 12,
              "description": "哈哈哈"
          }
      ]
  }
  ```

## person

- 功能：获取所有非讲师团讲师信息

- HTTP METHOD：GET

- Return：

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功",
      "data": [
          {
              "id": 1,
              "name": "吴桐",
              "images": [
                  "1",
                  "2"
              ],
              "description": "123"
          }
      ]
  }
  ```

## getTime

- 功能：获取已经被预约的时间段

- Request:

  ```json
  {
    	”type“:	string,				//"room"表示获取教室预约信息，"person"表示获取教师预约信息，"teacher"表示获取辅导员报名信息
    	"date":	string				//日期
  }
  ```

- Return:

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功",
      "data": {
          "order_period": [
              1,
              2,
              3,
              4,
              6,
              7
          ]
      }
  }
  ```


## delete

- 功能：删除某一条信息

- Request:

  ```json
  {
    	"type": string,				//type可选值：”person","room","team","teacher_sign_up"
    	"id": int							//预约信息的id
  }
  ```

- Return:

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功"
  }
  ```



# Admin控制器

- 功能：所有需要管理员权限才能进行的操作都在此控制器里，包括审核预约信息，沙龙信息的增删改查，讲师信息的增删改查，微党课小组的增删改查等

- 特别说明：此控制器内的所有方法，都必须登陆之后才能调用，否则会返回

  ```json
  {
      "status": false,
      "errCode": 12,
      "errMsg": "未登录"
  }
  ```

  

## changeStatus

- 功能：用于更改某一条预约的审核状态。

- Request:

  ```json
  {
  		"id": string, 		//用于唯一标识某一条预约信息
    	"status": int,  	//要更改的状态,-1表示未通过，1表示通过，0表示未审核
    	"type": string		//"room"表示审核教室预约信息，"person"表示审核教师预约信息
  }
  ```

- Return:

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功"
  }
  ```

## getList

- 功能：获取预约信息，按时间降序排列

- Request:

  ```json
  {
  		"type": string,		//"room"表示获取教室预约信息，"person"表示获取教师预约信息，"teacher"表示获取辅导员报名信息
  }
  ```

- Return:

  - 获取教室预约信息:

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功",
        "data": [
            {
                "order_id": 2,
                "name": "RioChen",
                "id": "2016060201029",
                "school": "计算机",
                "room_usage": "自习",
                "phone_number": "15525881104",
                "order_date": "2019-04-12",
                "order_period": "[1, 2, 3, 4]",
                "status": -1,
                "time": "2019-04-17 15:44:21"
            }
        ]
    }
    ```

  - 获取教师预约信息：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功",
        "data": [
            {
                "order_id": 3,
                "name": "RioChen",
                "person_id": 1,
                "id": "2016060201029",
                "school": "计算机",
                "phone_number": "15525881104",
                "order_date": "2019-04-12",
                "order_period": [
                    1,
                    2,
                    3,
                    4,
                    6,
                    7
                ],
                "time": "2019-04-25 15:02:24",
                "status": 0
            }
        ]
    }
    ```

    

  - 获取讲师团预约信息:

    ```
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功",
        "data": [
            {
                "order_id": 5,
                "name": "RioChen",
                "id": "2016060201029",
                "team_id": 1,
                "school": "计算机",
                "phone_number": "15525881104",
                "order_date": "2019-04-12",
                "order_period": "[1, 2, 3, 4]",
                "time": "2019-04-18 15:37:04",
                "status": -1
            }
        ]
    }
    ```

  - 获取辅导员报名信息:

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功",
        "data": [
            {
                "sign_up_id": 7,
                "id": "2016060201011",
                "teacher_name": "riowu",
                "time": "2019-04-18 16:35:41",
                "salon_id": 1
            },
            {
                "sign_up_id": 6,
                "id": "2016060201018",
                "teacher_name": "riowu",
                "time": "2019-04-18 16:35:03",
                "salon_id": 1
            }
        ]
    }
    ```

## salon

- 功能：实现辅导员沙龙信息的增删改查

- Request:

  - 增:

    ```json
    {
      	"start_time":	string,			//满足2019-04-12 00:00:00这种格式的时间
      	"end_time":	string,				//满足2019-04-12 00:00:00这种格式的时间
      	"location": string,				//沙龙举办的地点
      	"speaker": string,				//主讲人
      	"title": string,					//沙龙主题
      	"images": array,					//数组，存放内容为base64处理的图片
      	"capacity": int,					//容量
      	"count": int,							//已报名人数
      	"type": "add"							//表示此操作为增加操作
    }
    ```

  - 删:

    ```json
    {
      	"id":	string,							//要删除的沙龙id
      	"type": "delete"					//表示此操作为删除操作
    }
    ```

  - 改：特别说明：可以修改一部分，也可以全部修改，取决于传入的数据

    ```json
    {
      	"start_time":	string,			//满足2019-04-12 00:00:00这种格式的时间
      	"end_time":	string,				//满足2019-04-12 00:00:00这种格式的时间
      	"location": string,				//沙龙举办的地点
      	"speaker": string,				//主讲人
      	"title": string,					//沙龙主题
      	"images": array,					//数组，存放内容为base64处理的图片
      	"capacity": int,					//容量
      	"count": int,							//已报名人数
      	"id": int,								//要更新的沙龙id
      	"type": "update"					//表示此操作为增加操作
    }
    ```

  - 查：

    ```json
    {
      	"type": "get"							//表示此操作为查找操作
    }
    ```

    

- Return:

  - 增，删，改：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功"
    }
    ```

  - 查：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功",
        "data": [
            {
                "id": 1,
                "start_time": "2019-04-12 00:00:00",
                "end_time": "2019-04-26 00:00:00",
                "location": "品学楼",
                "speaker": "吴桐",
                "title": "大学生怎么能\b谈恋爱呢",
                "images": "[\"123\", \"123\"]",
                "capacity": 1,
                "count": 1
            },
            {
                "id": 3,
                "start_time": "2019-04-12 00:00:00",
                "end_time": "2019-04-26 00:00:00",
                "location": "品学楼",
                "speaker": "吴桐",
                "title": "大学生可以谈恋爱",
                "images": "[\"123\", \"123\"]",
                "capacity": 20,
                "count": 0
            },
            {
                "id": 4,
                "start_time": "2019-04-12 00:00:00",
                "end_time": "2019-04-26 00:00:00",
                "location": "品学楼",
                "speaker": "吴桐",
                "title": "啦啦啦",
                "images": "[\"123\", \"123\"]",
                "capacity": 20,
                "count": 0
            }
        ]
    }
    ```

## speaker

- 功能：微党课讲师的增删改查

- Request:

  - 增:

    ```json
    {
    		"name": string,					//讲师姓名
      	"team_id": int,					//该讲师所属的队伍
      	"images":	array,				//数组，存放内容为base64处理的图片
      	"description": string,	//对该讲师的描述
      	"type": "add"					  //表示该操作为增加操作
    }
    ```

    

  - 删：

    ```json
    {
      	"id":	string,							//要删除的讲师id
      	"type": "delete"					//表示此操作为删除操作
    }
    ```

  - 改: 特别说明：可以修改一部分，也可以全部修改，取决于传入的数据

    ```json
    {
    		"name": string,					//讲师姓名
      	"team_id": int,					//该讲师所属的队伍
      	"images":	array,				//数组，存放内容为base64处理的图片
      	"description": string,	//对该讲师的描述
      	"id": string,						//要更新的讲师id
      	"type": "update"				//表示该操作为增加操作
    }
    ```

  - 查:

    ```json
    {
      	"type": "get",						//表示此操作为查找操作
      	"team_id" : int						//获取某个讲师团下的所有讲师
    }
    ```

- Return：

  - 增，删，改：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功"
    }
    ```

  - 查:

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功",
        "data": [
            {
                "id": 1,
                "name": "吴桐",
                "team_id": 1,
                "images": "[\"1243\", \"2323\"]",
                "description": "一个瓜皮"
            },
            {
                "id": 5,
                "name": "吴桐",
                "team_id": 1,
                "images": "[\"1243\", \"2323\"]",
                "description": "3131"
            }
        ]
    }
    ```

## team

- 功能：微党课小组的增删改查

- Request:

  - 增：

    ```json
    {
      	"type": "add",						//表示该操作为增加操作
      	"name": string,						//小组名字
      	"description": string			//小组的总体描述
    }
    ```

  - 删：特别说明：删除一个组，会删掉这个组所有的讲师

    ```json
    {
      	"type": "delete",					//表示该操作为删除操作
      	"id": int									//要删除的小组id
    }
    ```

  - 改：

    ```json
    {
      	"type": "update",					//表示该操作为更新操作
        "name": string,						//小组名字
      	"description": string,		//小组的总体描述
      	"id": int									//要更新的小组id
    }
    ```

  - 查：

    ```json
    {
      	"type": "get"							//表示此操作为查找操作
    }
    ```

- Return:

  - 增，删，改：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功"
    }
    ```

  - 查：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功",
        "data": [
            {
                "id": 2,
                "name": 12,
                "description": "哈哈哈"
            }
        ]
    }
    ```

## person

- 功能：讲师的增删改查

- Request:

  - 增：

    ```json
    {
    		"name": string,					//讲师姓名
      	"images":	array,				//数组，存放内容为base64处理的图片
      	"description": string,	//对该讲师的描述
      	"type": "add"					  //表示该操作为增加操作
    }
    ```

  - 删：特别说明：删除一个组，会删掉这个组所有的讲师

    ```json
    {
      	"type": "delete",					//表示该操作为删除操作
      	"id": int									//要删除的讲师id
    }
    ```

  - 改：

    ```json
    {
    		"name": string,					//讲师姓名
      	"images":	array,				//数组，存放内容为base64处理的图片
      	"description": string,	//对该讲师的描述
      	"id": string,						//要更新的讲师id
      	"type": "update"				//表示该操作为增加操作
    }
    ```

  - 查：

    ```json
    {
      	"type": "get"							//表示此操作为查找操作
    }
    ```

- Return:

  - 增，删，改：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功"
    }
    ```

  - 查：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功",
        "data": [
            {
                "id": 1,
                "name": "吴桐",
                "images": [
                    "1",
                    "2"
                ],
                "description": "123"
            }
        ]
    }
    ```

## delete

- 功能：删除某一条信息

- Request:

  ```json
  {
    	"type": string,				//type可选值：”person","room","team","teacher_sign_up"
    	"id": int							//预约信息的id
  }
  ```

- Return:

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功"
  }
  ```

# Account控制器

- 功能：用于处理用户权限认证的相关信息

## logIn

- 功能：用于检验用户输入的用户名和密码

- Request：

  ```json
  {
    	"userName": string,		//用户名
    	"password": string		//md5哈希加密之后的密码
  }
  ```

- Return：

  - 登陆成功:

    ```json
    {
        "status": true,
        "errCode": 201,
        "errMsg": "身份验证成功"
    }
    ```

  - 登陆失败:

    ```json
    {
        "status": false,
        "errCode": 13,
        "errMsg": "用户名或密码错误"
    }
    ```

## signUp

- 功能：用于注册用户

- Request:

  ```json
  {
    	"userName": string,		//用户名
    	"password": string		//md5哈希加密之后的密码
  }
  ```

- Return：

  - 注册成功：

    ```json
    {
        "status": true,
        "errCode": 200,
        "errMsg": "操作成功"
    }
    ```

## logOut

- 功能：用于注销用户

- HTTP METHOD：GET

- Return：

  ```json
  {
      "status": true,
      "errCode": 200,
      "errMsg": "操作成功"
  }
  ```


## isLog

- 功能：用于判断是否登陆

- HTTP METHOD：GET

- Return：

  - 已登陆：

    ```json
    {
        "status": true,
        "errCode": 202,
        "errMsg": "已登陆"
    }
    ```

  - 未登陆：

    ```json
    {
        "status": false,
        "errCode": 12,
        "errMsg": "未登录"
    }
    ```
