<?php
session_start();
include 'config.php'; // Adjust as needed

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $response["message"] = "User not logged in.";
        echo json_encode($response);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $deceased_id = $_POST['deceased_id'] ?? null;
    $post_content = $_POST['post_content'] ?? '';
    $post_date = date('Y-m-d H:i:s');
    $candles = 0;
    $post_image = null;

    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
        $image_name = $_FILES['post_image']['name'];
        $image_tmp_name = $_FILES['post_image']['tmp_name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image_name);

        if (move_uploaded_file($image_tmp_name, $target_file)) {
            $post_image = $target_file;
        } else {
            $response["message"] = "Failed to upload image.";
            echo json_encode($response);
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO posts (user_id, deceased_id, post_content, post_image, post_date, candles) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssi", $user_id, $deceased_id, $post_content, $post_image, $post_date, $candles);

    if ($stmt->execute()) {
        $response["success"] = true;
    } else {
        $response["message"] = "Error adding post: " . $stmt->error;
    }

    $stmt->close();
}

echo json_encode($response);
