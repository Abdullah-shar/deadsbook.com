<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Function to calculate time difference in a human-readable format
function time_ago($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];

    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// Fetch posts from the database (joining user and deceased tables for names)
$sql = "SELECT p.post_id, p.post_content, p.post_image, p.post_date, u.name AS poster_name, d.name AS deceased_name
        FROM posts p
        JOIN users u ON p.user_id = u.user_id
        JOIN deceased_profiles d ON p.deceased_id = d.profile_id
        ORDER BY p.post_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deadsbook - Home</title>

    <link rel="icon" type="image/png" href="my-favicon/favicon-48x48.png" sizes="48x48" />
    <link rel="icon" type="image/svg+xml" href="my-favicon/favicon.svg" />
    <link rel="shortcut icon" href="my-favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/my-favicon/apple-touch-icon.png" />
    <link rel="manifest" href="my-favicon/site.webmanifest" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>



</head>

<body>
    <!-- <div class="dashboard-background"></div> -->

    <!-- Navbar -->
    <?php include './includes/navbar.php'; ?>

    <!-- Main content - Feed -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="post-box">
                    <div class="profile-section mt-2">
                        <img src="assets/images/<?php echo $_SESSION['profile_pic'] ?: 'default_user.svg'; ?>" alt="User Profile Picture" class="profile-img" style="width: 40px; height: 40px; object-fit: cover;">

                        <input type="text" class="post-input open-post-modal" onclick="openNewModal()" placeholder="What's on your mind, <?php echo $_SESSION['name']; ?>?" readonly>
                        <!-- New Post Modal -->
                        <div id="newPostModal" class="custom-modal">
                            <div class="custom-modal-content">
                                <!-- Modal Header -->

                                <span class="custom-close-btn" onclick="closeNewModal()">&times;</span>

                                <div class="modal-header">

                                    <span class="modal-title">Create Post</span>

                                </div>

                                <div class="custom-modal-header">
                                    <div class="custom-profile-section">
                                        <img src="assets/images/<?php echo $_SESSION['profile_pic']; ?>" alt="Profile Picture" class="custom-profile-pic">
                                        <div>
                                            <span class="custom-profile-name"><?php echo $_SESSION['name']; ?></span>
                                            <div class="custom-privacy">
                                                <i class="fa fa-globe"></i> Public
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-container">

                                        <div id="dropdownMenu" class="dropdown-content hidden">
                                            <label id="listbox-label" class="block text-sm/6 font-medium text-gray-900">Assigned to</label>
                                            <div class="relative mt-2">
                                                <button type="button" class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm/6" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label">
                                                    <span class="flex items-center">
                                                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-5 w-5 flex-shrink-0 rounded-full">
                                                        <span class="ml-3 block truncate">Tom Cook</span>
                                                    </span>
                                                    <span class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
                                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M10.53 3.47a.75.75 0 0 0-1.06 0L6.22 6.72a.75.75 0 0 0 1.06 1.06L10 5.06l2.72 2.72a.75.75 0 1 0 1.06-1.06l-3.25-3.25Zm-4.31 9.81 3.25 3.25a.75.75 0 0 0 1.06 0l3.25-3.25a.75.75 0 1 0-1.06-1.06L10 14.94l-2.72-2.72a.75.75 0 0 0-1.06 1.06Z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </button>
                                                <ul class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-option-3">
                                                    <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" id="listbox-option-0" role="option">
                                                        <div class="flex items-center">
                                                            <img src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-5 w-5 flex-shrink-0 rounded-full">
                                                            <span class="ml-3 block truncate font-normal">Wade Cooper</span>
                                                        </div>
                                                        <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
                                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- Post Input Area -->
                                <form id="postForm" onsubmit="return submitPost();" enctype="multipart/form-data">
                                    <textarea class="custom-post-input" rows="2" id="postContent" name="post_content" placeholder="What's on your mind, <?php echo $_SESSION['name']; ?>?" oninput="togglePostButton()"></textarea>

                                    <!-- Preview section for the selected media -->
                                    <div id="mediaPreviewContainer" class="image-preview-container">
                                        <span class="custom-close-btn" onclick="removeMedia()">&times;</span>
                                        <div id="mediaPreview" class="media-p"></div> <!-- Flex container for media previews -->
                                    </div>

                                    <!-- Add to Post Options -->
                                    <div class="custom-add-post" onclick="document.getElementById('postImage').click()">
                                        <span>Add Photo/Video to your post &nbsp;</span>
                                        <div class="custom-icons">
                                            <img src="assets/images/svg/media.png" alt="Media icon">
                                        </div>
                                        <input type="file" id="postImage" name="post_image" accept="image/*,video/*" multiple style="display: none;" onchange="displayMediaPreview(event)">
                                    </div>

                                    <!-- Post Button -->
                                    <div class="custom-modal-footer">
                                        <button class="custom-post-btn" id="postButton" disabled>Post</button>
                                    </div>
                                </form>

                                <script>
                                    function displayMediaPreview(event) {
                                        const mediaPreviewContainer = document.getElementById('mediaPreviewContainer');
                                        const mediaPreview = document.getElementById('mediaPreview');
                                        mediaPreview.innerHTML = ''; // Clear previous previews
                                        mediaPreviewContainer.style.display = 'block'; // Show the container
                                        const imagePreview = document.getElementById('imagePreview');

                                        Array.from(event.target.files).forEach(file => {
                                            const fileURL = URL.createObjectURL(file);
                                            const mediaElement = file.type.startsWith('image/') ?
                                                `<img src="${fileURL}" alt="Selected image" style="max-width: 600px; max-height: 100px; margin-right: 10px; border-radius: 5px;">` : // Adjust max-width here
                                                `<video controls style="max-width: 150px; max-height: 100px; margin-right: 10px; border-radius: 5px;"><source src="${fileURL}" type="${file.type}"></video>`; // Adjust max-width here
                                            mediaPreview.innerHTML += mediaElement; // Add media element to the preview container

                                        });
                                        togglePostButton();
                                    }

                                    function removeMedia() {
                                        document.getElementById('mediaPreviewContainer').style.display = 'none';
                                        document.getElementById('mediaPreview').innerHTML = ''; // Clear previews
                                        document.getElementById('postImage').value = ''; // Clear the file input
                                        togglePostButton();
                                    }
                                </script>

                            </div>
                        </div>

                    </div>

                    <hr>
                    <div class="post-actions">

                        <div class="post-action">
                            <img src="assets/images/svg/media.png" alt="Media icon">

                            <span> &nbsp; Photo/video</span>
                        </div>
                        <div class="post-action">
                            <img src="assets/images/svg/live.png" alt="Live video icon">
                            <span>&nbsp; Live video</span>
                        </div>
                    </div>
                </div>

                <!-- Loop through the posts -->
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <!-- Poster and deceased info with sweet words -->
                                <div style="display: flex; align-items: center;">
                                    <img src="assets/images/<?php echo $_SESSION['profile_pic'] ?: 'default_user.svg'; ?>" alt="Poster Picture" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right:5px;">
                                    <h6 class="card-title">

                                        <?php echo htmlspecialchars($row['poster_name']); ?>
                                        <span class="text-muted">in loving memory of</span>
                                        <?php echo htmlspecialchars($row['deceased_name']); ?>
                                        <br>
                                        <span class="text-muted post-time" style="font-size: smaller !important;"><?php echo time_ago($row['post_date']); ?></span>

                                    </h6>
                                </div>
                                <!-- Post content -->
                                <p class="card-text">
                                    <?php echo htmlspecialchars($row['post_content']); ?>
                                </p>



                                <!-- Post image (if any) -->

                                <img src="<?php echo $row['post_image']; ?>" class="img-fluid rounded mb-3" alt="Post Image">


                                <!-- Candle button and comment section -->
                                <?php
                                $post_id = $row['post_id'];
                                $candle_sql = "SELECT COUNT(*) AS candle_count FROM candles WHERE post_id = '$post_id'";
                                $candle_result = mysqli_query($conn, $candle_sql);
                                $candle_count = mysqli_fetch_assoc($candle_result)['candle_count'];

                                $user_id = $_SESSION['user_id'];
                                $user_candle_sql = "SELECT * FROM candles WHERE post_id = '$post_id' AND user_id = '$user_id'";
                                $user_candle_result = mysqli_query($conn, $user_candle_sql);
                                $has_lit_candle = mysqli_num_rows($user_candle_result) > 0;
                                ?>

                                <form method="POST" action="#" class="d-inline" id="candle-form-<?php echo $post_id; ?>">
                                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                    <button type="button" class="btn btn-warning candle-button" data-post-id="<?php echo $post_id; ?>" <?php echo $has_lit_candle ? 'data-lit="true"' : ''; ?>>
                                        Send Flowers 💐 (<?php echo $candle_count; ?>)
                                    </button>
                                </form>


                                <button class="btn btn-link text-dark" data-bs-toggle="collapse" data-bs-target="#comments-<?php echo $post_id; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
                                        <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105" />
                                    </svg>
                                </button>



                                <!-- Comments section -->
                                <div id="comments-<?php echo $post_id; ?>" class="collapse">
                                    <h5>Comments</h5>
                                    <?php
                                    $comments_sql = "SELECT c.comment, u.name, c.created_at, u.profile_pic
                                                     FROM comments c
                                                     JOIN users u ON c.user_id = u.user_id
                                                     WHERE c.post_id = '$post_id'
                                                     ORDER BY c.created_at ASC";
                                    $comments_result = mysqli_query($conn, $comments_sql);
                                    ?>
                                    <?php if (mysqli_num_rows($comments_result) > 0): ?>
                                        <ul class="list-group mb-3">
                                            <?php while ($comment_row = mysqli_fetch_assoc($comments_result)): ?>
                                                <li class="list-group-item comment-box">
                                                    <img src="assets/images/<?php echo htmlspecialchars($comment_row['profile_pic']); ?>" alt="Profile Picture">
                                                    <div>
                                                        <div class="comment-content">
                                                            <strong><?php echo htmlspecialchars($comment_row['name']); ?></strong>
                                                            <p><?php echo htmlspecialchars($comment_row['comment']); ?></p>
                                                        </div>
                                                        <div class="comment-actions">
                                                            <p class="comment-time" data-created-at="<?php echo $comment_row['created_at']; ?>">
                                                                <?php echo time_ago($comment_row['created_at']); ?>
                                                            </p>
                                                            <div class="like-reply">
                                                                <span>Like</span>
                                                                <span>Reply</span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No comments yet.</p>
                                    <?php endif; ?>

                                    <!-- Add comment form -->
                                    <form method="POST" action="add_comment.php">
                                        <div class="add-comment-box mb-3">
                                            <textarea class="form-control" name="comment" rows="2" placeholder="Add a comment..." required></textarea>
                                        </div>
                                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No posts to display.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function togglePostButton() {
            const textarea = document.getElementById("postContent");
            const postImage = document.getElementById("postImage");
            const postButton = document.getElementById("postButton");
            postButton.disabled = textarea.value.trim() === "" && postImage.files.length === 0;
            textarea.addEventListener('input', togglePostButton);

            postImage.addEventListener('change', () => {
                togglePostButton();
            });
        }

        // Run the update function on page load
        document.addEventListener('DOMContentLoaded', updateTimeAgo);

        // Optionally, update every minute to reflect more accurate "time ago"
        setInterval(updateTimeAgo, 60000);

        // Event listener for form submission
        document.querySelectorAll('form[action="add_comment.php"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const formData = new FormData(this);
                const postId = formData.get('post_id');
                const commentTextArea = this.querySelector('textarea[name="comment"]');

                fetch('add_comment.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Create the new comment HTML structure
                            const newComment = document.createElement('li');
                            newComment.classList.add('list-group-item', 'comment-box');

                            newComment.innerHTML = `
                        <img src="assets/images/${data.profile_pic}" alt="Profile Picture" class="rounded-circle" style="width: 40px; height: 40px; margin-right: 10px;">
                        <div>
                            <div class="comment-content">
                                <strong>${data.name}</strong>
                                <p>${data.comment}</p>
                            </div>
                            <p class="comment-time">${data.created_at}</p>
                            <div class="like-reply">
                                <span>Like</span>
                                <span>Reply</span>
                            </div>
                        </div>
                    `;

                            // Find the comments list for this post and append the new comment
                            const commentList = document.querySelector(`#comments-${postId} .list-group`);
                            if (commentList) {
                                commentList.appendChild(newComment);
                            }

                            // Clear the textarea
                            commentTextArea.value = '';
                        } else {
                            alert('Failed to add comment.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        document.querySelectorAll('.candle-button').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                const isLit = this.getAttribute('data-lit') === 'true';

                fetch('toggle_candle.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `post_id=${postId}&lit=${!isLit}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the button and candle count
                            this.setAttribute('data-lit', !isLit);
                            this.textContent = `Send Flowers 💐 (${data.new_count})`;
                        } else {
                            alert('Failed to update Flowers status.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>