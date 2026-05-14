<?php include 'header.php'; ?>

<h1>My Enrolled Courses</h1>

<div class="card">
    <?php
    $sql = "SELECT c.*, cat.name as category_name, e.enrolled_at, e.progress_percentage 
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            LEFT JOIN course_categories cat ON c.category_id = cat.id
            WHERE e.user_id = ? 
            ORDER BY e.enrolled_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <?php if ($result->num_rows > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php while($course = $result->fetch_assoc()): ?>
                <div class="card" style="margin:0;">
                    <h3><?= htmlspecialchars($course['title']) ?></h3>
                    <p><strong>Category:</strong> <?= htmlspecialchars($course['category_name'] ?? 'General') ?></p>
                    <p><strong>Enrolled:</strong> <?= date('M j, Y', strtotime($course['enrolled_at'])) ?></p>
                    <p><strong>Progress:</strong> <?= $course['progress_percentage'] ?>%</p>
                    
                    <a href="course_view.php?id=<?= $course['id'] ?>" 
                       style="display:inline-block; margin-top:10px; padding:10px 15px; background:#3498db; color:white; text-decoration:none; border-radius:5px;">
                        Continue Learning →
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>You haven't enrolled in any courses yet.</p>
        <a href="all_courses.php" style="color:#3498db;">Browse Available Courses</a>
        <a href="course_view.php?id=<?= $course['id'] ?>" style="...">Continue Learning →</a>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>