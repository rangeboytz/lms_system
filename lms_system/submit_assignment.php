<?php include 'header.php'; ?>

<?php
$assignment_id = intval($_GET['id'] ?? 0);

if ($assignment_id == 0) {
    echo "<p style='color:red;'>Invalid assignment.</p>";
    include 'footer.php';
    exit();
}

$sql = "SELECT a.*, c.title as course_title FROM assignments a 
        JOIN courses c ON a.course_id = c.id 
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$assignment = $stmt->get_result()->fetch_assoc();

if (!$assignment) {
    echo "<p>Assignment not found.</p>";
    include 'footer.php';
    exit();
}
?>

<h1>Submit Assignment</h1>
<h2><?= htmlspecialchars($assignment['title']) ?></h2>
<p><strong>Course:</strong> <?= htmlspecialchars($assignment['course_title']) ?></p>
<p><strong>Deadline:</strong> <?= date('M j, Y \a\t H:i', strtotime($assignment['deadline'])) ?></p>

<div class="card">
    <h3>Upload Your Work</h3>
    <form method="POST" enctype="multipart/form-data" action="submit_handler.php">
        <input type="hidden" name="assignment_id" value="<?= $assignment_id ?>">
        
        <label>Upload File (PDF, DOC, ZIP, etc.):</label><br>
        <input type="file" name="submission_file" required><br><br>
        
        <label>Or Write Text Answer:</label><br>
        <textarea name="text_submission" rows="8" style="width:100%;" placeholder="Write your answer here..."></textarea>
        
        <br><br>
        <button type="submit" style="padding:14px 30px; background:#27ae60; color:white; border:none; border-radius:8px; font-size:16px;">
            📤 Submit My Assignment
        </button>
    </form>
</div>

<p><a href="assignments.php">← Back to Assignments</a></p>

<?php include 'footer.php'; ?>