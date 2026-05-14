<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: all_courses.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$course_id = intval($_POST['course_id']);

$sql = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE enrolled_at = CURRENT_TIMESTAMP";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $course_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Successfully enrolled in the course!";
} else {
    $_SESSION['error'] = "Enrollment failed. You may already be enrolled.";
}

header("Location: all_courses.php");
exit();
?>