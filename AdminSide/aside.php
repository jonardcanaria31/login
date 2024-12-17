
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="db.css">
</head>
<body>

<aside>
    <div class="logo">Goffee</div>
    <ul>
        <li><a href="Dashboard.php" class="active">Dashboard</a></li>
        <li><a href="Product.php">Products</a></li>
        <li><a href="Orders.php">Orders</a></li>
        <li><a href="Customer.php">Users</a></li>
        <li><a href="#">Log out</a></li>
        <li><a href="index.php">Go to Main Store</a></li>
    </ul>
</aside>


<style> 
/* Sidebar (Aside) Styling */
aside {
  width: 250px;
  background-color: #cfbaa2;
  color: white;
  padding-top: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 120%;
  border-right: 1px solid #ddd;
}

/* Logo Styling */
.logo {
  font-size: 24px;
  font-weight: bold;
  letter-spacing: 2px;
  margin-bottom: 40px;
}

/* Sidebar Links */
aside ul {
  padding: 0;
  width: 100%;
  list-style: none;
}

aside ul li {
  width: 100%;
}

aside ul li a {
  display: block;
  padding: 15px;
  text-align: center;
  color: white;
  text-decoration: none;
  font-size: 18px;
  transition: background-color 0.3s;
}

aside ul li a:hover {
  background-color: #c6a686;
}

</style>