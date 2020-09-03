<?php


use App\User;

function site_direction()
{
    return app()->getLocale() == 'ar' ? 'rtl' : 'ltr';
}

function current_user()
{
    if (app()->environment('local')) {
        // The environment is local
        return auth()->user() ?? User::first();
    }
    return auth()->user();
}

function current_uid()
{
    return current_user()->id;
}

function current_locale()
{
    return app()->getLocale();
}

function pg_connection()
{
    return env('DB_CONNECTION') !== 'pgsql' ? [] : [
        'driver' => 'pgsql',
        'port' => parse_url(getenv("DATABASE_URL"))["port"],
        'host' => parse_url(getenv("DATABASE_URL"))["host"],
        'database' => substr(parse_url(getenv("DATABASE_URL"))["path"], 1),
        'username' => parse_url(getenv("DATABASE_URL"))["user"],
        'password' => parse_url(getenv("DATABASE_URL"))["pass"],
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'prefer',
    ];
}
