function toggleDropdown() {
    const dropdownMenu = document.getElementById('dropdownMenu');
    dropdownMenu.classList.toggle('hidden');
  

  }
function displayMediaPreview(event) {
    const mediaPreview = document.getElementById('mediaPreview');
    const file = event.target.files[0];
    
    if (file) {
        mediaPreview.style.display = 'block';
        mediaPreview.innerHTML = ''; // Clear any previous preview

        const fileURL = URL.createObjectURL(file);
        
        if (file.type.startsWith('image/')) {
            mediaPreview.innerHTML = `<img src="${fileURL}" alt="Selected image" style="max-width: 100%; height: auto;">`;
        } else if (file.type.startsWith('video/')) {
            mediaPreview.innerHTML = `<video controls style="max-width: 100%; height: auto;"><source src="${fileURL}" type="${file.type}"></video>`;
        }
    } else {
        mediaPreview.style.display = 'none';
    }
}

function timeAgo(datetime) {
    const now = new Date();
    const diff = Math.floor((now - new Date(datetime)) / 1000);

    const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60,
        second: 1,
    };

    for (const key in intervals) {
        const interval = Math.floor(diff / intervals[key]);
        if (interval >= 1) {
            return `${interval} ${key}${interval > 1 ? 's' : ''} ago`;
        }
    }

    return 'just now';
}

function updateTimeAgo() {
    document.querySelectorAll('.comment-time').forEach(function (element) {
        const createdAt = element.getAttribute('data-created-at');
        element.textContent = timeAgo(createdAt);
    });
}

// Run the update function on page load
document.addEventListener('DOMContentLoaded', updateTimeAgo);

// Optionally, update every minute to reflect more accurate "time ago"
setInterval(updateTimeAgo, 60000);

// JavaScript to open and close the modal
function openNewModal() {
    document.getElementById("newPostModal").style.display = "block";
}

function closeNewModal() {
    document.getElementById("newPostModal").style.display = "none";
}

window.onclick = function(event) {
    var modal = document.getElementById("newPostModal");
    if (event.target === modal) {
        closeNewModal();
    }
};

// JavaScript to handle post submission

 function submitPost() {
            const form = document.getElementById("postForm");
            const formData = new FormData(form);

            fetch('add_post.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        window.location.reload();

                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });

            return false; // Prevent default form submission
        }


  
 
 


  