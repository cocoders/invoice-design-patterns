<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// replace with mechanism to retrieve EntityManager in your app
$paths = [__DIR__ . "/../src/Invoice/Adapter/Doctrine/config/mapping"];
$isDevMode = false;

$config = [
    'db_user' => getenv('POSTGRES_USER'),
    'db_password' => getenv('POSTGRES_PASSWORD'),
    'db_database' => getenv('POSTGRES_DB'),
    'db_host' => getenv('POSTGRES_HOST'),
    'db_database_dsn' => getenv('POSTGRES_DSN'),
];

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_pgsql',
    'user'     => $config['db_user'],
    'password' => $config['db_password'],
    'dbname'   => $config['db_database'],
    'host'   => $config['db_host'] ?? 'postgres',
);

$doctrineConfig = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $doctrineConfig);
