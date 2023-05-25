<?php
// Check if the form is submitted
 header("Location: https://ripuniverse.000webhostapp.com/Login/login.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  // Retrieve form data
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password1 = $_POST["password"];
  $dob = $_POST["dob"];
  $mobile1=$_POST["mobile"];

  // Validate and process the data
  if (!empty($name) && !empty($email) && !empty($password1) && !empty($dob))
   {
    $host = "localhost";
   $username = "id20798599_root";
   $password = "Rama@42016";
   $database = "id20798599_mydata";
   $conn = new mysqli($host, $username, $password, $database);
  
  
   if ($conn->connect_error)
     {
      die("Connection failed: " . $conn->connect_error);
    }
  

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR mobile = ?");
  $stmt->bind_param("ss", $email, $mobile); 
  $stmt->execute();
  
  $result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Record(s) with the email or mobile number already exist
    echo "Email or mobile number already registered.";
  } 
  else
 {
  $sql = "INSERT INTO users (name, email, password,Dob,mobile) VALUES ('$name', '$email', $password1,'$dob','$mobile1')";
  
  if ($conn->query($sql) === TRUE) {
    echo "Account Created successfully!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  } 
  $conn->close();
}
   }   
  else {
    echo "Please fill in all the required fields.";
    $conn->close();
  }
}
 exit;
?>



