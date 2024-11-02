<?php
include 'config.php'; // Include your database connection file

// SQL query to retrieve unread notifications
$sql = "SELECT * FROM notify WHERE is_read = 0 ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$notifications = array();
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

// Encode data as JSON
echo json_encode($notifications);
