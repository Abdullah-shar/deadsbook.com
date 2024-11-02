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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile - Deadsbook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="my-favicon/favicon-48x48.png" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="my-favicon/favicon.svg" />
    <link rel="shortcut icon" href="my-favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/my-favicon/apple-touch-icon.png" />
    <link rel="manifest" href="my-favicon/site.webmanifest" />
</head>

<body>
    <!-- Navbar (reuse from dashboard) -->
    <?php include './includes/navbar.php'; ?>

    <main class="container mt-5">
        <div class="card mx-auto shadow-sm" style="max-width: 600px;">
            <img src="assets/images/<?php echo $profile['cover_pic'] ?: 'default.jpg'; ?>" class="card-img-top" alt="Profile Image" style="width: 100%; height: 250px; object-fit: cover;">
            <div class="card-body text-center">
                <h5 class="card-title"><?php echo htmlspecialchars($profile['name']); ?></h5>
                <p class="card-text">Born: <?php echo htmlspecialchars($profile['birth_date']); ?></p>
                <p class="card-text">Passed: <?php echo htmlspecialchars($profile['death_date']); ?></p>
                <p class="card-text">Bio: <?php echo htmlspecialchars($profile['bio']); ?></p>
                <!-- Back Button -->

                <?php echo '<a href="edit_profile.php?id=' . $profile['profile_id'] . '" class="btn btn-secondary">Edit</a>'; ?>
                <a href="javascript:history.back()" class="btn btn-secondary">Back</a>

            </div>


        </div>
    </main>

    <!-- Footer -->
    <?php include './includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>