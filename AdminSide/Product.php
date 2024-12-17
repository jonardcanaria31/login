<?php
// Database connection
include "connect.php";

// Add Product Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productName'])) {
    $name = $_POST['productName'];
    $description = $_POST['productDescription'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productStock'];

    // Image upload
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        $imageTmpName = $_FILES['productImage']['tmp_name'];
        $imageName = $_FILES['productImage']['name'];
        $imagePath = 'uploads/' . $imageName;
        move_uploaded_file($imageTmpName, $imagePath);
    }

    $sql = "INSERT INTO products (name, description, price, image, stock)
            VALUES ('$name', '$description', '$price', '$imageName', '$stock')";

    if ($conn->query($sql) === TRUE) {
        header("Location: #"); // Redirect to this page to prevent resubmission
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Edit Product Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['productName'];
    $description = $_POST['productDescription'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productStock'];

    // Image upload for editing
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        $imageTmpName = $_FILES['productImage']['tmp_name'];
        $imageName = $_FILES['productImage']['name'];
        $imagePath = 'uploads/' . $imageName;
        move_uploaded_file($imageTmpName, $imagePath);
    }

    $sql = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock', image='$imageName' WHERE id='$product_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: #"); // Redirect after update
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete Product Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $product_id = $_POST['delete_id'];

    $sql = "DELETE FROM products WHERE id = $product_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: #"); // Redirect after deletion
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

// Search Logic
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch Products with Search
$query = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
$result = $conn->query($query);
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
  <link rel="stylesheet" href="product.css">
</head>
<body>

  <!-- Sidebar (Aside) -->
  <aside>
    <div class="logo">Goffee</div>
    <ul>
      <li><a href="Dashboard.php">Dashboard</a></li>
      <li><a href="Product.php" class="active">Products</a></li>
      <li><a href="Orders.php">Orders</a></li>
      <li><a href="Customer.php">Users</a></li>
      <li><a href="#">Log out</a></li>
      <li><a href="../index.php">Go to Main Store</a></li>
    </ul>
  </aside>

  <!--Main Body-->
  <div class="container">
    <!-- Product Management Card -->
    <div class="card">
      <div class="card-header">Product Management</div>
      <div class="card-body">
        <!-- Search Form -->
        <form method="POST" action="">
          <div class="search-bar">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
          </div>
        </form>

        <button class="btn-add-product" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

        <!-- Product Table -->
        <table class="table table-striped mt-4">
          <thead>
            <tr>
              <th>Image</th>
              <th>Product Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $image = isset($row['image']) ? htmlspecialchars($row['image']) : 'default.png';
                  $name = isset($row['name']) ? htmlspecialchars($row['name']) : 'No Name';
                  $description = isset($row['description']) ? htmlspecialchars($row['description']) : 'No Description';
                  $price = isset($row['price']) ? number_format($row['price'], 2) : '0.00';
                  $stock = isset($row['stock']) ? intval($row['stock']) : 0;

                  echo "<tr>";
                  echo "<td><img src='../images/" . $image . "' alt='Product' style='width: 50px;'></td>";
                  echo "<td>" . $name . "</td>";
                  echo "<td>" . $description . "</td>";
                  echo "<td>$" . $price . "</td>";
                  echo "<td>" . $stock . "</td>";
                  echo "<td>
                          <div class='action-buttons'>
                              <button data-bs-toggle='modal' data-bs-target='#editProductModal' onclick=\"populateEditModal('" . $row['id'] . "', '" . $name . "', '" . $description . "', '" . $price . "', '" . $stock . "', '" . $image . "')\">Edit</button>
                              <form action='' method='POST' style='display:inline;'>
                                  <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                  <button type='submit'>Delete</button>
                              </form>
                          </div>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6'>No products found</td></tr>";
          }
          ?>
        </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add Product Modal -->
  <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="productName" placeholder="Product Name" required>
            <textarea name="productDescription" placeholder="Description" required></textarea>
            <input type="number" name="productPrice" placeholder="Price" required>
            <input type="number" name="productStock" placeholder="Stock" required>
            <input type="file" name="productImage" required>
            <button type="submit" class="btn">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Product Modal -->
  <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="editProductId">
            <input type="text" name="productName" id="editProductName" placeholder="Product Name" required>
            <textarea name="productDescription" id="editProductDescription" placeholder="Description" required></textarea>
            <input type="number" name="productPrice" id="editProductPrice" placeholder="Price" required>
            <input type="number" name="productStock" id="editProductStock" placeholder="Stock" required>
            <input type="file" name="productImage" id="editProductImage">
            <button type="submit" class="btn">Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <script>
    // Function to populate Edit Modal with existing product data
    function populateEditModal(id, name, description, price, stock, image) {
      document.getElementById('editProductId').value = id;
      document.getElementById('editProductName').value = name;
      document.getElementById('editProductDescription').value = description;
      document.getElementById('editProductPrice').value = price;
      document.getElementById('editProductStock').value = stock;
      // The image field will not be populated because it can't be edited directly
    }
  </script>
  
</body>
</html>
