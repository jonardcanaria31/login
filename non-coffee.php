<?php
session_start();
include("connect.php");

// Redirect to index.php if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit(); // Ensure script stops after redirection
}

// The rest of your script...

if (isset($_POST["add"])) {
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    $product_id = mysqli_real_escape_string($conn, $_GET["id"]);
    $quantity = isset($_POST["quantity"]) && is_numeric($_POST["quantity"]) && $_POST["quantity"] > 0 ? (int)$_POST["quantity"] : 1;

    $found = false;

    foreach ($_SESSION["cart"] as &$item) {
        if ($item["product_id"] == $product_id) {
            $item["item_quantity"] += $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $query = "SELECT * FROM products WHERE id = '$product_id' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["cart"][] = [
                'product_id' => $row["id"],
                'item_name' => $row["name"],
                'product_price' => $row["price"],
                'item_quantity' => $quantity
            ];
            $_SESSION["message"] = "Item successfully added to cart!";
        } else {
            $_SESSION["message"] = "Invalid Product!";
        }
    } else {
        $_SESSION["message"] = "Item quantity updated in cart!";
    }

    echo '<script>alert("'.$_SESSION["message"].'"); window.location="non-coffee.php";</script>';
    unset($_SESSION["message"]); // Clear the message after displaying
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non-Coffee</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="landingpage.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(185, 149, 95);
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box; /* Ensures consistent box-sizing */
        }

        nav {
            background-color: #ffffff;
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header {
            text-align: center;
            padding: 30px 0;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: bold;
            color: #444444;
            margin-bottom: 10px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            margin: 15px 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%; /* Ensures equal height across products */
            display: flex;
            flex-direction: column; /* Aligns items vertically */
            justify-content: space-between; /* Ensures spacing between items */
        }

        .product:hover {
            transform: scale(1.05);
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.15);
        }

        .product img {
            width: 100%; /* Ensures the image takes the full width of the container */
            height: 200px; /* Fixed height to ensure all images are the same size */
            object-fit: cover; /* Ensures the image maintains aspect ratio without distortion */
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product h5 {
            margin-top: 10px;
            font-size: 1.2rem;
            color: #333333;
        }

        .product p {
            font-size: 0.9rem;
            color: #666666;
            margin: 10px 0;
        }

        .product h5.text-danger {
            color: #e74c3c;
        }

        .product input[type="number"] {
            width: 50px;
            text-align: center;
            margin-top: 10px;
        }

        .product .btn-success {
            background-color: #27ae60;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
        }

        .product .btn-success:hover {
            background-color: #219150;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .col-md-3 {
            flex: 0 0 24%;
            max-width: 24%;
            padding: 10px;
        }

        @media (max-width: 768px) {
            .col-md-3 {
                flex: 0 0 48%;
                max-width: 48%;
            }
        }

        @media (max-width: 576px) {
            .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
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

<div class="header">
    <h1>Non-Coffee</h1>
</div>
<div class="container">
    <!-- Alert for success or error message -->
    <?php if (isset($_SESSION["message"])): ?>
        <script>
            alert("<?php echo $_SESSION["message"]; ?>");
            window.location = "non-coffee.php"; // Redirect after showing alert
        </script>
    <?php unset($_SESSION["message"]); endif; ?>

    <div class="row">
        <?php
        $query = "SELECT * FROM products WHERE name IN ('Mango Shake', 'Ube Shake', 'Choco Shake', 'Banana Shake') ORDER BY id ASC";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col-md-3">
            <form method="post" action="non-coffee.php?action=add&id=<?php echo $row["id"]; ?>">
                <div class="product">
                    <img src="images/<?php echo $row["image"]; ?>" alt="<?php echo $row["name"]; ?>">
                    <h5 class="text-info"><?php echo $row["name"]; ?></h5>
                    <p><?php echo $row["description"]; ?></p>
                    <h5 class="text-danger">₱<?php echo $row["price"]; ?></h5>
                    <input type="number" name="quantity" class="form-control" value="1" min="1">
                    <input type="submit" name="add" class="btn btn-success" value="Add to Cart">
                </div>
            </form>
        </div>
        <?php
            }
        }
        ?>
    </div>
</div>
</body>
</html>
