<?php
session_start();

// Database connection

include "connect.php";

// Fetch customer data with total price and status
$query = "SELECT u.id AS user_id, u.fName, u.lName, o.created_at, o.total_price, o.status
          FROM users u
          JOIN orders o ON u.id = o.user_id
          ORDER BY o.created_at DESC";

$result = $conn->query($query);

// Fetch total price from orders for each customer
$totalPriceQuery = "SELECT u.id AS user_id, SUM(o.total_price) AS total_price
                    FROM users u
                    JOIN orders o ON u.id = o.user_id
                    GROUP BY u.id";
$totalPriceResult = $conn->query($totalPriceQuery);

$totalPrices = [];
while ($row = $totalPriceResult->fetch_assoc()) {
    $totalPrices[$row['user_id']] = $row['total_price'];
}

// Close the database connection after fetching data
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="db.css">
    <style>
        .done-button {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>

<aside>
    <div class="logo">Goffee</div>
    <ul>
        <li><a href="Dashboard.php">Dashboard</a></li>
        <li><a href="Product.php">Products</a></li>
        <li><a href="Orders.php">Orders</a></li>
        <li><a href="Customer.php" class="active">Users</a></li>
        <li><a href="#">Log out</a></li>
        <li><a href="../index.php">Go to Main Store</a></li>
    </ul>
</aside>

<!-- Main Content -->
<div class="main-content">
    <div class="card">
        <div class="card-header">Customer Overview</div>
        <div class="card-body">
            <h3>Customer Details</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['fName'] . ' ' . $row['lName']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>$<?php echo number_format($totalPrices[$row['user_id']], 2); ?></td>
                            <td>
                                <form method="POST" action="orders.php?order_id=<?php echo $row['user_id']; ?>" class="status-form-<?php echo $row['user_id']; ?>">
                                    <select name="status" class="form-control" onchange="submitForm(<?php echo $row['user_id']; ?>)">
                                        <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Done" <?php echo $row['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm done-button" onclick="markAsDone(<?php echo $row['user_id']; ?>)" <?php echo $row['status'] == 'Done' ? 'disabled' : ''; ?>>
                                    <?php echo $row['status'] == 'Done' ? 'Done' : 'Mark as Done'; ?>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function submitForm(userId) {
        document.querySelector(`.status-form-${userId}`).submit();
    }

    function markAsDone(userId) {
        // Optionally handle status update through an AJAX request if needed
        const statusForm = document.querySelector(`.status-form-${userId} select`);
        statusForm.value = 'Done';
        statusForm.form.submit();
    }
</script>
</body>
</html>
