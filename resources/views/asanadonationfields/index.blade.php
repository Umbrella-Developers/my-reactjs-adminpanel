<!-- resources/views/response.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        pre {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            overflow: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Response Data</h2>
        <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
        @if(isset($stories))
            <pre>{{ json_encode($stories, JSON_PRETTY_PRINT) }}</pre>
        @endif
    </div>
</body>
</html>
