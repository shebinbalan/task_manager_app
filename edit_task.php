<?php
require_once 'config/database.php';
require_once 'includes/functions.php';


requireLogin();


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$taskId = intval($_GET['id']);
$task = getTask($conn, $taskId, $_SESSION['user_id']);

if (!$task) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $title = cleanInput($_POST['title']);
    $description = cleanInput($_POST['description']);
    $deadline = cleanInput($_POST['deadline']);
    $status = cleanInput($_POST['status']);
    
    // Check required fields
    if (empty($title) || empty($deadline)) {
        $error = "Title and deadline are required.";
    } else {
        // Update task in database
        $sql = "UPDATE tasks SET title = ?, description = ?, deadline = ?, status = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssii", $title, $description, $deadline, $status, $taskId, $_SESSION['user_id']);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Task updated successfully!";
            // Refresh task data
            $task = getTask($conn, $taskId, $_SESSION['user_id']);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
    }
}

include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>Edit Task</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $taskId; ?>" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="deadline" name="deadline" value="<?php echo $task['deadline']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="Pending" <?php if ($task['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="In Progress" <?php if ($task['status'] === 'In Progress') echo 'selected'; ?>>In Progress</option>
                    <option value="Completed" <?php if ($task['status'] === 'Completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>