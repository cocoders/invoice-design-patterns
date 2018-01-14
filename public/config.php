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

$users = new \Invoice\Adapter\Doctrine\Domain\Users(
    new \Invoice\Adapter\Doctrine\Domain\UserFactory(),
    $entityManager,
    $entityManager->getClassMetadata(\Invoice\Adapter\Doctrine\Domain\User::class)
);
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

