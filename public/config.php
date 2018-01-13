<?php include '../vendor/autoload.php' ?>
<?php

$config = [
    'db_user' => getenv('POSTGRES_USER'),
    'db_password' => getenv('POSTGRES_PASSWORD'),
    'db_database_dsn' => getenv('POSTGRES_DSN')
];

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

$unitOfWork = new \Invoice\Adapter\Pdo\UnitOfWork();
$users = new \Invoice\Adapter\Pdo\Domain\Users($connection, $unitOfWork);
$transactionManager = new \Invoice\Adapter\Pdo\Application\TransactionManager($connection, $unitOfWork);
$registerUser = new \Invoice\Application\UseCase\RegisterUser(
    $transactionManager,
    $users,
    new \Invoice\Adapter\Pdo\Domain\UserFactory()
);

