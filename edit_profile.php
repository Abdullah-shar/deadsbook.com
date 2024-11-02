<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the profile details based on the ID from the URL
$profile_id = $_GET['id'];
$sql = "SELECT * FROM deceased_profiles WHERE profile_id = '$profile_id'";
$result = mysqli_query($conn, $sql);
$profile = mysqli_fetch_assoc($result);

if (!$profile) {
    echo "Profile not found!";
    exit();
}

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $birth_date = $_POST['birth_date'];
    $death_date = $_POST['death_date'];
    $bio = $_POST['bio'];

    // Check if a new image is uploaded
    if (isset($_FILES['cover_pic']) && $_FILES['cover_pic']['error'] === UPLOAD_ERR_OK) {
        // Define the target directory
        $target_dir = "assets/images/";
        $target_file = $target_dir . basename($_FILES["cover_pic"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type (optional: you can add more validation here)
        $valid_extensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $valid_extensions)) {
            // Move the file to the images directory
            if (move_uploaded_file($_FILES["cover_pic"]["tmp_name"], $target_file)) {
                // Update query with the new image
                $update_sql = "UPDATE deceased_profiles SET name='$name', birth_date='$birth_date', death_date='$death_date', bio='$bio', cover_pic='" . basename($_FILES["cover_pic"]["name"]) . "' WHERE profile_id='$profile_id'";
            } else {
                echo "Error uploading the file.";
                exit();
            }
        } else {
            echo "Invalid file type. Please upload an image file.";
            exit();
        }
    } else {
        // Update without changing the image
        $update_sql = "UPDATE deceased_profiles SET name='$name', birth_date='$birth_date', death_date='$death_date', bio='$bio' WHERE profile_id='$profile_id'";
    }

    // Execute the query
    if (mysqli_query($conn, $update_sql)) {
        header("Location: view_profile.php?id=$profile_id");
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
    <title>Edit Profile - Deadsbook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="my-favicon/favicon-48x48.png" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="my-favicon/favicon.svg" />
    <link rel="shortcut icon" href="my-favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/my-favicon/apple-touch-icon.png" />
    <link rel="manifest" href="my-favicon/site.webmanifest" />
    <style>
        .profile-image-container {
            position: relative;
            width: 100%;
            height: 250px;
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .edit-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            /* background-color: rgba(0, 0, 0, 0.5); */
            border-radius: 40%;
            padding: 15px;
            color: white;
            cursor: pointer;
        }

        .edit-icon:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .upload-option {
            margin-top: 15px;
            display: flex;
            justify-content: center;
        }

        .img-preview {
            margin-top: -150px;
            /* Remove any margin */
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
            /* Ensure no extra space is added */
            border: 1px solid #ddd;
            /* Optional border for better visibility */
            display: none;
            /* Initially hidden */
        }
    </style>
</head>

<body>
    <!-- Navbar (reuse from dashboard) -->
    <?php include './includes/navbar.php'; ?>

    <main class="container mt-5">
        <div class="card mx-auto shadow-sm" style="max-width: 600px;">
            <!-- Profile Image with Edit Icon -->
            <div class="profile-image-container">
                <img id="currentImage" src="assets/images/<?php echo $profile['cover_pic'] ?: 'default.jpg'; ?>" alt="Profile Image">
                <label for="cover_pic" class="edit-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                    </svg><!-- Bootstrap pen icon -->
                </label>
            </div>

            <!-- Preview of the new image -->
            <img id="previewImage" class="img-preview" src="" alt="Image Preview">

            <div class="card-body">
                <form action="edit_profile.php?id=<?php echo $profile_id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($profile['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" name="birth_date" class="form-control" value="<?php echo htmlspecialchars($profile['birth_date']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="death_date" class="form-label">Death Date</label>
                        <input type="date" name="death_date" class="form-control" value="<?php echo htmlspecialchars($profile['death_date']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3" required><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                    </div>

                    <!-- Image Upload Section -->
                    <input type="file" name="cover_pic" id="cover_pic" class="form-control d-none" accept="image/*" onchange="previewImage(event)">

                    <!-- Upload Option Below Form -->
                    <div class="upload-option">
                        <label for="cover_pic" class="btn btn-outline-secondary">Upload New Image</label>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Update Profile</button>
                    <a href="javascript:history.back()" class="btn btn-secondary mt-3">Back</a>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include './includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview the image when selected
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('previewImage');
            const currentImage = document.getElementById('currentImage');

            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block'; // Show the preview
                currentImage.style.display = 'none'; // Hide the current image
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>

</html>