# K-Laravel_Creator

Hope this can help you quickly build API

## Instructions

On the basis of Laravel5.2, provide convenient database and the production of API interface

## Install

git clone git@github.com:mzkmzk/K_Laravel_Creator_Demo.git

composer install 

chmod -R 777 storage/framework/ storage/logs bootstrap/cache

cp .env.example .env //configuration .env 

php artisan key:generate

php artisan make:k_command

rm -rf .git

## Use

### Set Entity

```php
//in app/Entities/Creator_User_Entity.php
use K_Laravel_Creator\Entities\Base_Entity;

<php?

class Creator_User_Entity extends Base_Entity{

    public static $entity = [
        "User" => "用户"
    ];
    
    // if has on to many entity 
    public static $has_many = ['Creator_Activity'];

    /**
     * set_attribute 参数
     */
    public static function get_attribute(){
        $attribute = array();
        //wechat_id 为属性名 微信ID为字段说明 string 为该字段的类型, 可选类型有 "id" , "string" , "date_time" "url","int"
        $attribute['wechat_id'] = parent::set_attribute("微信ID","string");
        $attribute['login_sum'] = parent::set_attribute("登陆次数","int");
        $attribute['visit_password'] = parent::set_attribute("访问密码","string");
        $attribute['sina_uid'] = parent::set_attribute("新浪id","string");
        $attribute['sina_access_token'] = parent::set_attribute("新浪密钥","string");
        return array_merge(parent::get_attribute(),$attribute);
    }
}

```

## Add Entity to config 

In /config/creator.php

```php
<?php

return [
    'entities' => [
        'Creator_User'
    ]
];

```

## Creator Code and Datatbase table

`php artisan make:k_command`


## Result

### database table

![image](./Static/Images/database_table.png)

### API 

#### insert  

`http://url/v1/Creator_User_Controller/query?id=161`

```JSON
{
  "result": true,
  "data": [
    {
      "updated_at": "2017-06-02 07:53:32",
      "created_at": "2017-06-02 07:53:32",
      "id": 161,
      "patient_name": "11111",
      "creator_activity": []
    }
  ]
}
```

### update

`http://inner.journey.404mzk.com/v1/Creator_User_Controller/update?id=161&wechat_id=1`

```JSON
{
  "result": true
}
```

#### query

`http://url/v1/Creator_User_Controller/query?id=161`

```JSON
{
    "total": 1,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null,
    "from": 1,
    "to": 1,
    "data": [
        {
            "id": 161,
            "wechat_id": 1,
            ...
            "creator_activity": [
                {
                    "id": 337,
                    ...
                },
                {
                    "id": 338,
                    ...
                }
            ]
        }
    ]
}
```


### delete

`http://inner.journey.404mzk.com/v1/Creator_User_Controller/delete?id=161`

```JSON
{
  "result": true 
}
```

