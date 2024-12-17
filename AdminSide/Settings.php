<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="db.css">
  <link rel="stylesheet" href="settings.css">
</head>
<body>

  <!-- Sidebar (Aside) -->
  <aside>
    <div class="logo">Goffee</div>
    <ul>
      <li><a href="Dashboard.php">Dashboard</a></li>
      <li><a href="Product.php">Products</a></li>
      <li><a href="Orders.php">Orders</a></li>
      <li><a href="Customer.php">Users</a></li>
      <li><a href="Settings.php" class="active">Settings</a></li>
      <li><a href="#">Log out</a></li>
      <li><a href="Home.php">Go to Main Store</a></li>
    </ul>
  </aside>

  <!--Main Content-->
  <div class="container">
    <!-- Settings Card -->
    <div class="card">
      <div class="card-header">Settings</div>
      <div class="card-body">
        <form>
          <!-- Username Field -->
          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" placeholder="Enter new username">
          </div>

          <!-- Email Field -->
          <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter new email">
          </div>

          <!-- New Password Field -->
          <div class="form-group">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
          </div>

          <!-- Confirm Password Field -->
          <div class="form-group">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
          </div>

          <!-- Buttons -->
          <div class="modal-footer">
            <button type="button" class="btn btn-save" onclick="saveSettings()">Save Changes</button>
            <button type="button" class="btn btn-cancel" onclick="cancelChanges()">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

  <script>
    // Function to handle save changes
    function saveSettings() {
      const username = document.getElementById('username').value;
      const email = document.getElementById('email').value;
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;

      // Validate that new password and confirm password match
      if (newPassword !== confirmPassword) {
        alert("Passwords do not match!");
        return;
      }

      // Here you would send the data to your backend to save the changes
      console.log("Settings saved:", { username, email, newPassword });
      alert("Settings saved successfully!");
    }

    // Function to handle cancel changes
    function cancelChanges() {
      // Clear the form fields
      document.getElementById('username').value = '';
      document.getElementById('email').value = '';
      document.getElementById('newPassword').value = '';
      document.getElementById('confirmPassword').value = '';
      alert("Changes canceled.");
    }
  </script>
</body>
</html>