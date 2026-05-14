<?php include 'header.php'; ?>

<h1>📝 Assignments</h1>

<?php
// Handle Assignment Creation (Only Instructors & Admin)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_assignment'])) {
    if ($role_id == 1 || $role_id == 2) {
        $course_id = intval($_POST['course_id']);
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $deadline = $_POST['deadline'];

        $sql = "INSERT INTO assignments (course_id, title, description, deadline) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $course_id, $title, $description, $deadline);

        if ($stmt->execute()) {
            echo "<p style='color:green; padding:15px; background:#d4edda; border-radius:5px;'>✅ Assignment created successfully!</p>";
        } else {
            echo "<p style='color:red;'>Failed to create assignment.</p>";
        }
    }
}
?>

<!-- Create Form - Only for Instructors -->
<?php if ($role_id == 1 || $role_id == 2): ?>
<div class="card">
    <h3>Create New Assignment</h3>
    <form method="POST">
        <select name="course_id" required style="width:100%; padding:12px; margin:8px 0;">
            <?php
            $courses = $conn->query("SELECT id, title FROM courses WHERE instructor_id = ".$_SESSION['user_id']);
            while($c = $courses->fetch_assoc()) {
                echo "<option value='".$c['id']."'>".$c['title']."</option>";
            }
            ?>
        </select>
        <input type="text" name="title" placeholder="Assignment Title" required style="width:100%; padding:12px; margin:8px 0;">
        <textarea name="description" placeholder="Description / Instructions" rows="4" style="width:100%; padding:12px; margin:8px 0;"></textarea>
        <input type="datetime-local" name="deadline" required style="width:100%; padding:12px; margin:8px 0;">
        
        <button type="submit" name="create_assignment" 
                style="padding:14px 30px; background:#e74c3c; color:white; border:none; border-radius:8px; margin-top:10px;">
            Create Assignment
        </button>
    </form>
</div>
<?php endif; ?>

<!-- Assignments List -->
<div class="card">
    <h3><?= ($role_id == 1 || $role_id == 2) ? 'My Created Assignments' : 'Available Assignments' ?></h3>
    
    <?php
    if ($role_id == 1 || $role_id == 2) {
        $sql = "SELECT a.*, c.title as course_title 
                FROM assignments a 
                JOIN courses c ON a.course_id = c.id 
                WHERE c.instructor_id = ? 
                ORDER BY a.deadline ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
    } else {
        // For Students - Show all assignments
        $sql = "SELECT a.*, c.title as course_title 
                FROM assignments a 
                JOIN courses c ON a.course_id = c.id 
                ORDER BY a.deadline ASC";
        $stmt = $conn->prepare($sql);
        // No bind_param needed for students
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='12' style='width:100%; border-collapse:collapse;'>";
        echo "<tr><th>Title</th><th>Course</th><th>Deadline</th><th>Action</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['course_title']) . "</td>";
            echo "<td>" . date('M j, Y H:i', strtotime($row['deadline'])) . "</td>";
            echo "<td><a href='submit_assignment.php?id=" . $row['id'] . "'>Submit</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No assignments available yet.</p>";
    }
    ?>
</div>

<?php include 'footer.php'; ?>