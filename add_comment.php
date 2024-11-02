<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id']; // Assuming user is logged in and their ID is stored in session

    // Insert the comment into the database
    $sql = "INSERT INTO comments (post_id, user_id, comment, created_at) VALUES ('$post_id', '$user_id', '$comment', NOW())";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the user's profile pic and name for the JSON response
        $user_sql = "SELECT name, profile_pic FROM users WHERE user_id = '$user_id'";
        $user_result = mysqli_query($conn, $user_sql);
        $user_data = mysqli_fetch_assoc($user_result);

        $response = [
            'success' => true,
            'name' => $user_data['name'],
            'profile_pic' => $user_data['profile_pic'],
            'created_at' => date('F d, Y H:i'), // Format the time to be displayed on the comment
            'comment' => htmlspecialchars($comment)
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['success' => false]);
    }
}
