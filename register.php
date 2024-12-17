<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle Sign-Up
    if (isset($_POST['signUp'])) {
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $password = $_POST['password'];

        // Check if the email already exists in the database
        $emailCheckQuery = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $emailCheckQuery);

        // If email exists, show error message
        if (mysqli_num_rows($result) > 0) {
            echo "The email address is already taken. Please choose another one.";
        } else {
            // If email doesn't exist, insert the new user
            $query = "INSERT INTO users (fName, lName, email, mobile, password) VALUES ('$fName', '$lName', '$email', '$mobile', '$password')";
            if (mysqli_query($conn, $query)) {
                // Success message and redirection after successful registration
                echo "<script>alert('Registration Successful! Please sign in.');</script>";
                echo "<script>window.location.href = 'login.php';</script>";
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }

    // Handle Sign-In
    if (isset($_POST['signIn'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Check if the user exists
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
        $row = mysqli_fetch_assoc($query);
    
        if ($row) {
            // If user is found, store the email, first name, and user ID in session
            $_SESSION['email'] = $row['email'];
            $_SESSION['fName'] = $row['fName']; // Store the first name in session
            $_SESSION['userID'] = $row['id']; // Store user ID in session
            
            // Output a success message directly
            echo "<script>
                    alert('Sign In Successfully!');
                    window.location.href = 'index.php'; // Redirect after alert
                  </script>";
            exit();
        } else {
            // If sign-in fails, store error message and redirect back to login page
            $_SESSION['errorMessage'] = 'Invalid email or password.';
            header('Location: login.php'); // Redirect to login page with error
            exit();
        }
    }
}
?>
