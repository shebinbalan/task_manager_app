<?php
require_once 'config/database.php';
require_once 'includes/functions.php';


requireLogin();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    if ($_POST['action'] === 'update_status') {
        $taskId = intval($_POST['task_id']);
        $status = cleanInput($_POST['status']);

     
        if (!in_array($status, ['Pending', 'In Progress', 'Completed'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid status value']);
            exit;
        }

        // Update task status
        $sql = "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $status, $taskId, $_SESSION['user_id']);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        }

        mysqli_stmt_close($stmt);
        exit;
    }
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $taskId = intval($_GET['id']);

    $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $taskId, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: dashboard.php");
    exit;
}

// Redirect if no action matched
header("Location: dashboard.php");
exit;
