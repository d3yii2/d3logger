## Installation

create Monolog logs in runtime directory

```bash
composer require d3yii2/d3logger dev-master
```



In configuration file define only path
```php 
        'myLoggel=r' => [
            'class' => 'd3logger\D3Monolog',
            'name' => 'myLogger',
            'fileName' => 'robotex',
            'directory' => 'devices',
            'maxFiles' => 7,
        ],
```

## Ussage

```php 
use d3logger\D3Monolog;

Yii::$app->myLogger->info('ok',['a','b']);

```
# Runtime viewer
## configure
To console config migration path add
```php
    '@vendor/d3yii2/d3logger/src/migrations',

```

## web connnfig module
```php
        'd3logger' => [
            'class' => 'd3logger\Module',
            'leftMenu' => 'company',
            'accessRoles' => [
                'D3loggerView' => [
                    'logging/sorting',  //directories
                    'logs',
                ],
            ]
        ],

```

## translation
```php
    'd3logger' => [
        'class' => 'yii\i18n\PhpMessageSource',
        'basePath' => '@vendor/d3yii2/d3logger/src/messages',
        'sourceLanguage' => 'en-US',
    ],

```

Left menu
```php
    [
        'label' => 'Logfaili',
        'icon' => 'bars',
        'type' => 'submenu',
        'url' => ['/d3logger/log-viewer'],
        'visible' => Yii::$app->user->can(D3loggerViewUserRole::NAME)
    ],

```
