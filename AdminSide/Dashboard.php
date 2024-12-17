<?php
session_start();


include "connect.php";



// Fetching data for the dashboard
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders";
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalOrders = $totalOrdersResult->fetch_assoc()['total_orders'];

$totalProductsQuery = "SELECT COUNT(*) AS total_products FROM products";
$totalProductsResult = $conn->query($totalProductsQuery);
$totalProducts = $totalProductsResult->fetch_assoc()['total_products'];

$availableProductsQuery = "SELECT COUNT(*) AS available_products FROM products WHERE stock > 0";
$availableProductsResult = $conn->query($availableProductsQuery);
$availableProducts = $availableProductsResult->fetch_assoc()['available_products'];

$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'];

$totalPriceQuery = "SELECT SUM(total_price) AS total_price FROM orders";
$totalPriceResult = $conn->query($totalPriceQuery);
$totalPrice = $totalPriceResult->fetch_assoc()['total_price'];

$recentTransactionsQuery = "SELECT o.id AS order_id, u.fName AS customer_name, o.created_at, o.total_price
                            FROM orders o
                            JOIN users u ON o.user_id = u.id
                            ORDER BY o.created_at DESC LIMIT 5";
$recentTransactionsResult = $conn->query($recentTransactionsQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="db.css">
</head>
<body>  
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (from aside.php) -->
        <div class="col-md-3">
        <aside>
    <div class="logo">Goffee</div>
    <ul>
        <li><a href="Dashboard.php" class="active">Dashboard</a></li>
        <li><a href="Product.php">Products</a></li>
        <li><a href="Orders.php">Orders</a></li>
        <li><a href="Customer.php">Users</a></li>
        <li><a href="#">Log out</a></li>
        <li><a href="../index.php">Go to Main Store</a></li>
    </ul>
</aside>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <h3>Welcome to the Admin Dashboard</h3>
                    <p>Here you can manage your store's products, orders, customers, and settings.</p>

                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card">
                                <h5>Total Orders</h5>
                                <p><?php echo $totalOrders; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card">
                                <h5>Total Users</h5>
                                <p><?php echo $totalUsers; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card">
                                <h5>Total Products</h5>
                                <p><?php echo $totalProducts; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card">
                                <h5>Available Products</h5>
                                <p><?php echo $availableProducts; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card">
                                <h5>Total Revenue</h5>
                                <p>$<?php echo number_format($totalPrice, 2); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions Table -->
                    <div class="card mt-4">
                        <div class="card-header">Recent Transactions</div>
                        <div class="card-body">
                            <table class="table table-bordered transactions-table">
                                <thead>
                                    <tr>
                                        <th>Order Name</th>
                                        <th>Customer Name</th>
                                        <th>Order Date</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $recentTransactionsResult->fetch_assoc()) { ?>
                                        <tr>
                                            <td>Order #<?php echo $row['order_id']; ?></td>
                                            <td><?php echo $row['customer_name']; ?></td>
                                            <td><?php echo $row['created_at']; ?></td>
                                            <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Go to Orders Button -->
                    <a href="Orders.php" class="go-to-orders-btn">Go to Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
