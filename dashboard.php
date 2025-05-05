<?php
require_once 'config/database.php';
require_once 'includes/functions.php';


requireLogin();


$statusFilter = isset($_GET['status']) ? cleanInput($_GET['status']) : '';
$dateFilter = isset($_GET['date']) ? cleanInput($_GET['date']) : '';


$tasks = getTasks($conn, $_SESSION['user_id'], $statusFilter, $dateFilter);

include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>My Tasks</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="add_task.php" class="btn btn-primary">Add New Task</a>
    </div>
</div>

<div class="filters">
    <form id="filter-form" method="GET" action="dashboard.php">
        <div class="row">
            <div class="col-md-5">
                <label for="status" class="form-label">Filter by Status:</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="Pending" <?php if ($statusFilter === 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="In Progress" <?php if ($statusFilter === 'In Progress') echo 'selected'; ?>>In Progress</option>
                    <option value="Completed" <?php if ($statusFilter === 'Completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>
            <div class="col-md-5">
                <label for="date" class="form-label">Filter by Date:</label>
                <select name="date" id="date" class="form-select">
                    <option value="">All Dates</option>
                    <option value="past" <?php if ($dateFilter === 'past') echo 'selected'; ?>>Past Deadline</option>
                    <option value="today" <?php if ($dateFilter === 'today') echo 'selected'; ?>>Today</option>
                    <option value="upcoming" <?php if ($dateFilter === 'upcoming') echo 'selected'; ?>>Upcoming</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary w-100">Apply Filters</button>
            </div>
        </div>
    </form>
</div>

<?php if (empty($tasks)): ?>
    <div class="alert alert-info">No tasks found. <a href="add_task.php">Add a new task</a> to get started!</div>
<?php else: ?>
    <div class="row">
        <?php foreach ($tasks as $task): ?>
            <?php
                $statusClass = '';
                if ($task['status'] === 'Pending') {
                    $statusClass = 'bg-warning';
                } elseif ($task['status'] === 'In Progress') {
                    $statusClass = 'bg-primary';
                } else {
                    $statusClass = 'bg-success';
                }
                
                $isPastDeadline = strtotime($task['deadline']) < strtotime(date('Y-m-d')) && $task['status'] !== 'Completed';
                $isCompleted = $task['status'] === 'Completed';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card task-card <?php if ($isCompleted) echo 'completed'; ?>">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($task['title']); ?></h5>
                        <span class="badge <?php echo $statusClass; ?> status-badge"><?php echo $task['status']; ?></span>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
                        <p>
                            <strong>Deadline:</strong> 
                            <span class="<?php if ($isPastDeadline) echo 'past-deadline'; ?>">
                                <?php echo date('M d, Y', strtotime($task['deadline'])); ?>
                                <?php if ($isPastDeadline): ?> (Overdue)<?php endif; ?>
                            </span>
                        </p>
                        <div class="form-check">
                            <input class="form-check-input task-status-checkbox" type="checkbox" 
                                   id="task-<?php echo $task['id']; ?>" 
                                   data-task-id="<?php echo $task['id']; ?>"
                                   <?php if ($isCompleted) echo 'checked'; ?>>
                            <label class="form-check-label" for="task-<?php echo $task['id']; ?>">
                                Mark as Completed
                            </label>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="task_actions.php?action=delete&id=<?php echo $task['id']; ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>