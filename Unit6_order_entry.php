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
if ($role < 1) {
    header('Location: Unit6_index.php?err=You are not authorized for that page!');
    exit();
}
?>

<head>
	<title>Order Entry</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="Unit6_order_entry.css">
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
		<section>
			<form>
				<fieldset>
					<legend class="legend">Personal Info</legend><br>
					<label for="first-name" class="align-label">First Name: <span class="asterisk">*</span></label>
					<input type="text" id="first-name" name="first_name" onkeyup="showHint(this.value, 'first')" ><br><br>
					<label for="last-name" class="align-label">Last Name: <span class="asterisk">*</span></label>
					<input type="text" id="last-name" name="last_name" onkeyup="showHint(this.value, 'last')"><br><br>
					<label for="email" class="align-label">Email: <span class="asterisk">*</span></label>
					<input type="email" id="email" name="email"><br><br>
				</fieldset>

				<fieldset>
					<legend class="legend">Product Info</legend><br>
					<select id="product" name="product">
						<option value="" disabled selected hidden>Choose a product</option>
						<?php
						$result = getMyProducts($conn);
						if ($result) :
							while ($row = $result->fetch_assoc()) :
						?>
								<option value=<?= $row['id'] ?> data-image-name=<?= $row['image_name'] ?> data-in-stock=<?= $row['in_stock'] ?>> <?= $row['product_name'] ?> - $<?= $row['price'] ?></option>

						<?php
							endwhile;
						endif;
						?>
					</select><br>
					<label for="available">Available:</label>
					<input type="number" id="available" name="available" readonly><br><br>
					<label for="quantity">Quantity:</label>
					<input type="number" id="quantity" name="quantity" min="1" max="100" value="1" required><br><br>
				</fieldset><br>

				<input class="submit-btn" id="submit" type="submit" value="Purchase"></input>
				<button class="reset-btn" type="reset">Clear Fields</button>
			</form>
				
		</section>
		<div class="customer-lookup" id="customer-lookup">
		</div>
	</div>
	<?php include 'Unit6_footer.php' ?>
	<script src="Unit6_script.js"></script>
</body>

</html>