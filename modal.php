<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tag People Modal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- Modal Structure -->
    <div class="modal fade" id="tagPeopleModal" tabindex="-1" aria-labelledby="tagPeopleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tagPeopleModalLabel">Tag People</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Search Box -->
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search" aria-label="Search">
                    </div>

                    <!-- Tagged Section -->
                    <div id="taggedContainer" class="mb-3"></div>

                    <!-- Suggestions List -->
                    <div id="suggestionsContainer" class="suggestions mt-3">
                        <h6 class="text-muted">Suggestions</h6>
                        <div class="list-group">
                            <!-- Dynamic suggestions list items will be injected here by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Trigger Button -->
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#tagPeopleModal">
        Open Tag People Modal
    </button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>