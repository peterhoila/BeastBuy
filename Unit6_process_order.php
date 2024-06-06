<!DOCTYPE html>
<html lang="en">
<?php
include 'Unit6_database.php';
$conn = getConnection();
?>

<head>
    <title>Order Confirmation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="Unit6_common.css">
    <link rel="stylesheet" href="Unit6_process_order.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <?php include 'Unit6_header.php'; ?>
    <div id="receipt">
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $email = $_POST["email"];
            $product_id = $_POST["product"];
            $product = findProductById($conn, $product_id);
            $price = $product['price'];
            $quantity = intval($_POST["quantity"]);
            $round_up = $_POST["round-up"];
            $timestamp = $_POST['timestamp'];
            $tax_rate = 8.5;

            $subtotal = $price * $quantity;
            $tax = $subtotal * $tax_rate / 100;
            $total = $subtotal + $tax;
            $donation = 0;
            if ($round_up == 'yes') {
                $donation = ceil($total) - $total;
            }
            $total_with_donation = $total + $donation;

            $customerExists = findCustomerByEmail($conn, $email);


            echo "<b>Hello " . $first_name . " " . $last_name . " </b>";
            if ($customerExists) {
                $customer_id = $customerExists['id'];
                echo "<i> - Welcome back!</i>";
            } else {
                echo "<i> - Thank you for becoming a customber!</i>";
                addCustomer($conn, $first_name, $last_name, $email);
                $customerExists = findCustomerByEmail($conn, $email);
                $customer_id = $customerExists['id'];
            }
            echo "<br>We hope you enjoy your <b><i>" . $product['product_name'] . "</i></b>";
            echo "<br>Order details:";

            echo '<div class="row">';
            echo '<div class="word">' . $quantity . ' @ $' . number_format($price, 2) . ':</div>';
            echo '<div class="number">$' . number_format($subtotal, 2) . '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="word">Tax:</div>';
            echo '<div class="number">$' . number_format($tax, 2) . '</div>';
            echo '</div>';



            if ($round_up == "yes") {
                echo '<div class="row">';
                echo '<div class="word">Subtotal:</div>';
                echo '<div class="number">$' . number_format($total, 2) . '</div>';
                echo '</div>';

                echo '<div class="row">';
                echo '<div class="word">Total with donation:</div>';
                echo '<div class="number">$' . number_format($total_with_donation, 2) . '</div>';
                echo '</div>';
            } else {
                echo '<div class="row">';
                echo '<div class="word">Total:</div>';
                echo '<div class="number">$' . number_format($total, 2) . '</div>';
                echo '</div>';
            }
            echo "<br>We'll send special offers to " . $email;

            if (!doesOrderExist($conn, $customer_id, $product_id, $timestamp)) {
                addOrder($conn, $product_id, $customer_id, $quantity, $price, $tax, $donation, $timestamp);
                sellProduct($conn, $product_id, $quantity);
            }
        }
        ?>
        <script src="Unit6_script.js"></script>
        <script>
            viewedProducts = getCookie('viewedProducts');
            viewedProducts = viewedProducts ? JSON.parse(viewedProducts) : [];
            var purchasedProduct = <?php echo json_encode($product['product_name']); ?>;
            // Remove the purchased product
            var index = viewedProducts.indexOf(purchasedProduct);
            if (index !== -1) {
                viewedProducts.splice(index, 1);
            }

            // Display bullet list
            if (viewedProducts.length > 0) {
                document.write("<div id='coupon' style='color:rgb(4, 122, 122)'>");
                document.write("<p>Based on your viewing history, we'd like to offer 20% off these items:</p>");
                document.write("<ul>");
                viewedProducts.forEach(function(productId) {
                    document.write("<li>" + productId + "</li>");
                });
                document.write("</ul></div>");
            }
            deleteCookie('viewedProducts');
        </script>
    </div>
    <?php include 'Unit6_footer.php'; ?>

</body>

</html>