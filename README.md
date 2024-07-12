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