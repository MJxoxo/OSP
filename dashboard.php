<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get user information
$fullname = $_SESSION['fullname'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolsa Technologies - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #CAD1C5;
        }

        .container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header/Navbar styles */
        header {
            background-color: #CAD1C5;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin-left: 25px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-size: 15px;
        }

        .profile-icon {
            font-size: 18px;
        }

        /* Main content */
        .main-content {
            flex: 1;
            padding: 40px 5%;
        }

        .dashboard-header {
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
        }

        .user-info {
            margin-bottom: 20px;
        }

        .user-info p {
            margin-bottom: 10px;
            color: #555;
        }

        .user-info span {
            font-weight: bold;
            color: #333;
        }

        .btn {
            background-color: #121B57;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #0a1040;
        }

        /* Footer styles */
        footer {
            background-color: #CAD1C5;
            padding: 20px 5%;
            border-top: 1px solid #ddd;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
        }

        .footer-links {
            display: flex;
        }

        .footer-column {
            margin-right: 50px;
        }

        .footer-column h3 {
            font-size: 0.9rem;
            margin-bottom: 15px;
            color: #333;
        }

        .footer-column a {
            display: block;
            text-decoration: none;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.8rem;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .social-icons a {
            color: #333;
            font-size: 1.2rem;
        }

        .legal {
            display: flex;
            gap: 20px;
        }

        .legal a {
            text-decoration: none;
            color: #333;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header/Navbar -->
        <header>
            <div class="logo">
                <img src="images/rolsa-logo.png" alt="Rolsa Technologies">
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Energy</a></li>
                    <li><a href="#">Carbon Calc</a></li>
                    <li><a href="#">Schedule</a></li>
                    <li><a href="#"><i class="profile-icon">👤</i></a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <div class="main-content">
            <div class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($fullname); ?>!</h1>
            </div>

            <div class="dashboard-card">
                <h2>Your Account Information</h2>
                <div class="user-info">
                    <p>Name: <span><?php echo htmlspecialchars($fullname); ?></span></p>
                    <p>Email: <span><?php echo htmlspecialchars($email); ?></span></p>
                </div>

                <a href="logout.php" class="btn">Logout</a>
            </div>

            <div class="dashboard-card">
                <h2>Energy Consumption Summary</h2>
                <p>Your energy dashboard will appear here.</p>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <div class="footer-container">
                <div class="footer-left">
                    <div class="logo">
                        <img src="images/rolsa-logo.png" alt="Rolsa Technologies">
                    </div>
                    <div class="social-icons">
                        <a href="#"><span>📷</span></a>
                        <a href="#"><span>📘</span></a>
                        <a href="#"><span>📧</span></a>
                        <a href="#"><span>📱</span></a>
                    </div>
                    <div class="legal">
                        <a href="#">PRIVACY POLICY</a>
                        <a href="#">TERMS & CONDITIONS</a>
                    </div>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <a href="#">About us</a>
                        <a href="#">How to reduce carbon footprint</a>
                        <a href="#">Optimising energy use</a>
                        <a href="#">Electric Vehicle charging stations</a>
                    </div>
                    <div class="footer-column">
                        <a href="#">CTA requesting analysis</a>
                        <a href="#">Green energy products</a>
                        <a href="#">Contact us</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>