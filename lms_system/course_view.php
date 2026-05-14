<?php include 'header.php'; ?>

<?php
if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>No course selected.</p>";
    include 'footer.php';
    exit();
}

$course_id = intval($_GET['id']);

// Get course details
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    echo "<p>Course not found.</p>";
    include 'footer.php';
    exit();
}
?>

<h1><?= htmlspecialchars($course['title']) ?></h1>
<p><?= htmlspecialchars($course['description']) ?></p>

<div class="card">
    <h3>📚 Learning Materials</h3>
    <?php
    $sql = "SELECT * FROM materials WHERE course_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <?php if ($result->num_rows > 0): ?>
        <ul style="list-style:none; padding:0;">
            <?php while($material = $result->fetch_assoc()): ?>
                <li style="padding:12px; background:#f9f9f9; margin:8px 0; border-radius:6px;">
                    📄 <strong><?= htmlspecialchars($material['title']) ?></strong> 
                    (<?= strtoupper($material['file_type']) ?>)
                    <br>
                    <a href="<?= $material['file_path'] ?>" target="_blank" 
                       style="color:#3498db;">📥 Download / View</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No materials uploaded for this course yet.</p>
    <?php endif; ?>
</div>

<a href="all_courses.php" style="color:#667eea;">← Back to All Courses</a>

<?php include 'footer.php'; ?>