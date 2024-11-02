<?php
session_start();
include 'config.php';

if (isset($_POST['post_id']) && isset($_POST['lit'])) {
    $post_id = $_POST['post_id'];
    $lit = $_POST['lit'];
    $user_id = $_SESSION['user_id'];

    if ($lit == 1) {
        // Light the candle (add entry)
        $sql = "INSERT INTO candles (post_id, user_id) VALUES ('$post_id', '$user_id')";
    } else {
        // Unlight the candle (remove entry)
        $sql = "DELETE FROM candles WHERE post_id = '$post_id' AND user_id = '$user_id'";
    }

    mysqli_query($conn, $sql);

    // Get the updated candle count
    $count_sql = "SELECT COUNT(*) AS candle_count FROM candles WHERE post_id = '$post_id'";
    $count_result = mysqli_query($conn, $count_sql);
    $candle_count = mysqli_fetch_assoc($count_result)['candle_count'];

    // Respond with success and updated candle count
    echo json_encode(['success' => true, 'candle_count' => $candle_count]);
}
