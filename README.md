## Installation
```
composer require oueng/yii2-wechat
```

## Configuration

Add the SDK as a yii2 application `component` in the `config/main.php`:

```php

'components' => [
	'wechat' => [
		'class' => 'oueng\wechat\Wechat',
		// 'wechatOptions' => []  # 不配置默认获取Yii::$app->params['WECHAT']，如配置则使用此配置
		// 'sessionParam' => '' # wechat user info will be stored in session under this key
		// 'returnUrlParam' => '' # returnUrl param stored in session
	],
]
```