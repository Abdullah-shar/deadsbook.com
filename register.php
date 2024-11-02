<?php
include 'config.php'; // Include the database connection file

// Initialize message variable
$message = '';
$registrationSuccess = false; // Flag to track registration status

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash the password

    $sql = "INSERT INTO users (name, email, password_hash) VALUES ('$name', '$email', '$password')";

    try {
        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert alert-success'>Registration successful! You can Login Now!</div>";
            $registrationSuccess = true; // Set the registration success flag
        }
    } catch (mysqli_sql_exception $e) {
        // Set the message for errors (like duplicate entry)
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Deadsbook</title>
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
            <?php if (!$registrationSuccess): // If not registered successfully 
            ?>
                <h2 class="text-center mb-4">Create Your Account</h2>
                <!-- Alert message positioned below the heading -->
                <?php if ($message): ?>
                    <?php echo $message; ?>
                <?php endif; ?>

                <form method="POST" action="register.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login</a></p>
            <?php else: // If registered successfully 
            ?>
                <?php echo $message; ?>
                <a href="login.php" class="btn btn-primary w-100 mt-3">Login Now</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>