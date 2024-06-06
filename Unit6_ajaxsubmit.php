<?php

include 'Unit6_database.php';
$conn = getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $product_id = $_POST["product"];
    $product = findProductById($conn, $product_id);
    $price = $product['price'];
    $quantity = intval($_POST["quantity"]);
    $tax_rate = 8.5;
    $donation = 0;

    $subtotal = $price * $quantity;
    $tax = $subtotal * $tax_rate / 100;
    $total = $subtotal + $tax;

    $customerExists = findCustomerByEmail($conn, $email);
    if ($customerExists) {
        $customer_id = $customerExists['id'];
    } else {
        addCustomer($conn, $first_name, $last_name, $email);
        $customerExists = findCustomerByEmail($conn, $email);
        $customer_id = $customerExists['id'];
    }

    echo "Order submitted for: <b>" . $first_name . " " .$last_name . "</b> " . $quantity . " " . $product['product_name'] . " <i>Total:<i> $" . $total;
    addOrder($conn, $product_id, $customer_id, $quantity, $price, $tax, $donation, time());
    sellProduct($conn, $product_id, $quantity);
}
?>