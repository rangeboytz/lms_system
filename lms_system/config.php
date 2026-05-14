<?php
// Production Config
$hostname = "";   // utaipata baadaye
$username = "root";
$password = "";
$database = "lms_system";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed");
}
$conn->set_charset("utf8mb4");
?>