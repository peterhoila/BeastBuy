<?php

include 'Unit6_database.php';
$conn = getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $image_name = $_POST["image_name"];
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);
    $inactive = isset($_POST["inactive"]) ? 1 : 0;

    $action = $_POST["action"];
    switch ($action) {
        case "create":
            createProduct($conn, $product_name, $image_name, $quantity, $price, $inactive);
            break;

        case "update":
            updateProduct($conn, $product_id, $product_name, $image_name, $quantity, $price, $inactive);
            break;

        case "delete":
            deleteProduct($conn, $product_id);
            break;
        case "check orders":
            echo hasOrders($conn, $product_id) ? "true" : "false";
    }
}
