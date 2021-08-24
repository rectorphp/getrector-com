<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Dotenv\Dotenv;

// Load cached env vars if the .env.local.php file exists
// Run "composer dump-env prod" to create it (requires symfony/flex >=1.2)
$env = @include dirname(__DIR__) . '/.env.local.php';
if (is_array($env)) {
    $_SERVER += $env;
    $_ENV += $env;
} else {
    // load all the .env files
    (new Dotenv())->loadEnv(dirname(__DIR__) . '/.env');
}

$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null ?: 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? $_SERVER['APP_ENV'] !== 'prod';
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int) $_SERVER['APP_DEBUG'] || filter_var(
    $_SERVER['APP_DEBUG'],
    FILTER_VALIDATE_BOOLEAN
) ? '1' : '0';

if (isset($_COOKIE['XDEBUG_TRACE']) && ! empty($_ENV['DEBUG_COOKIE']) && $_COOKIE['XDEBUG_TRACE'] === $_ENV['DEBUG_COOKIE']) {
    $_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = 1;
    $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'dev';
}

function getUserIpAddr(): string
{
    return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
}

// @see https://github.com/liip/LiipFunctionalTestBundle/issues/110#issuecomment-201908411
AnnotationReader::addGlobalIgnoredName('dataProvider');
