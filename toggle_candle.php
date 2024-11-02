<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $lit = $_POST['lit'] === 'true' ? 1 : 0; // Convert to 1 or 0

    // Toggle the candle status in the database
    if ($lit) {
        // Light the candle
        $sql = "INSERT INTO candles (post_id, user_id) VALUES ('$post_id', '$user_id')";
    } else {
        // Unlight the candle
        $sql = "DELETE FROM candles WHERE post_id = '$post_id' AND user_id = '$user_id'";
    }

    if (mysqli_query($conn, $sql)) {
        // Count the number of candles for the post
        $count_sql = "SELECT COUNT(*) AS candle_count FROM candles WHERE post_id = '$post_id'";
        $count_result = mysqli_query($conn, $count_sql);
        $new_count = mysqli_fetch_assoc($count_result)['candle_count'];

        echo json_encode(['success' => true, 'new_count' => $new_count]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
