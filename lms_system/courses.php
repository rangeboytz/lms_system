<?php include 'header.php'; ?>

<h1>Course Management</h1>

<div class="card">
    <h3>Create New Course</h3>
    
    <form method="POST">
        <input type="text" name="title" placeholder="Course Title" required 
               style="width:100%; padding:12px; margin:8px 0; font-size:16px;">
        
        <textarea name="description" placeholder="Course Description" rows="5" 
                  style="width:100%; padding:12px; margin:8px 0; font-size:16px;"></textarea>
        
        <label style="display:block; margin:10px 0 5px;">Category:</label>
        <select name="category_id" required style="width:100%; padding:12px; margin:8px 0; font-size:16px;">
            <?php
            $cats = $conn->query("SELECT id, name FROM course_categories ORDER BY name");
            while($cat = $cats->fetch_assoc()) {
                echo "<option value='".$cat['id']."'>".$cat['name']."</option>";
            }
            ?>
        </select>
        
        <button type="submit" name="create_course" 
                style="padding:14px 25px; background:#27ae60; color:white; border:none; 
                       border-radius:6px; font-size:16px; cursor:pointer; margin-top:10px;">
            ✅ Create Course
        </button>
    </form>
</div>

<div class="card">
    <h3>My Courses</h3>
    
    <?php
    $sql = "SELECT c.*, cat.name as category_name 
            FROM courses c 
            LEFT JOIN course_categories cat ON c.category_id = cat.id 
            WHERE c.instructor_id = ? 
            ORDER BY c.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="12" cellspacing="0" style="width:100%; border-collapse:collapse;">
            <tr style="background:#f8f9fa;">
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                <td><?= htmlspecialchars($row['category_name'] ?? 'N/A') ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No courses created yet. Create your first course above.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>