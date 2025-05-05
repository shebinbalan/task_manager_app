<?php
session_start();


function isLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        return true;
    }
    return false;
}


function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: index.php");
        exit;
    }
}


function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function getTasks($conn, $userId, $statusFilter = '', $dateFilter = '') {
    $sql = "SELECT * FROM tasks WHERE user_id = ?";
    
   
    if (!empty($statusFilter)) {
        $sql .= " AND status = ?";
    }
    
   
    if (!empty($dateFilter)) {
        $today = date('Y-m-d');
        if ($dateFilter == 'past') {
            $sql .= " AND deadline < ?";
        } elseif ($dateFilter == 'today') {
            $sql .= " AND deadline = ?";
        } elseif ($dateFilter == 'upcoming') {
            $sql .= " AND deadline > ?";
        }
    }
    
    $sql .= " ORDER BY deadline ASC";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!empty($statusFilter) && !empty($dateFilter)) {
        mysqli_stmt_bind_param($stmt, "iss", $userId, $statusFilter, $today);
    } elseif (!empty($statusFilter)) {
        mysqli_stmt_bind_param($stmt, "is", $userId, $statusFilter);
    } elseif (!empty($dateFilter)) {
        mysqli_stmt_bind_param($stmt, "is", $userId, $today);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $userId);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $tasks = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
    }
    
    return $tasks;
}


function getTask($conn, $taskId, $userId) {
    $sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $taskId, $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    }
    
    return false;
}
?>