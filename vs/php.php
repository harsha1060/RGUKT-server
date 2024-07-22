<?php
// Include database connection
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect post variables
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $verification_code = bin2hex(random_bytes(16)); // Generate a verification code

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssss", $username, $email, $password, $verification_code);
        if ($stmt->execute()) {
            // Send verification email
            $subject = "Verify Your Email Address";
            $message = "Click the link below to verify your email address:\n\n";
            $message .= "http://yourdomain.com/verify.php?code=$verification_code";
            mail($email, $subject, $message);

            echo "Registration successful! Please check your email to verify your account.";
        } else {
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>



<?php
$mysqli = new mysqli("localhost", "root", "", "event_management");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>



<?php
require 'config.php';

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    // Verify the user
    $sql = "UPDATE users SET is_verified = 1 WHERE verification_code = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $verification_code);
        if ($stmt->execute()) {
            if ($stmt->affected_rows == 1) {
                echo "Your email has been verified!";
            } else {
                echo "Invalid verification code or your email is already verified.";
            }
        } else {
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
    $mysqli->close();
} else {
    echo "No verification code provided.";
}
?>
