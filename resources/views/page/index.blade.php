<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #343a40;
            color: #ffffff;
            border-radius: 8px;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-card {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h4 {
            margin: 0;
            font-size: 2em;
            color: #343a40;
        }
        .stat-card p {
            margin: 0;
            color: #6c757d;
        }
        .donations-section {
            margin-bottom: 40px;
        }
        .section-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #343a40;
            font-weight: bold;
        }
        .donation-card {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .donation-card h5 {
            margin: 0;
            font-size: 1.2em;
            color: #343a40;
        }
        .donation-card p {
            margin: 5px 0;
            color: #6c757d;
        }
        .donation-card button {
            margin-top: 10px;
        }
        .pagination {
            justify-content: center;
        }
        .btn-custom {
            color: #ffffff;
            background-color: #343a40;
            border-color: #343a40;
        }
        .btn-custom:hover {
            color: #ffffff;
            background-color: #23272b;
            border-color: #1d2124;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome back, Rich Jackson</h1>
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAd5avdba8EiOZH8lmV3XshrXx7dKRZvhx-A&s" alt="Profile" class="rounded-circle" width="50" height="50">
        </div>
        <div class="stats">
            <div class="stat-card">
                <h4>860</h4>
                <p>devices donated</p>
            </div>
            <div class="stat-card">
                <h4>230</h4>
                <p>households assisted</p>
            </div>
            <div class="stat-card">
                <h4>1K</h4>
                <p>new digital users</p>
            </div>
            <div class="stat-card">
                <h4>120k</h4>
                <p>pounds of e-waste eliminated</p>
            </div>
        </div>

        <div class="donations-section">
            <h3 class="section-title">Donations in Progress</h3>
            <div class="donation-card">
                <h5>May 7, 2023</h5>
                <p>Job ID: 75767868996969</p>
                <p>Picked up at 6789 Rainbow Drive, Cupertino, CA 95015</p>
                <button class="btn btn-custom btn-sm">View Journey Map</button>
            </div>
            <div class="donation-card">
                <h5>May 6, 2023</h5>
                <p>Job ID: 12767686999090</p>
                <button class="btn btn-custom btn-sm">+</button>
            </div>
            <div class="donation-card">
                <h5>April 18, 2023</h5>
                <p>Job ID: 45767686999067</p>
                <button class="btn btn-custom btn-sm">+</button>
            </div>
            <div class="donation-card">
                <h5>April 1, 2023</h5>
                <p>Job ID: 65767686999094</p>
                <button class="btn btn-custom btn-sm">+</button>
            </div>
            <div class="donation-card">
                <h5>March 16, 2023</h5>
                <p>Job ID: 85767686999095</p>
                <button class="btn btn-custom btn-sm">+</button>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                </ul>
            </nav>
        </div>

        <div class="donations-section">
            <h3 class="section-title">Past Donations</h3>
            <div class="donation-card">
                <h5>May 7, 2023</h5>
                <p>Job ID: 75767868996969</p>
                <p>Picked up at 6789 Rainbow Drive, Cupertino, CA 95015</p>
                <p>Completed on May 6, 2023</p>
                <button class="btn btn-outline-success btn-sm">RECEIPT</button>
                <button class="btn btn-outline-success btn-sm">CERTIFICATE</button>
                <button class="btn btn-custom btn-sm">View Journey Map</button>
            </div>
            <div class="donation-card">
                <h5>May 6, 2023</h5>
                <p>Job ID: 12767686999090</p>
                <button class="btn btn-custom btn-sm">+</button>
            </div>
            <div class="donation-card">
                <h5>April 18, 2023</h5>
                <p>Job ID: 45767686999067</p>
                <button class="btn btn-custom btn-sm">+</button>
            </div>
            <div class="donation-card">
                <h5>April 1, 2023</h5>
                <p>Job ID: 65767686999094</p>
                <button class="btn btn-custom btn-sm">+</button>
            </div>
            <div class="donation-card">
                <h5>March 16, 2023</h5>
                <p>Job ID: 85767686999095</p>
                <button class="btn btn-custom btn-sm">+</button>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
