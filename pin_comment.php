<?php
require 'config.php';
$comment_id = $_POST['comment_id'];
$is_pinned = $_POST['is_pinned'] ? 0 : 1; // Toggle pin status

$stmt = $conn->prepare("UPDATE comments SET is_pinned = ? WHERE comment_id = ?");
$stmt->bind_param("ii", $is_pinned, $comment_id);
$stmt->execute();
echo json_encode(['success' => $stmt->affected_rows > 0, 'is_pinned' => $is_pinned]);
