<?php
session_start();
include("connect.php");
// Redirect to cart if cart is empty
if (empty($_SESSION["cart"])) {
    echo '<script>alert("Your cart is empty!");</script>';
    echo '<script>window.location="cart-details.php";</script>';
    exit();
}

// Calculate total price
$total = 0;
foreach ($_SESSION["cart"] as $item) {
    $product_total = $item["item_quantity"] * $item["product_price"];
    $total += $product_total;
}

// Insert order into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['userID'];
    $orderDetails = json_encode($_SESSION['cart']); // Convert cart items to JSON format
    $totalPrice = $total;

    // Insert the order details
    $insertOrderQuery = "INSERT INTO orders (user_id, total_price, order_details, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($insertOrderQuery);
    $stmt->bind_param("ids", $userId, $totalPrice, $orderDetails);

    if ($stmt->execute()) {
        echo '<script>alert("Order placed successfully!");</script>';
        unset($_SESSION["cart"]); // Clear the cart after order is placed
        echo '<script>window.location="product-details.php";</script>';
    } else {
        echo '<script>alert("Error placing the order. Please try again.");</script>';
    }
    $stmt->close();
}

$con->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="landingpage.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Titillium+Web');
        * { font-family: 'Titillium Web', sans-serif; }
    </style>
</head>
<body>
<nav>
    <!-- Left Side Navigation -->
    <div class="left-nav">
      <ul>
        <li><a href="index.php" class="nav-link active">Home</a></li>
        <li><a href="#menu" class="nav-link">Menu</a></li>
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
        <li><a href="#about" class="nav-link">About Us</a></li>
        <li><a href="#contact" class="nav-link">Contact</a></li>
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

<div class="container">
    <h2>Checkout</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php
            foreach ($_SESSION["cart"] as $item) {
                $product_total = $item["item_quantity"] * $item["product_price"];
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item["item_name"]); ?></td>
                <td><?php echo htmlspecialchars($item["item_quantity"]); ?></td>
                <td>$<?php echo number_format($item["product_price"], 2); ?></td>
                <td>$<?php echo number_format($product_total, 2); ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="3" align="right">Total</td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
        </table>

        <form method="post" action="">
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
</div>

</body>
</html>
