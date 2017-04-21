## Installation
```
composer require oueng/yii2-wechat
```

## Configuration

Add the SDK as a yii2 application `component` in the `config/main.php`:

```php

'components' => [
	// ...
	'wechat' => [
		'class' => 'maxwen\easywechat\Wechat',
		// 'userOptions' => []  # user identity class params
		// 'sessionParam' => '' # wechat user info will be stored in session under this key
		// 'returnUrlParam' => '' # returnUrl param stored in session
	],
	// ...
]
```