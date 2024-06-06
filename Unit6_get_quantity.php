<?php

include 'Unit6_database.php';
$conn = getConnection();
$product_id = $_GET["product_id"];
$stock = getQuantityInStock($conn, $product_id);
echo $stock["in_stock"];

?>