<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Assign user ID from session to the $user_id variable
$user_id = $_SESSION['user_id'];

$sql = "SELECT profile_pic, name FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    // Assign the fetched data to session
    $_SESSION['name'] = $row['name'];

    // Check if the user has a profile pic or not
    $_SESSION['profile_pic'] = !empty($row['profile_pic']) ? $row['profile_pic'] : ''; // Keep empty if no picture is uploaded
}

// Fetch user details for deceased profiles
$sql = "SELECT * FROM deceased_profiles WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql); // Procedural MySQLi
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Deadsbook</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Link to external CSS -->
    <link rel="icon" type="image/png" href="my-favicon/favicon-48x48.png" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="my-favicon/favicon.svg" />
    <link rel="shortcut icon" href="my-favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/my-favicon/apple-touch-icon.png" />
    <link rel="manifest" href="my-favicon/site.webmanifest" />
    <!-- Add background image styling for this page -->
    <style>
        /* Ensure body takes the full height */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Dashboard specific background image */
        .dashboard-background {
            background-image: url('assets/images/coffin-background.jpg');
            background-size: cover;
            /* Cover the entire page */
            background-position: center;
            background-attachment: fixed;
            /* Keep the image fixed */
            position: fixed;
            /* Ensure the background image is fixed */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: blur(8px);
        }

        /* Main layout setup */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Ensure the body stretches to full height */
        }
    </style>
</head>

<body>
    <!-- Background image div -->
    <div class="dashboard-background"></div>

    <!-- Navbar -->
    <?php include './includes/navbar.php'; ?>

    <!-- Main content -->
    <main class="container dashboard-content">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <h2 class="fw-bold">Welcome, <?php echo $_SESSION['name']; ?>!</h2>
            </div>
        </div>
        <!-- Profiles Section -->
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <h3 class="text-center mb-4">Your Loved Ones</h3>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary mb-3">
                        <a class="nav-link text-white" href="add_deceased.php">Add Loved-one</a>
                    </button>
                </div>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <img src="assets/images/<?php echo $row['cover_pic'] ?: 'default.jpg'; ?>" class="card-img-top" alt="Profile Picture">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text text-muted">Born: <?php echo htmlspecialchars($row['birth_date']); ?></p>
                                <p class="card-text text-muted">Passed: <?php echo htmlspecialchars($row['death_date']); ?></p>
                                <p class="card-text text-muted">Bio: <?php echo htmlspecialchars($row['bio']); ?></p>
                                <?php echo '<a href="view_profile.php?id=' . $row['profile_id'] . '" class="btn btn-primary">View Profile</a>'; ?>
                                <?php echo '<a href="edit_profile.php?id=' . $row['profile_id'] . '" class="btn btn-secondary">Edit</a>'; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <h3 class="text-center">No profiles found. Please add some.</h3>
                <div class="text-center">
                    <a href="add_deceased.php" class="btn btn-success">Add New Profile</a>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <!-- Footer -->
    <?php include './includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>