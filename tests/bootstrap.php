<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}


putenv('APP_ENV=test');
putenv('DATABASE_URL=mysql://root:4289@localhost:3306/dev4_test');

// Include Symfony's autoloader
require __DIR__.'/../vendor/autoload.php';