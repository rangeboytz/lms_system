<?php include 'header.php'; ?>

<h1>Grade Submissions</h1>

<?php
$assignment_id = intval($_GET['assignment_id'] ?? 0);

if ($role_id != 1 && $role_id != 2) {
    echo "<p style='color:red;'>Access denied.</p>";
    include 'footer.php';
    exit();
}

// Get submissions for this assignment
$sql = "SELECT s.*, u.full_name as student_name 
        FROM submissions s 
        JOIN users u ON s.student_id = u.id 
        WHERE s.assignment_id = ? 
        ORDER BY s.submitted_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="card">
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="12" style="width:100%; border-collapse:collapse;">
            <tr>
                <th>Student</th>
                <th>Submitted</th>
                <th>File</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= date('M j, Y H:i', strtotime($row['submitted_at'])) ?></td>
                <td>
                    <?php if($row['file_path']): ?>
                        <a href="<?= $row['file_path'] ?>" target="_blank">Download</a>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="grade_submission.php?id=<?= $row['id'] ?>">Grade</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No submissions yet for this assignment.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>