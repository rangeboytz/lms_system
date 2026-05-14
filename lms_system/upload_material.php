<?php include 'header.php'; ?>

<h1>📤 Upload Learning Material</h1>

<?php
// Only Admin and Instructor can upload
if ($role_id != 1 && $role_id != 2) {
    echo "<p style='color:red; padding:20px; background:#ffebee;'>You don't have permission to upload materials.</p>";
    include 'footer.php';
    exit();
}

// Handle Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_material'])) {
    $course_id = intval($_POST['course_id']);
    $title = trim($_POST['title']);
    $file = $_FILES['material_file'];

    if ($file['error'] == 0) {
        $allowed = ['pdf','doc','docx','ppt','pptx','mp4','zip'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $upload_dir = "uploads/materials/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $new_name = time() . "_" . basename($file['name']);
            $file_path = $upload_dir . $new_name;

            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $sql = "INSERT INTO materials (course_id, title, file_path, file_type, file_size, uploaded_by) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssii", $course_id, $title, $file_path, $ext, $file['size'], $_SESSION['user_id']);

                if ($stmt->execute()) {
                    echo "<p style='color:green; padding:15px; background:#d4edda; border-radius:5px;'>✅ Material uploaded successfully!</p>";
                }
            } else {
                echo "<p style='color:red;'>Failed to save file.</p>";
            }
        } else {
            echo "<p style='color:red;'>File type not allowed. Allowed types: PDF, DOC, PPT, MP4, ZIP</p>";
        }
    }
}
?>

<div class="card">
    <h3>Upload New Material</h3>
    <form method="POST" enctype="multipart/form-data">
        <label>Select Course:</label><br>
        <select name="course_id" required style="width:100%; padding:12px; margin:10px 0;">
            <?php
            $courses = $conn->query("SELECT id, title FROM courses WHERE instructor_id = " . $_SESSION['user_id']);
            while($course = $courses->fetch_assoc()) {
                echo "<option value='{$course['id']}'>{$course['title']}</option>";
            }
            ?>
        </select>

        <input type="text" name="title" placeholder="Material Title (e.g. Week 1 Notes)" required 
               style="width:100%; padding:12px; margin:10px 0;">

        <input type="file" name="material_file" required style="margin:15px 0;">

        <button type="submit" name="upload_material" 
                style="padding:14px 30px; background:#27ae60; color:white; border:none; border-radius:8px; font-size:16px;">
            📤 Upload Material
        </button>
    </form>
</div>

<?php include 'footer.php'; ?>