<?php
// Include the database connection
include 'connect.php';

// Handle the form submission for editing the customer details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    if ($password) {
        $password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password if it's provided
    }

    // Check if the connection is not null
    if ($conn) {
        $updateQuery = "UPDATE users SET email = ?, mobile = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $email, $mobile, $password, $userId);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('User updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating User');</script>";
        }
        
        $stmt->close();
    } else {
        die("Database connection error.");
    }
}

// Search query
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch users from the database with search functionality
$query = "SELECT * FROM users WHERE fName LIKE '%$search_query%' OR lName LIKE '%$search_query%' OR email LIKE '%$search_query%' OR mobile LIKE '%$search_query%'";
$result = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="db.css">
  <link rel="stylesheet" href="customer.css">
</head>
<body>

  <!-- Sidebar (Aside) -->
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
  <div class="container">
    <!-- Customer Management Card -->
    <div class="card">
      <div class="card-header">Users Management</div>
      <div class="card-body">
        <!-- Search Bar -->
        <form method="POST" action="">
          <div class="search-bar">
            <input type="text" placeholder="Search customers..." name="search" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
          </div>
        </form>

        <!-- Customer Table -->
        <table class="table table-striped mt-4">
          <thead>
            <tr>
              <th>User ID</th>
              <th>User Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>#{$row['id']}</td>";
                echo "<td>{$row['fName']} {$row['lName']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['mobile']}</td>";
                echo "<td class='action-buttons'>
                        <button data-bs-toggle='modal' data-bs-target='#viewCustomerModal' onclick=\"viewCustomer('{$row['id']}', '{$row['fName']} {$row['lName']}', '{$row['email']}', '{$row['mobile']}')\">View</button>
                        <button data-bs-toggle='modal' data-bs-target='#editCustomerModal' onclick=\"editCustomer('{$row['id']}', '{$row['email']}', '{$row['mobile']}')\">Edit</button>
                      </td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='5'>No Users found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- View Customer Modal -->
  <div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewCustomerModalLabel">View Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>User ID:</strong> <span id="viewCustomerID"></span></p>
          <p><strong>User Name:</strong> <span id="viewCustomerName"></span></p>
          <p><strong>Email:</strong> <span id="viewCustomerEmail"></span></p>
          <p><strong>Phone:</strong> <span id="viewCustomerPhone"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Customer Modal -->
  <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCustomerModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST">
            <input type="hidden" id="editCustomerID" name="user_id">
            <label for="editEmail">Email:</label>
            <input type="email" id="editEmail" name="email" placeholder="Enter new email">

            <label for="editPhone">Phone:</label>
            <input type="text" id="editPhone" name="mobile" placeholder="Enter new phone number">

            <label for="editPassword">New Password:</label>
            <input type="password" id="editPassword" name="password" placeholder="Enter new password">

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" placeholder="Confirm new password">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <script>
    // Function to populate the View Customer Modal
    function viewCustomer(customerID, customerName, customerEmail, customerPhone) {
      document.getElementById('viewCustomerID').textContent = customerID;
      document.getElementById('viewCustomerName').textContent = customerName;
      document.getElementById('viewCustomerEmail').textContent = customerEmail;
      document.getElementById('viewCustomerPhone').textContent = customerPhone;
    }

    // Function to populate the Edit Customer Modal with current customer details
    function editCustomer(customerID, customerEmail, customerPhone) {
      document.getElementById('editCustomerID').value = customerID;
      document.getElementById('editEmail').value = customerEmail;
      document.getElementById('editPhone').value = customerPhone;
    }
  </script>
</body>
</html>
