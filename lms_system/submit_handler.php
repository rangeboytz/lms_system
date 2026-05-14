<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: assignments.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$assignment_id = intval($_POST['assignment_id']);
$text_submission = trim($_POST['text_submission'] ?? '');

if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] == 0) {
    $file = $_FILES['submission_file'];
    $upload_dir = "uploads/submissions/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $new_name = time() . "_" . basename($file['name']);
    $file_path = $upload_dir . $new_name;

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $sql = "INSERT INTO submissions (assignment_id, student_id, file_path, text_submission) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $assignment_id, $student_id, $file_path, $text_submission);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Assignment submitted successfully!";
        } else {
            $_SESSION['error'] = "Failed to save submission.";
        }
    } else {
        $_SESSION['error'] = "Failed to upload file.";
    }
} else {
    $_SESSION['error'] = "Please upload a file.";
}

header("Location: assignments.php");
exit();
?>