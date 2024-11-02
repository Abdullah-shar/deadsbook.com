<!-- Navbar -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 dashboard-content">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="./assets/images/site images/deadsbook_logo.png" alt="Deadsbook Logo" style="width: 150px; height: auto;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">Loved ones</a>
                </li>
            </ul>

            <!-- Notification Bell Icon -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fa-lg text-dark" style="margin-bottom:-40px;"></i>
                        <!-- Notification count badge -->
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notification-list" style="min-width: 250px;">
                        <li class="text-center"><a class="dropdown-item" href="#">No new notifications</a></li>
                    </ul>
                </li>

                <!-- Dropdown for Profile Picture and Name -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="assets/images/<?php echo $_SESSION['profile_pic'] ?: 'default_user.svg'; ?>" alt="User Profile Picture" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile_update.php">My Profile</a></li>
                        <li><a class="dropdown-item" href="dashboard.php">Loved ones</a></li>
                        <li><a class="dropdown-item" href="add_deceased.php">Add Loved-one</a></li>
                        <hr class="dropdown-divider">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- JavaScript to Fetch Notifications Using AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function fetchNotifications() {
            $.ajax({
                url: "fetch_notifications.php",
                method: "GET",
                success: function(data) {
                    let notifications = JSON.parse(data);
                    let notificationCount = notifications.length;

                    // Update notification count (only show badge if there are notifications)
                    $("#notification-count").text(notificationCount > 0 ? notificationCount : "").toggle(notificationCount > 0);

                    // Update notification list (show up to 5 notifications)
                    let notificationList = notifications.slice(0, 5).map(function(notification) {
                        return `<li><a class="dropdown-item" href="#">${notification.message}</a></li>`;
                    });
                    $("#notification-list").html(notificationList.length ? notificationList.join('') : '<li class="text-center"><a class="dropdown-item" href="#">No new notifications</a></li>');

                    // Add "View More" button if there are more than 5 notifications
                    if (notifications.length > 5) {
                        $("#notification-list").append('<li><a class="dropdown-item text-center" href="view_all_notifications.php">View More</a></li>');
                    }
                }
            });
        }

        // Fetch notifications every 10 seconds
        setInterval(fetchNotifications, 10000);
        fetchNotifications();
    });
</script>