<?php include 'header.php'; ?>

<h1>📚 Learning Materials</h1>

<div class="card">
    <h3>Available Materials</h3>
    
    <?php
    $sql = "SELECT m.*, c.title as course_title 
            FROM materials m 
            JOIN courses c ON m.course_id = c.id 
            ORDER BY m.created_at DESC";
    
    $result = $conn->query($sql);
    ?>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="12" cellspacing="0" style="width:100%; border-collapse:collapse;">
            <tr style="background:#f8f9fa;">
                <th>Material Title</th>
                <th>Course</th>
                <th>File Type</th>
                <th>Uploaded</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                <td><?= htmlspecialchars($row['course_title']) ?></td>
                <td><strong>.<?= strtoupper($row['file_type']) ?></strong></td>
                <td><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="<?= $row['file_path'] ?>" target="_blank" 
                       style="color:#3498db; text-decoration:none;">📥 Download</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No materials uploaded yet.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>