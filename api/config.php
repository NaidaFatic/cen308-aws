<?php

class Config
{
    const DATE_FORMAT = "Y-m-d H:i:s";

    public static function DB_HOST()
    {
        return Config::get_env("DB_HOST", "localhost");
    }
    public static function DB_USERNAME()
    {
        return Config::get_env("DB_USERNAME", "root");
    }
    public static function DB_PASSWORD()
    {
        return Config::get_env("DB_PASSWORD", "tanzmitmir1488");
    }
    public static function DB_SCHEME()
    {
        return Config::get_env("DB_SCHEME", "cen308-app");
    }
    public static function DB_PORT()
    {
        return Config::get_env("DB_PORT", "3306");
    }

    const JWT_SECRET = "y4KvQcZVqn3F7uxQvcFk";
    const JWT_TOKEN_TIME = 604800;

    public static function get_env($name, $default)
    {
        return isset($_ENV[$name]) && trim($_ENV[$name]) != '' ? $_ENV[$name] : $default;
    }
}
