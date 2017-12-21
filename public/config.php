<?php use Invoice\Adapter\Pdo\Application\TransactionManager;
use Invoice\Adapter\Pdo\Domain\UserFactory;
use Invoice\Adapter\Pdo\Domain\UserRepository;
use Invoice\Application\UnitOfWork;
use Invoice\Application\UseCase\EditProfile;
use Invoice\Application\UseCase\RegisterUser;
use Invoice\Domain\DefaultProfileFactory;

require_once '../vendor/autoload.php'; ?>
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
        $config['db_password']
    );
} catch (\PDOException $exception) {
    die ('Cannot connect to database: ' . $exception->getMessage());
}

$profileFactory = new DefaultProfileFactory();
$userFactory = new UserFactory($profileFactory);
$unitOfWork = new UnitOfWork();
$transactionManager = new TransactionManager($connection, $unitOfWork);
$userRepository = new UserRepository($connection, $userFactory, $unitOfWork);
$registerUser = new RegisterUser(
    $transactionManager,
    $userRepository,
    $userFactory
);
$editProfile = new EditProfile(
    $transactionManager,
    $userRepository,
    $profileFactory
);
