<!DOCTYPE html>
<html>
<head>
    <title>Completed Donations</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .header-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title h1 {
            margin: 0;
        }
        .btn-custom {
            background-color: #28a745;
            color: #fff;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="header-title mb-4">
            <h1>Completed Donations | Project: {{ $project }} - </h1>
        </div>
        <h2 class="text-secondary mb-4"><b>Sandbox - Processed Donations</b></h2>
        
        @if(count($tasks) > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Task ID</th>
                            <th scope="col">Task Name</th>
                            <th scope="col">Task Completed</th>
                            <th scope="col">Custom Fields</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($tasks as $task)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $task['data']['gid'] }}</td>
                                <td>{{ $task['data']['name'] }}</td>
                                <td>@if($task['data']['completed'] == 1) True @else False @endif</td>
                                <td>
                                    <a target="_blank" href="/tasks/{{$task['data']['gid']}}/details" class="btn btn-sm btn-info">Action</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info" role="alert">
                No completed Donations found.
            </div>
        @endif
    </div>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
