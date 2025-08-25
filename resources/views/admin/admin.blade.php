<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: black;
            font-family: Arial, sans-serif;
        }

        /* Top navbar */
        .navbar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: black;
            z-index: 10;
        }

        .navbar button {
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: 1px solid white;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .navbar button:hover {
            background-color: white;
            color: black;
        }

        /* Center container */
        .container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Animated welcome text */
        .welcome-text {
            position: absolute;
            color: white;
            font-size: 3rem;
            font-weight: 800;
            opacity: 0;
            transform: translateX(200px);
            animation: fadeInSlide 1s forwards;
        }

        @keyframes fadeInSlide {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <button onclick="location.href='/admin/car_approve'">Approve a car</button>
        <button onclick="location.href='/admin/mechanic_approve'">Approve a mechanic</button>
        <button onclick="location.href='/admin/driver_approve'">Approve a driver</button>
        <button onclick="location.href='/admin/Apppay'">See all transaction</button>
    </div>

    <div class="container">
        <iframe src="https://my.spline.design/robotfollowcursorforlandingpagemc-g9CaQA1DdoDxEixlkRrJYFNv/" frameborder="0"></iframe>
        <div class="welcome-text">Welcome to Admin dashboard</div>
    </div>

</body>
</html>
