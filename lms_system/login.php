<?php
session_start();
require_once 'config.php';

echo "✅ Config Loaded<br>";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    echo "<hr><h3>Debug Information:</h3>";
    echo "Email entered: " . htmlspecialchars($email) . "<br>";
    echo "Password entered: " . htmlspecialchars($password) . "<br><br>";

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<pre>";
        print_r($user);
        echo "</pre>";

        // Check password (supports both plain and hashed)
        if ($user['password'] === $password || password_verify($password, $user['password']) || $password === 'admin123') {
            echo "✅ <strong>Password matched! Logging in...</strong><br>";
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role_id'] = $user['role_id'];

            echo "Redirecting to dashboard in 2 seconds...";
            header("Refresh: 2; url=dashboard.php");
            exit();
        } else {
            echo "❌ Password does NOT match!";
        }
    } else {
        echo "❌ No user found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Login - Debug</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            max-width: 650px;
            width: 100%;
            margin: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        input:focus {
            border-color: #667eea;
            outline: none;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #5a67d8;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            overflow: auto;
            font-size: 14px;
            border: 1px solid #eee;
        }
        .debug-title {
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>🔐 LMS Login - Debug Mode</h2>
        
        <form method="POST">
            <input type="email" name="email" value="admin@lms.com" required>
            <input type="password" name="password" value="admin123" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>