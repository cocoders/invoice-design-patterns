<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// replace with mechanism to retrieve EntityManager in your app
$paths = array(__DIR__ . "/../src/Invoice/Adapter/Doctrine/config/mapping");
$isDevMode = false;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_pgsql',
    'user'     => $config['db_user'],
    'password' => $config['db_password'],
    'dbname'   => $config['db_database'],
    'host'   => $config['db_host'],
);

$config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);