<?php
// Database connection configuration
$dbHost = 'localhost';
$dbUsername = 'id20798599_root';
$dbPassword = 'Rama@42016';
$dbName = 'id20798599_mydata';

// Establish database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the token from the URL that we maaded in forgot password site
$token = $_GET['token'];

// Check if the token exists in the database
$sql = "SELECT email FROM password_reset_tokens WHERE token = '$token'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {//the result will be zero if token not present
    echo " ";
} else {
    // Token is valid
    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Process password reset form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve new password from the form
        $newPassword = $_POST['new_password'];

        // Update the password for the user (you need to implement the user update logic)
        // For example, if you have a users table with email and password columns:
         $sql = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
         $conn->query($sql);

        // Delete the token from the database
        $sql = "DELETE FROM password_reset_tokens WHERE token = '$token'";
        $conn->query($sql);

        // Redirect to a success page or display a success message
        header("Location: reset_password_success.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        h1 {
            color: #333;
            text-align: center;
        }
        
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        
        label {
            display: block;
            margin-top: 10px;
        }
        
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
        }
        
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
 <script>
        // JavaScript code to display an alert when the token is invalid
        <?php if ($result->num_rows === 0) { ?>
        window.onload = function() {
            alert("Invalid token , Session Expired ,Resubmit the password reset form ");
        };
        <?php } ?>
    </script>
<body>
    <h1>Reset Password</h1>
    <form method="post" action="">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        <br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

