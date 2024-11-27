<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asana Project Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .task-details {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Asana Project Search</h2>
        <form id="searchForm">
            <div class="mb-3">
                <label for="searchQuery" class="form-label">Search</label>
                <input type="text" class="form-control" id="searchQuery" placeholder="Enter project name, task name, or custom field">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="mt-5" id="searchResults">
            <h3>Search Results</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Task Name</th>
                        <th>Custom Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody id="resultsTable">
                    <!-- Results will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                let query = $('#searchQuery').val().trim();
                
                if (query !== '') {
                    $.ajax({
                        url: '/search',
                        type: 'GET',
                        data: { query: query },
                        success: function(data) {
                            $('#resultsTable').empty();
                            alert(data);
                            if (data.length > 0) {
                                data.forEach(item => {
                                    $('#resultsTable').append(`
                                        <tr>
                                            <td>${item.project_name}</td>
                                            <td>${item.task_name}</td>
                                            <td>${item.custom_field_name}</td>
                                            <td>${item.custom_field_value}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                $('#resultsTable').append('<tr><td colspan="4">No results found</td></tr>');
                            }
                        },
                        error: function() {
                            alert('Error occurred while fetching data');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
