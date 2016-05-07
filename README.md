# Lumen + Dingo API + JWT-auth

This is based on a [forked repository](https://github.com/edgji/lumengular)

### Installation:

1. `git clone https://github.com/NicksonYap/LumenDingoJWTauth.git`
2. `cd LumenDingoJWTauth`
3. `composer install`
4. Duplicate `.env.example` as `.env`
5. Edit `.env` and configure secrets using [a generator](http://passwordsgenerator.net/)
6. Set up databases and edit configuration in `.env`
7. `php artisan migrate`

### Features:

* User authentication and role based permissions (middleware) using [Cartalyst/Sentinel 2.0](https://github.com/cartalyst/sentinel)
* API package using [dingo/api](https://github.com/dingo/api)
* API authentication based on [JWT-auth](https://github.com/tymondesigns/jwt-auth)
