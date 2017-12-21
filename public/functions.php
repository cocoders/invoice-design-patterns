<?php

use Invoice\Adapter\Legacy\Application\UseCase\EditProfile\Responder as EditProfileResponder;
use Invoice\Adapter\Legacy\Application\UseCase\RegisterUser\Errors;
use Invoice\Adapter\Legacy\Application\UseCase\RegisterUser\Responder;
use Invoice\Application\UseCase\RegisterUser;
use Invoice\Application\UseCase\EditProfile;

$loginErrors = [];
$registerErrors = new Errors();
$invoiceFormErrors = [];
$profileFormErrors = [];

function login() {
    global $connection, $loginErrors;

    if (empty($_POST['email'])) {
        $loginErrors['email'] = "Email field was empty.";
    } elseif (empty($_POST['password'])) {
        $loginErrors['password'] = "Password field was empty.";
    } elseif (!empty($_POST['email']) && !empty($_POST['password'])) {

        $stmt = $connection->prepare('SELECT id, email, password_hash FROM users WHERE email = :email');
        $stmt->execute(['email' => $_POST['email']]);
        $users = $stmt->fetchAll();

        if (count($users) == 1) {
            $user = $users[0];

            if (password_verify($_POST['password'], $user['password_hash'])) {
                $_SESSION['loggedInUser'] = $user;
                header('Location: /index.php?page=dashboard');
                exit;
            }
        }
        $loginErrors['email'] = 'Login or password is invalid.';
    }
}

function register()
{
    global $registerUser, $registerErrors;

    $registerUser->registerResponder(new Responder($registerErrors));
    $registerUser->execute(new RegisterUser\Command(
        $_POST['email'] ?? '',
        $_POST['password'] ?? ''
    ));
}

function logout()
{
    if (isset($_SESSION['loggedInUser'])) {
        unset ($_SESSION['loggedInUser']);
    }

    header('Location: /index.php');
}

function validateInvoice()
{
    global $invoiceFormErrors;

    $requiredFields = [
        'invoice_number', 'seller_name', 'seller_vat_number',
        'buyer_name', 'buyer_vat_number', 'sell_date', 'maturity_date', 'date_of_invoice'
    ];

    foreach ($requiredFields as $requiredField) {
        if (empty($_POST[$requiredField])) {
            $invoiceFormErrors[$requiredField] = "This field is required.";
        }
    }

    $requiredInvoiceItemFields = [
        'name', 'vat', 'net_price', 'quantity'
    ];

    if ((!isset($_POST['invoice_item'])) or (isset($_POST['invoice_item']) && !$_POST['invoice_item']))  {
        $invoiceFormErrors['invoice_item'] = 'Invoice need at least one invoice item.';

        return;
    }

    foreach ($_POST['invoice_item'] as $key => $item) {
        foreach ($requiredInvoiceItemFields as $requiredField) {
            if (empty($_POST['invoice_item'][$key][$requiredField])) {
                $invoiceFormErrors['invoice_item'] = "Please fill in " . $requiredField . " field.";
                break;
            }
        }
    }
}

function totalPrice($items)
{
    $totalPrice = 0;
    foreach ($items as $key => $item) {
        $totalPrice +=  $item['net_price'] + ($item['net_price'] * ($item['vat']/100));
    }

    return $totalPrice;
}

function createInvoice()
{
    global $connection, $invoiceFormErrors;

    validateInvoice();

    if (!$invoiceFormErrors) {
        $stmt = $connection->prepare('INSERT INTO invoices (invoice_number, date_of_invoice, sell_date, maturity_date, seller_name, seller_address, seller_vat_number, buyer_name, buyer_address, buyer_vat_number, user_id, additional_info, total_price) VALUES (:invoice_number, :date_of_invoice, :sell_date, :maturity_date, :seller_name, :seller_address, :seller_vat_number, :buyer_name, :buyer_address, :buyer_vat_number, :user_id, :additional_info, :total_price)');
        $stmt->execute([
            'invoice_number' => $_POST['invoice_number'],
            'date_of_invoice' => date($_POST['date_of_invoice']),
            'sell_date' => date($_POST['date_of_invoice']),
            'maturity_date' => date($_POST['date_of_invoice']),
            'seller_name' => $_POST['seller_name'],
            'seller_address' => $_POST['seller_address'],
            'seller_vat_number' => $_POST['seller_vat_number'],
            'buyer_name' => $_POST['buyer_name'],
            'buyer_address' => $_POST['buyer_address'],
            'buyer_vat_number' => $_POST['buyer_vat_number'],
            'user_id' => $_SESSION['loggedInUser']['id'],
            'additional_info' => $_POST['additional_info'],
            'total_price' => totalPrice($_POST['invoice_item'])
        ]);

        foreach ($_POST['invoice_item'] as $key => $item) {
            $stmt = $connection->prepare("INSERT INTO invoice_items (invoice_id, name, quantity, unit, net_price, vat, total_price) VALUES (currval('invoices_id_seq'), :name, :quantity, :unit, :net_price, :vat, :total_price)");
            $stmt->execute([
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'net_price' => $item['net_price'],
                'vat' => $item['vat'],
                'total_price' => $item['net_price'] + ($item['net_price'] * ($item['vat']/100)),
            ]);
        }

        header('Location: /index.php?page=invoices&successMessage="Invoice created"');
        exit;
    }
}

function editInvoice($invoiceId)
{
    global $connection, $invoiceFormErrors;

    validateInvoice();

    if (!$invoiceFormErrors) {
        $stmt = $connection->prepare('UPDATE invoices SET invoice_number = :invoice_number, date_of_invoice = :date_of_invoice, sell_date = :sell_date, maturity_date = :maturity_date, seller_name = :seller_name, seller_address = :seller_address, seller_vat_number = :seller_vat_number, buyer_name = :buyer_name, buyer_address = :buyer_address, buyer_vat_number = :buyer_vat_number, additional_info = :additional_info, total_price = :total_price WHERE id = :id AND user_id = :user_id');

        $success = $stmt->execute([
            'invoice_number' => $_POST['invoice_number'],
            'date_of_invoice' => date($_POST['date_of_invoice']),
            'sell_date' => date($_POST['date_of_invoice']),
            'maturity_date' => date($_POST['date_of_invoice']),
            'seller_name' => $_POST['seller_name'],
            'seller_address' => $_POST['seller_address'],
            'seller_vat_number' => $_POST['seller_vat_number'],
            'buyer_name' => $_POST['buyer_name'],
            'buyer_address' => $_POST['buyer_address'],
            'buyer_vat_number' => $_POST['buyer_vat_number'],
            'user_id' => $_SESSION['loggedInUser']['id'],
            'additional_info' => $_POST['additional_info'],
            'total_price' => totalPrice($_POST['invoice_item']),
            'id' => $invoiceId
        ]);

        if (!$success) {
            $invoiceFormErrors['invoice_number'] = 'Cannot save invoice in db. Try again later.';
            return;
        }

        foreach ($_POST['invoice_item'] as $id => $item) {
            $stmt = $connection->prepare("UPDATE invoice_items SET name = :name, quantity = :quantity, unit = :unit, net_price = :net_price, vat = :vat, total_price = :total_price WHERE invoice_id = :invoice_id AND id = :id");
            $stmt->execute([
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'net_price' => $item['net_price'],
                'vat' => $item['vat'],
                'total_price' => $item['net_price'] + ($item['net_price'] * ($item['vat']/100)),
                'id' => $id,
                'invoice_id' => $invoiceId
            ]);
        }

        header('Location: /index.php?page=invoice-edit&invoice_id='.$invoiceId.'&successMessage="Invoice updated successfully"');
        exit;
    }
}

function editProfile()
{
    global $editProfile;

    $editProfile->registerResponder(new EditProfileResponder());
    $editProfile->execute(new EditProfile\Command(
        $_SESSION['loggedInUser']['email'],
        $_POST['name'] ?? '',
        $_POST['vat'] ?? '',
        $_POST['address'] ?? ''
    ));
}
