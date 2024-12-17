<?php
session_start();
include("connect.php");
// Handle removing an item from the cart
if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"])) {
    $product_id = $_GET["id"];
    foreach ($_SESSION["cart"] as $key => $item) {
        if ($item["product_id"] == $product_id) {
            unset($_SESSION["cart"][$key]); // Remove the item from the cart
            echo '<script>alert("Item removed from cart!")</script>';
            echo '<script>window.location="cart-details.php"</script>'; // Redirect to the same page to update the cart
            break;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="landingpage.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Titillium+Web');
        * { font-family: 'Titillium Web', sans-serif; }
        table, th, td { text-align: center; }
        .title2, h2 { text-align: center; color: #66afe9; background-color: #efefef; padding: 20px; }
        table th { background-color: #efefef; }
        .btn-success { margin-top: 5px; }
    </style>
</head>
<body>
<nav>
    <!-- Left Side Navigation -->
    <div class="left-nav">
      <ul>
      <li><a href="index.php" class="nav-link active" id="homeLink">Home</a></li>
      <li><a href="index.php#menu" class="nav-link" id="menuLink">Menu</a></li>
      </ul>
    </div>

    <!-- Logo Section -->
    <div class="logo">
      <a href="#">
        <img src="images/cafe logo.png" alt="Cafe Logo">
        <span><span class="go">GO</span><span class="fee">ffee</span></span>
      </a>
    </div>

    <!-- Right Side Navigation -->
    <div class="right-nav">
      <ul>
        <li><a href="index.php#about" class="nav-link" id="aboutLink">About Us</a></li>
        <li><a href="index.php#contact" class="nav-link" id="contactLink">Contact</a></li>
        <li>
          <a href="product-details.php">
            <img src="images/shopping-cart.png" alt="Shopping Cart">
          </a>
        </li>
        <?php if (isset($_SESSION['email']) && isset($_SESSION['fName'])): ?>
          <li><a href="#">Hello, <?php echo htmlspecialchars($_SESSION['fName']); ?>!</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php">Sign In</a></li>
        <?php endif; ?>
      </ul>
    </div>
</nav>

<div class="container" style="width: 65%">
    <h2>Shopping Cart</h2>
    <div class="table-responsive">
        <?php if (!empty($_SESSION["cart"])): ?>
        <table class="table table-bordered">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION["cart"] as $item) {
                $product_total = $item["item_quantity"] * $item["product_price"];
                $total += $product_total;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item["item_name"]); ?></td>
                <td><?php echo htmlspecialchars($item["item_quantity"]); ?></td>
                <td>$<?php echo number_format($item["product_price"], 2); ?></td>
                <td>$<?php echo number_format($product_total, 2); ?></td>
                <td><a href="cart-details.php?action=delete&id=<?php echo $item["product_id"]; ?>" class="text-danger">Remove</a></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="3" align="right">Total</td>
                <td colspan="2">$<?php echo number_format($total, 2); ?></td>
            </tr>
        </table>
        <form method="post" action="order-checkout.php">
            <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
        </form>
        <?php else: ?>
        <div class="alert alert-info text-center">
            <strong>Your cart is empty!</strong> Browse products and add them to your cart.
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
