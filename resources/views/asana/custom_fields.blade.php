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
            <h1>Task: <b>{{$data['name']}}</b></h1>
        </div>
        <h2 class="text-secondary mb-4">Total Number of Fields: <b>{{count($data['custom_fields'])}}</b></h2>
        
        @if(count($data['custom_fields']) > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Field ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Display Value</th>
                            <th scope="col">Field Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($data['custom_fields'] as $field)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $field['gid'] }}</td>
                                <td>{{ $field['name'] }}</td>
                                <td>{{ $field['display_value'] }}</td>
                                <td>{{ $field['type'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info" role="alert">
                No custom fields found.
            </div>
        @endif
    </div>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
