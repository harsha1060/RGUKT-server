<?php
$mysqli = new mysqli("localhost", "root", "", "login_system");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
