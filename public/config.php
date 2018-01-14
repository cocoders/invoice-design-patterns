<?php include '../vendor/autoload.php' ?>
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




//$unitOfWork = new \Invoice\Adapter\Pdo\UnitOfWork();
//$users = new \Invoice\Adapter\Pdo\Domain\Users($connection, $unitOfWork);
//$transactionManager = new \Invoice\Adapter\Pdo\Application\TransactionManager($connection, $unitOfWork);
$registerUser = new \Invoice\Application\UseCase\RegisterUser(
    $transactionManager,
    $users,
    new \Invoice\Adapter\Pdo\Domain\UserFactory()
);
$editProfile = new \Invoice\Application\UseCase\EditProfile(
    $transactionManager,
    $users,
    new \Invoice\Adapter\Legacy\Domain\VatNumberFactory()
);

