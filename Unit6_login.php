<?php
session_start();
include 'Unit6_database.php';
$conn = getConnection();

$email    = !empty($_POST['email']) ? $_POST['email'] : null;
$password = !empty($_POST['password']) ? $_POST['password'] : null;

if ($email === null || $password === null) {
    header('Location: index.php?err=Email or Password is empty');
    exit();
}

$user = getUser($conn, $email, $password);

if ($user == false) {
    header('Location: Unit6_index.php?err=Invalid User');
    exit();
}

$_SESSION['role'] = $user['role'];
$_SESSION['first_name'] = $user['first_name'];

if ($user['role'] == 1) {
    header('Location: Unit6_order_entry.php');
    exit();
} elseif ($user['role'] == 2) {
    header('Location: Unit6_adminProduct.php');
    exit();
}
?>