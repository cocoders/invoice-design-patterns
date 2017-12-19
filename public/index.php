<?php include '../vendor/autoload.php' ?>
<?php session_start(); ?>
<?php ob_start(); ?>
<?php if ((isset($_SESSION['loggedInUser']) && !$_SESSION['loggedInUser']) or !isset($_SESSION['loggedInUser'])): ?>
    <?php header("Location: /login.php"); exit; ?>
<?php endif ?>

<?php require_once 'config.php'; ?>
<?php require_once 'functions.php'; ?>

<?php if (!isset($_GET['page']) or (isset($_GET['page']) && !in_array($_GET['page'], array_keys($pages)))): ?>
    <?php header("HTTP/1.0 404 Not Found"); exit; ?>
<?php endif ?>

<?php require_once 'header.php'; ?>
<?php require_once 'main.php'; ?>
<?php require_once 'footer.php'; ?>
<?php ob_end_flush(); ?>
