<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="landingpage.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <script>
        function toggleEdit() {
            var fields = document.getElementsByClassName('editable-field');
            var editButton = document.getElementById('editButton');
            var isEditing = editButton.textContent === "Edit";

            // Toggle editable fields
            for (var i = 0; i < fields.length; i++) {
                fields[i].disabled = !isEditing;
            }

            // Toggle button text
            if (isEditing) {
                editButton.textContent = "Save";
            } else {
                editButton.textContent = "Edit";
                // Optional: Submit the form to save changes when the button becomes "Save"
                document.getElementById('profileForm').submit();
            }
        }
    </script>
</head>
<body>  
    <?php
        session_start();
        $conn = new mysqli('localhost', 'root', '', 'login');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Default user data
        $user = ['fname' => '', 'lname' => '', 'email' => '', 'mobile' => '', 'password' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProfile'])) {
            $fname = $conn->real_escape_string($_POST['fname']);
            $lname = $conn->real_escape_string($_POST['lname']);
            $email = $conn->real_escape_string($_POST['email']);
            $mobile = $conn->real_escape_string($_POST['mobile']);
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

            $updateQuery = "UPDATE users SET fname='$fname', lname='$lname', email='$email', mobile='$mobile'";
            if ($password) {
                $updateQuery .= ", password='$password'";
            }
            $updateQuery .= " WHERE email='{$_SESSION['email']}'";

            if ($conn->query($updateQuery)) {
                $_SESSION['fName'] = $fname;
                $_SESSION['lName'] = $lname;
                $_SESSION['email'] = $email;
                $_SESSION['mobile'] = $mobile;
            } else {
                echo "Error updating profile: " . $conn->error;
            }
        }

        if (isset($_SESSION['email'])) {
            $email = $conn->real_escape_string($_SESSION['email']);
            $userQuery = "SELECT * FROM users WHERE email='$email'"; // Correct table name
            $userResult = $conn->query($userQuery);
            if ($userResult && $userResult->num_rows > 0) {
                $user = $userResult->fetch_assoc();
            } else {
                echo "<p style='color: red;'>Error: User data not found.</p>";
            }
        } else {
            echo "<p style='color: red;'>Error: User is not logged in.</p>";
        }
    ?>
    

    <!-- Navigation Header -->
    <nav class="navbar">
        <!-- Left Side Navigation -->
        <div class="left-nav">
            <ul>
                <li><a href="#home" class="nav-link active" id="HomeLink">Home</a></li>
                <li><a href="#menu" class="nav-link" id="MenuLink">Menu</a></li>
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
                <li><a href="#about" class="nav-link" id="aboutLink">About Us</a></li>
                <li><a href="#contact" class="nav-link" id="contactLink">Contact</a></li>
                <li>
                    <a href="#">
                        <img src="images/shopping-cart.png" alt="Shopping Cart">
                    </a>
                </li>
                <?php if (isset($_SESSION['email']) && isset($_SESSION['fName'])): ?>
                    <li><span class="nav-link">Hello, <?php echo htmlspecialchars($_SESSION['fName']); ?>!</span></li>
                    <li><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li><a href="index.php" class="nav-link">Sign In</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Profile Edit Section -->
    <div class="container mt-5">
        <h2>Profile Information</h2>
        <form id="profileForm" method="POST" action="">
            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" class="form-control editable-field" name="fname" id="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control editable-field" name="lname" id="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control editable-field" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" class="form-control editable-field" name="mobile" id="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" class="form-control editable-field" name="password" id="password" placeholder="Leave blank to keep current password" disabled>
            </div>
            <button type="button" id="editButton" class="btn btn-primary" onclick="toggleEdit()">Edit</button>
        </form>
    </div>
</body>
</html>
