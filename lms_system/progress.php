<?php include 'header.php'; ?>

<h1>📊 My Learning Progress</h1>

<div class="card">
    <h3>Enrolled Courses Progress</h3>
    <?php
    $sql = "SELECT c.title, e.progress_percentage, e.enrolled_at 
            FROM enrollments e 
            JOIN courses c ON e.course_id = c.id 
            WHERE e.user_id = ? 
            ORDER BY e.enrolled_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="12" style="width:100%; border-collapse:collapse;">
            <tr>
                <th>Course</th>
                <th>Progress</th>
                <th>Enrolled Date</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td>
                    <strong><?= $row['progress_percentage'] ?>%</strong>
                    <div style="background:#ddd; height:10px; margin-top:5px;">
                        <div style="background:#27ae60; width:<?= $row['progress_percentage'] ?>%; height:10px;"></div>
                    </div>
                </td>
                <td><?= date('M j, Y', strtotime($row['enrolled_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You haven't enrolled in any courses yet.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>