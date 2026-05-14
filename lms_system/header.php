<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get role name
$role_id = $_SESSION['role_id'];
$role_name = ($role_id == 1) ? "Admin" : (($role_id == 2) ? "Instructor" : "Student");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .sidebar {
            width: 250px; background: #2c3e50; color: white; 
            position: fixed; height: 100%; padding: 20px 0;
        }
        .sidebar h2 { padding: 0 20px 20px; border-bottom: 1px solid #34495e; }
        .sidebar a {
            display: block; padding: 12px 20px; color: #bdc3c7;
            text-decoration: none;
        }
        .sidebar a:hover { background: #34495e; color: white; }
        .main-content {
            margin-left: 250px; padding: 20px;
        }
        .header {
            background: #667eea; color: white; padding: 15px 30px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .card { background: white; padding: 20px; border-radius: 8px; margin: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="sidebar">
    <h2>LMS</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="courses.php">📚 All Courses</a>
    <a href="my_courses.php">📖 My Courses</a>
    <a href="upload_material.php">📤 Upload Materials</a>
    <a href="materials.php">📚 All Materials</a>
    <a href="all_courses.php">📚 Browse Courses</a>
    <a href="assignments.php">📝 Assignments</a>
    <a href="view_submissions.php">📬 View Submissions</a>
    <a href="progress.php">📊 My Progress</a>
    
    <?php if($role_id == 1 || $role_id == 2): // Admin & Instructor ?>
        <a href="courses.php">➕ Manage Courses</a>
    <?php endif; ?>
    
    <a href="logout.php" style="color:#e74c3c; margin-top:30px;">🚪 Logout</a>
</div>

    <div class="main-content">
        <div class="header">
            <h3>Welcome, <?php echo $_SESSION['full_name']; ?> (<?php echo $role_name; ?>)</h3>
        </div>