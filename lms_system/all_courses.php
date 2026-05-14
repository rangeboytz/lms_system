<?php include 'header.php'; ?>

<h1>All Available Courses</h1>

<div class="card">
    <?php
    $sql = "SELECT c.*, cat.name as category_name, u.full_name as instructor_name 
            FROM courses c 
            LEFT JOIN course_categories cat ON c.category_id = cat.id 
            LEFT JOIN users u ON c.instructor_id = u.id 
            WHERE c.status = 'published' 
            ORDER BY c.created_at DESC";
    
    $result = $conn->query($sql);
    ?>

    <?php if ($result->num_rows > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php while($course = $result->fetch_assoc()): ?>
                <div class="card" style="margin:0;">
                    <h3><?= htmlspecialchars($course['title']) ?></h3>
                    <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($course['category_name'] ?? 'General') ?></p>
                    <p><?= substr(htmlspecialchars($course['description']), 0, 120) ?>...</p>
                    
                    <form method="POST" action="enroll.php" style="margin-top:10px;">
                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                        <button type="submit" style="background:#3498db; color:white; padding:10px 15px; border:none; border-radius:5px; cursor:pointer;">
                            Enroll Now
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <a href="course_view.php?id=<?= $course['id'] ?>" style="...">Continue Learning →</a>
        <p>No published courses available yet.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>