<?php

    $host = "localhost";
$username = "id20798599_root";
$password1 = "Rama@42016";
$database = "id20798599_mydata";
    $conn = new mysqli($host, $username, $password1, $database);

if (isset($_SESSION['user_id'])) {
  header("Location: home.html");
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT password FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($storedPassword);
  $stmt->fetch();
  $stmt->close();


  // Verify the entered password with the stored password
  if ($password === $storedPassword)  {
    // Password is valid, store user information in the session

    // Redirect the user to the home page or any other authorized page
    header("Location: home.html");
    exit;
  } else {
    // Password is invalid
   echo '<script>alert("Invalid password.");</script>';
  }
}
  //if (isset($errorMessage)) { echo "<p>" . $errorMessage . "</p>"; }     (if error happens then this will writes error in your current page )
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
  <link rel="stylesheet" type="text/css" href="./Styles.css">
</head>
<body>
  <div class="container">
    <h1>Login</h1>
    <form  method="POST" action="login.php">
      <div class="form-group">
        <label for="username">Email</label>
        <input type="text" id="email" name="email" placeholder="Enter your Email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>
      <div class="form-group">
        <input type="submit" value="Login">
      </div>
      <div class="form-group">
        <a href="../Forgot pass/forgot_password.php" class="forgot-password">Forgot password?</a>
      </div>
    </form>
    <div class="signup">
      <p>New user? <a href="../Signup/signup.html" class="signup-link">Sign up</a></p>
    </div>
  </div>

</body>
</html>
