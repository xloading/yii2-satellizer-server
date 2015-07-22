# Yii2 Satellizer Server

[**Satellizer**](https://github.com/sahat/satellizer) is a simple to use, end-to-end, token-based authentication module
for [AngularJS](http://angularjs.org) with built-in support for Google, Facebook,
LinkedIn, Twitter, Yahoo, Windows Live authentication providers, as well as Email and Password
sign-in. You are not limited to the sign-in options above, in fact you can add
any *OAuth 1.0* or *OAuth 2.0* provider by passing provider-specific information
during the configuration step.

[![Latest Stable Version](https://poser.pugx.org/wfcreations/yii2-satellizer-server/v/stable)](https://packagist.org/packages/wfcreations/yii2-satellizer-server) [![Total Downloads](https://poser.pugx.org/wfcreations/yii2-satellizer-server/downloads)](https://packagist.org/packages/wfcreations/yii2-satellizer-server) [![Latest Unstable Version](https://poser.pugx.org/wfcreations/yii2-satellizer-server/v/unstable)](https://packagist.org/packages/wfcreations/yii2-satellizer-server) [![License](https://poser.pugx.org/wfcreations/yii2-satellizer-server/license)](https://packagist.org/packages/wfcreations/yii2-satellizer-server)

Installation
------------

The preferred way to install this extension is through [composer](https://getcomposer.org/).

Either run

```bash
composer require "wfcreations/yii2-satellizer-server:*"
```

or add

```
"wfcreations/yii2-satellizer-server": "*",
```

to the `require` section of your `composer.json` file.

Usage
-----

To use this extension, simply add the following code in your application configuration:

```php

'components' => [
  'satellizer' => [
    'class' => \wfcreations\satellizer\Satellizer::className(),
    'identityClass' => \common\models\ar\Advertiser::className(),
    'tokenLifetime' => 2 * 7 * 24 * 60 * 60,
    'jwtKey' => 'jwtsecret',
    'facebook' => [
      'clientSecret' => 'facebookscecret',
    ],
  ],
  // ...
]

```

In your auth controller

```php

use yii\rest\Controller;

class AuthController extends Controller {

  public function actions() {
    return [
      'facebook' => [
        'class' => 'wfcreations\satellizer\actions\FacebookAction',
      ],
    ];
  }

  protected function verbs() {
    return [
      'facebook' => ['post', 'options'],
    ];
  }
  
  // ...

}
  
```

In others controllers that require authenticated user, just configure authenticator in behaviors:

```php

'authenticator' => [
  'class' => HttpBearerAuth::className(),
],

```

If you want get authenticated user:

```php

Yii::$app->user->identity

```

Provider supported
-----

- [X] Facebook
- [ ] Foursquare
- [ ] Github
- [ ] Google
- [ ] LinkedIn
- [ ] Via email and password
- [ ] Twitter



For more information about yii2 api see [yii2-app-api](https://github.com/wfcreations/yii2-app-api) and [Guide rest - Qick-start](http://www.yiiframework.com/doc-2.0/guide-rest-quick-start.html)
