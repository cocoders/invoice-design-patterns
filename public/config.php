<?php include '../vendor/autoload.php' ?>
<?php
    $config = [
        'db_user' => getenv('POSTGRES_USER'),
        'db_password' => getenv('POSTGRES_PASSWORD'),
        'db_database' => getenv('POSTGRES_DB'),
        'db_host' => getenv('POSTGRES_HOST'),
        'db_database_dsn' => getenv('POSTGRES_DSN'),
    ];
?>
<?php include './doctrine.php' ?>
<?php

$pages = [
    'dashboard' => [
        'icon' => 'fa-dashboard',
        'name' => 'Dashboard',
        'menu' => true
    ],
    'invoices' => [
        'icon' => 'fa-list',
        'name' => 'Invoices List',
        'menu' => true
    ],
    'invoice-add' => [
        'icon' => 'fa-file-text',
        'name' => 'Create new invoice',
        'menu' => true
    ],
    'invoice-edit' => [
        'icon' => 'fa-file-text',
        'name' => 'Edit invoice',
        'menu' => false
    ],
    'invoice-delete' => [
        'icon' => 'fa-file-text',
        'name' => 'Remove invoice',
        'menu' => false
    ],
    'user-profile' => [
        'icon' => 'fa-file-text',
        'name' => 'User profile',
        'menu' => false
    ]
];

try {
    $connection = new \PDO(
        $config['db_database_dsn'],
        $config['db_user'],
        $config['db_password'],
        [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (\PDOException $exception) {
    die ('Cannot connect to database: ' . $exception->getMessage());
}

$users = new \Invoice\Adapter\Doctrine\Domain\Users($entityManager);
$transactionManager = new \Invoice\Adapter\Doctrine\Application\TransactionManager($entityManager);
$registerUser = new \Invoice\Application\UseCase\RegisterUser(
    $transactionManager,
    $users,
    new \Invoice\Adapter\Doctrine\Domain\UserFactory()
);
$editProfile = new \Invoice\Application\UseCase\EditProfile(
    $transactionManager,
    $users,
    new \Invoice\Adapter\Legacy\Domain\VatNumberFactory()
);

