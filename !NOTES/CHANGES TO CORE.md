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
Run command `composer update`