<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for adding a deceased profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Get the user ID from the session
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $death_date = mysqli_real_escape_string($conn, $_POST['death_date']);
    $bio = mysqli_real_escape_string($conn, $_POST['biography']);

    // Handle profile picture upload
    $cover_pic = "";
    if (!empty($_FILES['cover_pic']['name'])) {
        $target_dir = "assets/images/";
        $cover_pic = basename($_FILES["cover_pic"]["name"]);
        $target_file = $target_dir . $cover_pic;
        move_uploaded_file($_FILES["cover_pic"]["tmp_name"], $target_file);
    }

    // Insert into database
    $insert_sql = "INSERT INTO deceased_profiles (user_id, name, birth_date, death_date, bio, cover_pic) VALUES ('$user_id', '$name', '$birth_date', '$death_date', '$bio', '$cover_pic')";
    if (mysqli_query($conn, $insert_sql)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Deceased Profile - Deadbook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            max-width: 600px;
            /* Set a maximum width for the card */
            margin: auto;
            /* Center the card */
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>
    <?php include './includes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h2>Add Deceased Profile</h2>
            </div>
            <div class="card-body">
                <!-- Deceased Profile Form -->
                <form action="add_deceased.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="death_date" class="form-label">Death Date</label>
                        <input type="date" class="form-control" id="death_date" name="death_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="biography" class="form-label">Biography</label>
                        <textarea class="form-control" id="biography" name="biography" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="cover_pic" class="form-label">Cover Picture</label>
                        <input type="file" class="form-control" id="cover_pic" name="cover_pic" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary">Add Profile</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>