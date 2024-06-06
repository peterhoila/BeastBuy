<!DOCTYPE html>
<html lang="en">
<?php
date_default_timezone_set("America/Denver");
include 'Unit6_database.php';
$conn = getConnection();
?>

<head>
	<title>TV Shop</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="Unit6_store.css">
	<link rel="stylesheet" href="Unit6_common.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body>
	<?php include 'Unit6_header.php' ?>
	<div class="container">
		<section>
			<form action="Unit6_process_order.php" method="POST">
				<fieldset>
					<legend class="legend">Personal Info</legend><br>
					<label for="first-name" class="align-label">First Name: <span class="asterisk">*</span></label>
					<input type="text" id="first-name" name="first_name" pattern="[A-Za-z\s']+" title="Only letters, spaces, and apostrophes are allowed" required><br><br>
					<label for="last-name" class="align-label">Last Name: <span class="asterisk">*</span></label>
					<input type="text" id="last-name" name="last_name" pattern="[A-Za-z\s']+" title="Only letters, spaces, and apostrophes are allowed" required><br><br>
					<label for="email" class="align-label">Email: <span class="asterisk">*</span></label>
					<input type="email" id="email" name="email" required><br><br>
				</fieldset>

				<fieldset>
					<legend class="legend">Product Info</legend><br>
					<select id="product" name="product" required>
						<option value="" disabled selected hidden>Choose a product</option>
						<?php
						$result = getMyProducts($conn);
						if ($result) :
							while ($row = $result->fetch_assoc()) :
								if (!$row['inactive']) :
						?>
									<option value=<?= $row['id'] ?> data-image-name=<?= $row['image_name'] ?> data-in-stock=<?= $row['in_stock'] ?>> <?= $row['product_name'] ?> - $<?= $row['price'] ?></option>

						<?php
								endif;
							endwhile;
						endif;
						?>
						<label for="quantity">Quantity:</label>
						<input type="number" id="quantity" name="quantity" min="1" max="100" value="1" required><br><br>
				</fieldset><br>

				<label class="donation-label">Round up to nearest dollar for a donation?</label><br>
				<input type="radio" id="yes" name="round-up" value="yes">
				<label for="yes">Yes</label><br>
				<input type="radio" id="no" name="round-up" value="no" checked>
				<label for="no">No</label><br><br>
				<input type="hidden" name="timestamp" value="<?php echo time(); ?>">

				<input class="submit-btn" type="submit" value="Purchase">
			</form>
		</section>
		<div class="side-image">
			<img id="product-image" src="" alt="">
			<p id="stock-message"></p>
		</div>
	</div>
	<?php include 'Unit6_footer.php' ?>
	<script src="Unit6_script.js"></script>
	<script>
		const productImage = document.getElementById("product-image");
		const stockMessage = document.getElementById("stock-message");
		// Handle select change
		productSelect.addEventListener("change", updateImage);

		function updateImage() {
			var selectedOption = productSelect.options[productSelect.selectedIndex];
			productImage.src = "images/" + selectedOption.getAttribute('data-image-name');
			inStock = selectedOption.getAttribute('data-in-stock');
			var productName = selectedOption.text.split(' - ')[0];
			if (inStock <= 0) {
				stockMessage.textContent = "SOLD OUT"
			} else if (inStock <= 5) {
				stockMessage.textContent = "Only " + inStock + " left";
			} else {
				stockMessage.textContent = "";
			}
			// Check and update cookies.
			if (!existsCookie('viewedProducts')){
				setCookie('viewedProducts', '', 7);
			}
			var viewedProducts = getCookie('viewedProducts');
			viewedProducts = viewedProducts ? JSON.parse(viewedProducts) : [];
			if (!viewedProducts.includes(productName)) {
				viewedProducts.push(productName);
				var json_str = JSON.stringify(viewedProducts);
				setCookie('viewedProducts', json_str, 7);
			}
			productSelect.blur();
		}
	</script>
</body>

</html>