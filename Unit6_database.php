<?php
date_default_timezone_set("America/Denver");
function getConnection()
{
    include('Unit6_database_credentials.php');
    error_reporting(E_ALL);
    ini_set('display_errors', True);
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Get all customers
function getMyCustomers($conn)
{
    $sql = "select * from customer";
    $result = $conn->query($sql);
    return $result;
}

function findCustomerById($conn, $customerId) {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE id = ?");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function findCustomerByEmail($conn, $email) {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function findMatchCustName($conn, $searchTerm, $field) {
    if ($field == 'first'){
        $column = 'first_name';
    } else{
        $column = 'last_name';
    }
    $searchTerm .= '%';
    $stmt = $conn->prepare("SELECT * FROM customer WHERE $column LIKE ?");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function addCustomer($conn, $firstName, $lastName, $email) {
    $stmt = $conn->prepare("INSERT INTO customer (first_name, last_name, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $firstName, $lastName, $email);
    return $stmt->execute();
}

// Get all orders
function getMyOrders($conn)
{
    $sql = "select * from orders";
    $result = $conn->query($sql);
    return $result;
}

function addOrder($conn, $productId, $customerId, $quantity, $price, $tax, $donation, $timestamp) {
    $stmt = $conn->prepare("INSERT INTO orders (product_id, customer_id, quantity, price, tax, donation, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiidddi", $productId, $customerId, $quantity, $price, $tax, $donation, $timestamp);
    return $stmt->execute();
}

function doesOrderExist($conn, $customerId, $productId, $timestamp) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? AND product_id = ? AND timestamp = ?");
    $stmt->bind_param("iii", $customerId, $productId, $timestamp);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0);
}

// Get all products
function getMyProducts($conn)
{
    $sql = "select * from product";
    $result = $conn->query($sql);
    return $result;
}

function findProductById($conn, $productId) {
    $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function sellProduct($conn, $productId, $quantitySold) {
    $currentQuantity = getQuantityInStock($conn, $productId)['in_stock'];
    $stmt = $conn->prepare("UPDATE product SET in_stock = in_stock - ? WHERE id = ?");
    if ($currentQuantity >= $quantitySold) {
        $stmt->bind_param("ii", $quantitySold, $productId);
        $stmt->execute();
    } else {
        $stmt->bind_param("ii", $currentQuantity, $productId);
        $stmt->execute();
    }
}

function getQuantityInStock($conn, $productId) {
    $stmt = $conn->prepare("SELECT in_stock FROM product WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function createProduct($conn, $product_name, $image_name, $quantity, $price, $inactive) {
    $stmt = $conn->prepare("INSERT INTO product (product_name, image_name, price, in_stock, inactive) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdii", $product_name, $image_name, $price, $quantity, $inactive);
    $stmt->execute();
}

function updateProduct($conn, $product_id, $product_name, $image_name, $quantity, $price, $inactive) {
    $stmt = $conn->prepare("UPDATE product SET product_name = ?, image_name = ?, in_stock = ?, price = ?, inactive = ? WHERE id = ?");
    $stmt->bind_param("ssidii", $product_name, $image_name, $quantity, $price, $inactive, $product_id);
    $stmt->execute();
}

function deleteProduct($conn, $product_id) {
    $stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
}

function hasOrders($conn, $product_id) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0);
}

function getUser($conn, $email, $password) {
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
    $stmt->bind_param("ss",$email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}