<?php
session_start(); // Start the session
include 'config.php'; // Include the database connection file

// Initialize message variable
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id']; // Use 'user_id' instead of 'id'
            $_SESSION['user_name'] = $user['name'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Invalid password!</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>No user found with this email!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Deadsbook</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Link to external CSS -->
    <link rel="icon" type="image/png" href="my-favicon/favicon-48x48.png" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="my-favicon/favicon.svg" />
    <link rel="shortcut icon" href="my-favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/my-favicon/apple-touch-icon.png" />
    <link rel="manifest" href="my-favicon/site.webmanifest" />
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Login to Your Account</h2>
            <?php if ($message): ?>
                <?php echo $message; ?>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
            </form>
            <p class="text-center mt-3 mb-0">Don't have an account? <a href="register.php">Register</a></p>
            <p class="text-center mt-2"><a href="#">Forgot your password?</a></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>