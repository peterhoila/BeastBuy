<?php     if (session_status() <> PHP_SESSION_ACTIVE) session_start();?>
  <nav>
    <ul>
      <?php
      if (!isset($_SESSION['role'])) { // End User
      ?>
        <li><a href="Unit6_index.php">Home</a></li>
        <li><a href="Unit6_store.php">Store</a></li>
      <?php
      } elseif ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) { // Customer Service rep or Admin
      ?>
        <li><a href="Unit6_index.php">Home</a></li>
        <li><a href="Unit6_order_entry.php">Order Entry</a></li>        
		<li><a href="Unit6_admin.php">Admin</a></li>

        <?php 
        if($_SESSION['role'] == 2) { // only for Admin
        ?>
        <li><a href="Unit6_store.php">Store</a></li>
        <li><a href="Unit6_adminProduct.php">Products</a></li>
        <?php 
        }
        ?>
        <li style="float:right"><a href="Unit6_logout.php">Logout</a></li>
      <?php
      }
      ?>
    </ul>
  </nav>
<header>
	<h1>Beast Buy - TV and Electronics Store</h1>
	<h2><em>Breaking technology, we work for you!</em></h2>
</header>
	