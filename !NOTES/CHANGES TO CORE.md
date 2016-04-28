# Changes To Core

### 2016/04/23 Hide `password` field when output JSON


In GitHub fork `https://github.com/NicksonYap/sentinel.git`, `src/Users/EloquentUser.php`:
After `protected $fillable = [...]` add:

```php
    protected $hidden = [
        'password',
    ];
```
Commit on new branch named `hidepassword`.

In local `composer.json`

At line 4, from:

```json
    "require": {
        "php": ">=5.5.9",
        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2",
        "cartalyst/sentinel": "2.0.*",
        "dingo/api": "1.0.x@dev",
        "tymon/jwt-auth": "dev-develop",
        "barryvdh/laravel-cors": "^0.8.0"
    },
```
to

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/NicksonYap/sentinel.git"
        }
    ],
    "require": {
        "php": ">=5.5.9",
        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2",
        "cartalyst/sentinel": "dev-hidepassword",
        "dingo/api": "1.0.x@dev",
        "tymon/jwt-auth": "dev-develop",
        "barryvdh/laravel-cors": "^0.8.0"
    },
```
Run command `composer update cartalyst/sentinel`

### 2016/04/27 Dingo API Accept multiple Rate Limiting Keys

Implementation made in GitHub fork, [commit link](https://github.com/NicksonYap/dingo-api/commit/e6db8797603908fc3d3e4e240812b0923a3a41a0)

In local `composer.json`

At line 4, from:

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/NicksonYap/sentinel.git"
        }
    ],
    "require": {
        "php": ">=5.5.9",
        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2",
        "cartalyst/sentinel": "dev-hidepassword",
        "dingo/api": "1.0.x@dev",
        "tymon/jwt-auth": "dev-develop",
        "barryvdh/laravel-cors": "^0.8.0"
    },
```
to

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/NicksonYap/sentinel.git"
        },
        {
            "type": "vcs",
            "url": "https://github.com/NicksonYap/dingo-api.git"
        }
    ],
    "require": {
        "php": ">=5.5.9",
        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2",
        "cartalyst/sentinel": "dev-hidepassword",
        "dingo/api": "dev-multiRLK",
        "tymon/jwt-auth": "dev-develop",
        "barryvdh/laravel-cors": "^0.8.0"
    },
```

Copy `vendor/dingo/api/config.api.php` to `config/api.php`

Run command `composer update dingo/api`

### 2016/04/28 Dingo API custom throttles autoload

In local `composer.json`

Line 28, from:

```json
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "files": [
            "app/helpers.php"
        ]
    },
```
to

```json
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "files": [
            "app/helpers.php"
        ],
        "classmap": [
            "app/Http/Throttles/"
        ]
    },
```


Run command `composer dump-autoload`