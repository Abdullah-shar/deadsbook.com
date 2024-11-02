<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Handle password update if provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $update_password_sql = "UPDATE users SET password_hash='$password' WHERE user_id='$user_id'";
        mysqli_query($conn, $update_password_sql);
    }

    // Handle profile picture update if provided
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "assets/images/";
        $profile_pic = basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $profile_pic;
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);

        // Update database with new profile picture path
        $update_pic_sql = "UPDATE users SET profile_pic='$profile_pic' WHERE user_id='$user_id'";
        mysqli_query($conn, $update_pic_sql);
    }

    // Update username and email
    $update_sql = "UPDATE users SET name='$username', email='$email' WHERE user_id='$user_id'";
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['user_name'] = $username; // Update session with new username
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Deadsbook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="my-favicon/favicon-48x48.png" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="my-favicon/favicon.svg" />
    <link rel="shortcut icon" href="my-favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/my-favicon/apple-touch-icon.png" />
    <link rel="manifest" href="my-favicon/site.webmanifest" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            max-width: 400px;
            /* Set a maximum width for the card */
            margin: auto;
            /* Center the card */
        }

        .form-label {
            margin-bottom: 5px;
            /* Spacing below labels */
            text-align: left;
            /* Align text to the left */
        }

        .form-control {
            border-radius: 5px;
            width: 100%;
            /* Set input fields to 100% of the card */
        }

        .profile-img {
            max-width: 150px;
            border-radius: 50%;
        }

        .btn-update {
            display: block;
            /* Make button a block element to center */
            margin: 20px auto 0;
            /* Center the button */
        }

        /* Centering the label and input field vertically */
        .form-group {
            margin-bottom: 15px;
            /* Space between fields */
        }
    </style>
</head>

<body>

    <?php include './includes/navbar.php'; ?>
    <div class="container mt-5">


        <!-- Profile Update Card -->
        <div class="card p-4 shadow-sm">
            <h2 class="text-center mb-4">Update Profile</h2>
            <!-- Display Profile Picture -->
            <div class="mt-3 text-center">
                <?php if (!empty($user['profile_pic'])): ?>
                    <img src="assets/images/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="img-fluid profile-img">
                <?php else: ?>
                    <p>No profile picture uploaded.</p>
                <?php endif; ?>
            </div>
            <form action="profile_update.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password (leave blank if not changing)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="profile_pic" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="profile_pic" name="profile_pic">
                </div>

                <button type="submit" class="btn btn-primary btn-update">Update Profile</button>
            </form>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>