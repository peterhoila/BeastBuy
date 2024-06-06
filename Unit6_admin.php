<!DOCTYPE html>
<?php
include 'Unit6_database.php';
date_default_timezone_set("America/Denver");
$conn = getConnection();
session_start();

// Redirect if not logged in
if (!isset($_SESSION['role'])) {
    header('Location: Unit6_index.php?err=Must log in first!');
    exit();
}

$role = intval($_SESSION['role']);

// Redirect if not authorized 
if ($role < 1) {
    header('Location: Unit6_index.php?err=You are not authorized for that page!');
    exit();
}
?>
<html lang="en">

<head>
    <title>Admin</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="Unit6_admin.css">
    <link rel="stylesheet" href="Unit6_common.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>
    <?php include 'Unit6_header.php' ?>
    <div class="container">
        <section>
            <h2>Customers</h2>
            <table>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                </tr>
                <?php
                $result = getMyCustomers($conn);
                if ($result) : ?>
                    <?php foreach ($result as $row) : ?>
                        <tr>
                            <td><?= $row['last_name'] ?></td>
                            <td><?= $row['first_name'] ?></td>
                            <td><?= $row['email'] ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </table>
            <br>
            <?php if ($result) {
                $numCustomers = $result->num_rows;
            } ?>
        </section>
        <hr>
        <section>
            <h2>Orders</h2>
            <?php
            $result = getMyOrders($conn);

            if ($result->num_rows > 0) {
                // There are orders, display the table
            ?>
                <table>
                    <tr>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Date</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Tax</th>
                        <th>Donation</th>
                        <th>Total</th>
                    </tr>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $customerId = $row['customer_id'];
                        $productId = $row['product_id'];
                        $date = $row['timestamp'];
                        $price = $row['price'];
                        $quantity = $row['quantity'];
                        $tax = $row['tax'];
                        $donation = $row['donation'];

                        $customer = findCustomerById($conn, $customerId);
                        $product = findProductById($conn, $productId);
                        $total = $quantity * $price + $donation + $tax;

                        if ($customer && $product) :
                    ?>
                            <tr>
                                <td><?= $customer['first_name'] . ' ' . $customer['last_name'] ?></td>
                                <td><?= $product['product_name'] ?></td>
                                <td><?= date('Y-m-d h:i A', $date) ?></td>
                                <td><?= $quantity ?></td>
                                <td><?= $price ?></td>
                                <td><?= $tax ?></td>
                                <td><?= $donation ?></td>
                                <td><?= number_format($total, 2) ?></td>
                            </tr>
                    <?php
                        endif;
                    }
                    ?>
                </table>
            <?php
            } else {
                echo "No orders yet!";
            }
            ?>

        </section>
        <hr>
        <section>
            <h2>Products</h2>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Inactive</th>
                </tr>
                <?php
                $result = getMyProducts($conn);
                if ($result) :
                    while ($row = $result->fetch_assoc()) :
                ?>
                        <tr>
                            <td><?= $row['product_name'] ?></td>
                            <td><?= $row['in_stock'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td><?= $row['inactive'] == 1 ? 'Yes' : 'No' ?></td>
                        </tr>
                <?php
                    endwhile;
                endif;
                ?>
            </table>
        </section>

    </div>
    <?php include 'Unit6_footer.php' ?>
</body>

</html>