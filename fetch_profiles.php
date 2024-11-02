<?php
include 'congig.php';

$user_id = $_SESSION['user_id']; // Get current user ID
$sql = "SELECT profile_id, name, birth_date, death_date FROM deceased_profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$profiles = [];
while ($row = $result->fetch_assoc()) {
    $profiles[] = $row;
}

echo json_encode($profiles);
