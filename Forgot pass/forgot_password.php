<?php

//reqired to send email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
//this is only for error reading
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the absolute path of the file the above file
$absolutePath1 = $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer-master/src/PHPMailer.php';
$absolutePath2 = $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer-master/src/Exception.php';
$absolutePath3 = $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer-master/src/SMTP.php';

// Use the absolute path in your code
require $absolutePath1;
require $absolutePath2;
require $absolutePath3;


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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve email address from the form
    $email = $_POST['email'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//email filtration
        $error = "Invalid email address";
    } else {
        // Generate a random password reset token(just a random text which cannot be predicted by anyone)
        $token = bin2hex(random_bytes(16));

        // Store the token and associated email in the database
        $sql = "INSERT INTO password_reset_tokens (email, token) VALUES ('$email', '$token')";
        if ($conn->query($sql) === TRUE) {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            // SMTP configuration to setup our emailing proseses
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Smtp server of google
            $mail->SMTPAuth = true; //authentication is seted to true 
            $mail->Username = 'ramachandraudupa2004@gmail.com'; 
            $mail->Password = 'pkyqevdpoztkznvl'; // this password is provided only for PHP mail()
            $mail->SMTPSecure = 'ssl'; // ssl sertificate to high security
            $mail->Port = 465;//comnly used ports are 16, 465,512

          /*  // Enable debug output (Only to check the error)
            $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
            $mail->Debugoutput = function ($str, $level) {
                echo "$level: $str";
            };
*/
            // Sender and recipient details
            $mail->setFrom('ramachandraudupa2004@gmail.com');
            $mail->addAddress($email);

            //content of Email
            $resetLink = "https://ripuniverse.000webhostapp.com/Forgot%20pass/reset_password.php?token=$token";
            $mail->isHTML(true);
            $mail->Subject = "Password Reset";
            $mail->Body = "Click the following link to reset your password: $resetLink";

            // Email sending process (try/catch only because some times email will not sent, that time save our website from crashing )
            try {
                $mail->send();
                header("Location: password_reset_success.php");//if email has sent the redirect to  password_reset_success.php 
                exit();//exit from this php file 
            } catch (Exception $e) {
                $error = "Error sending email: " . $mail->ErrorInfo;
            }
        } else {
            $error = "Error storing token in the database: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: #ff0000;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <input type="submit" value="Reset Password">
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
