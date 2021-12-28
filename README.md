# yii2-bd-translate 基于百度的翻译接口 SDK for Yii2 组件。

## 安装

```sh
$ composer require saviorlv/yii2-bd-translate
```

## 配置

在使用本扩展之前，你需要去 [百度翻译开放平台](http://fanyi-api.baidu.com/api/trans/product/apidoc) 注册账号，然后创建应用，获取应用的 API Key。

## 使用

> 在 config/main.php 配置文件中定义 component 配置信息

```php
'components' => [
  .....
  "bdTrans" => [
        'class' => 'Saviorlv\Baidu\Translate',
        'app_id' => 'xxx',
        'sec_key' => 'xxxxx'
    ],
  ....
]

```

### 获取

```php
$response = Yii::$app->bdTrans->translate("基于百度的翻译接口", 'zh', 'en');;
```

返回示例：

```array
[
  "code"=> "58000",
  "data"=> [ "client_ip"=> "58.38.220.180" ],
  "msg"=> "客户端IP非法"
]
```

```array
[
  "code"=> "0",
  "msg"=> "请求成功",
  "data"=> [
        "from"=> "zh",
        "to"=> "en",
        "trans_result"=> [
            "src"=> "xxxxxxxwww",
             "dst"=> "Xxxxxxxwww"
             ]
    ]
]
```

### 参数说明

```
array  public function translate($query, $from, $to)
public $langue = ['zh', 'en', 'yue', 'wyw', 'jp', 'kor', 'fra', 'spa', 'th', 'ara', 'ru', 'pt', 'de', 'it', 'el', 'nl', 'pl', 'bul', 'est', 'dan', 'fin', 'cs', 'rom', 'slo', 'swe', 'hu', 'cht', 'vie'];

```

## 参考

[百度翻译开放平台](http://fanyi-api.baidu.com/api/trans/product/apidoc)

## 感谢

> 非常感谢 [Yii](https://www.yiiframework.com/)

## License

MIT
