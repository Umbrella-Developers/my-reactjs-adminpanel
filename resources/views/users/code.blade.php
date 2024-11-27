<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Page</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Full height background with a gradient */
        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f06, #ff9);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Container for the form */
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            margin-bottom: 5px;
            color: #555;
        }

        input[type="email"],
        input[type="text"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="tel"]:focus,
        input[type="email"]:focus {
            border-color: #6a11cb;
        }

        /* Button styling */
        button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background: #6a11cb;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #5a0dbb;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .form-container {
                padding: 20px;
            }

            h2 {
                font-size: 18px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Code</h2>
        <form action="/auth/code" method="POST">
            @csrf
            <div class="form-group">
                <label for="code">Four digits code</label>
                <input type="text" id="code" name="code" required autofocus>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
