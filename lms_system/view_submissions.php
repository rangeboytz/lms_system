<?php include 'header.php'; ?>

<h1>📬 View Submissions</h1>

<?php if ($role_id != 1 && $role_id != 2): ?>
    <p style='color:red;'>Access denied.</p>
    <?php include 'footer.php'; exit(); ?>
<?php endif; ?>

<div class="card">
    <h3>Assignments with Submissions</h3>
    <?php
    $sql = "SELECT a.id, a.title, c.title as course_title, COUNT(s.id) as total_submissions 
            FROM assignments a 
            JOIN courses c ON a.course_id = c.id 
            LEFT JOIN submissions s ON a.id = s.assignment_id 
            WHERE c.instructor_id = ? 
            GROUP BY a.id ORDER BY a.deadline DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="12" style="width:100%; border-collapse:collapse;">
            <tr><th>Assignment</th><th>Course</th><th>Submissions</th><th>Action</th></tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['course_title']) ?></td>
                <td><strong><?= $row['total_submissions'] ?></strong></td>
                <td><a href="grade_submission.php?assignment_id=<?= $row['id'] ?>">Grade Submissions</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No assignments with submissions yet.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>