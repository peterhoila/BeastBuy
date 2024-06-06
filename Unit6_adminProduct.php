<!DOCTYPE html>
<html lang="en">
<?php
date_default_timezone_set("America/Denver");
include 'Unit6_database.php';
$conn = getConnection();
session_start();

// Redirect if not logged in
if (!isset($_SESSION['role'])) {
    header('Location: Unit6_index.php?err=Must log in first!');
    exit();
}

$role = intval($_SESSION['role']);

// Redirect if not authorized 
if ($role < 2) {
    header('Location: Unit6_index.php?err=You are not authorized for that page!');
    exit();
}
?>

<head>
	<title>Order Entry</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="Unit6_adminProduct.css">
	<link rel="stylesheet" href="Unit6_common.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>
	<?php include 'Unit6_header.php' ?>
    <?php
	if (isset($_SESSION['first_name'])) {
		echo '<p class="welcome">Welcome, ' . htmlspecialchars($_SESSION['first_name']) . '</p>';
	}
    ?>
	<div class="container">
        <div class="left-side">
		        <h2>Products</h2>
<div class="product-table" id="product-table">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Image</th>
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
                        <td><?= $row['id'] ?></td>
                            <td><?= $row['product_name'] ?></td>
                            <td><?= $row['image_name'] ?></td>
                            <td><?= $row['in_stock'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td><?= $row['inactive'] == 1 ? 'Yes' : '' ?></td>
                        </tr>
                <?php
                    endwhile;
                endif;
                ?>
            </table>
		</div></div>
        <section>
            <div id='form-error-message'></div>
			<form>
				<fieldset>
					<legend class="legend">Product Info</legend><br>
					<label for="product-name" class="align-label">Product Name: <span class="asterisk">*</span></label>
					<input type="text" id="product-name" name="product_name"><br><br>
					<label for="image-name" class="align-label">Product Image: <span class="asterisk">*</span></label>
					<input type="text" id="image-name" name="image_name"><br><br>
					<label for="quantity" class="align-label">Quantity: </label>
					<input type="number" id="quantity" name="quantity"><br><br>
					<label for="price" class="align-label">Price: <span class="asterisk">*</span></label>
					<input type="number" id="price" name="price"><br><br>
					<label for="inactive">Make Inactive: </label>
					<input type="checkbox" id="inactive" name="inactive"><br><br>
                    <input type="hidden" id="product-id" name="product_id" value="">
				</fieldset><br>

				<button class="form-btn" id="add-product-btn">Add product</button>
                <button class="form-btn" id="update-btn">Update</button>
				<button class="delete-btn" id="delete-btn">Delete</button>
			</form>
				
		</section>

	</div>
	<?php include 'Unit6_footer.php' ?>
	<script src="Unit6_adminProduct.js"></script>
</body>

</html>