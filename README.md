Yii2 Mutex Filter
===========================
MutexFilter is an action filter relies on Yii2 Mutex component that apply mutex on controller's actions.

The Mutex component allows mutual execution of concurrent processes in order to prevent "race conditions". See [Yii2 Mutex](https://github.com/yiisoft/mutex/blob/master/README.md) document.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run console command

```
composer require sadi01/yii2-mutex-filter "*"
```

Or add the package to the `require` section of your `composer.json` file:

```json
{
    "require": {
      "sadi01/yii2-mutex-filter": "*"
    }
}
```

then run `composer update`.

Usage
-----
Config Yii2 Mutex component

```php
[
    'components' => [
        'mutex' => [
            'class' => 'yii\mutex\FileMutex'
        ],
    ],
]
```
Then, simply use MutexFilter in your controller's behaviors :

```php
public function behaviors()
{
    return [
        'mutexFilter' => [
            'class' => \sadi01\mutexFilter\MutexFilter::class,
            'mutexKeyPostfix' => 'anyString', // Optional
            'exceptActions' => ['index', 'view'] // Exclude some actions
        ]
    ];
}
```