<?php
// Start the session at the very beginning of the file
session_start();

// Initialize variables
$fullname = $email = $username = $password = $repeat_password = "";
$signup_error = $login_error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "rt");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle signup
    if (isset($_POST['signup'])) {
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['signup_email']);
        $username = trim($_POST['username']);
        $password = trim($_POST['signup_password']);
        $repeat_password = trim($_POST['repeat_password']);

        // Validate input
        if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($repeat_password)) {
            $signup_error = "All fields are required";
        } elseif ($password !== $repeat_password) {
            $signup_error = "Passwords do not match";
        } else {
            // Check if email already exists
            $check_email = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($check_email);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $signup_error = "Email already exists";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $insert_query = "INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ssss", $fullname, $email, $username, $hashed_password);

                if ($stmt->execute()) {
                    // Redirect to login
                    header("Location: index.php?signup_success=1");
                    exit();
                } else {
                    $signup_error = "Error: " . $stmt->error;
                }
            }
        }
    }

    // Handle login
    if (isset($_POST['login'])) {
        $email = trim($_POST['login_email']);
        $password = trim($_POST['login_password']);

        // Validate input
        if (empty($email) || empty($password)) {
            $login_error = "Email and password are required";
        } else {
            // Check user credentials
            $login_query = "SELECT id, fullname, email, password FROM users WHERE email = ?";
            $stmt = $conn->prepare($login_query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['email'] = $user['email'];

                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $login_error = "Invalid email or password";
                }
            } else {
                $login_error = "Invalid email or password";
            }
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolsa Technologies - Login/Signup</title>
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
            background-image: url('images/background.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .auth-container {
            display: flex;
            justify-content: space-around;
            width: 100%;
            max-width: 1000px;
        }

        .auth-form {
            background-color: #121B57;
            color: white;
            border-radius: 8px;
            padding: 25px;
            width: 45%;
            max-width: 400px;
        }

        .auth-form h2 {
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .btn {
            background-color: #CAD1C5;
            color: #333;
            border: none;
            border-radius: 25px;
            padding: 10px 15px;
            cursor: pointer;
            width: 100%;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #bbc2c0;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
            font-size: 0.8rem;
        }

        .forgot-password a {
            color: white;
            text-decoration: none;
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

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                align-items: center;
            }

            .auth-form {
                width: 90%;
                margin-bottom: 20px;
            }

            .footer-container {
                flex-direction: column;
            }

            .footer-links {
                flex-direction: column;
                margin-bottom: 20px;
            }
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

                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <div class="main-content">
            <div class="auth-container">
                <!-- Signup Form -->
                <div class="auth-form">
                    <h2>Full name</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <?php if (!empty($signup_error)): ?>
                            <div style="color: #ffcccb; margin-bottom: 15px;"><?php echo $signup_error; ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <input type="text" name="fullname" placeholder="JamesCharles"
                                value="<?php echo $fullname; ?>" required>
                        </div>

                        <h2>Email</h2>
                        <div class="form-group">
                            <input type="email" name="signup_email" placeholder="jamescharles@gmail.com"
                                value="<?php echo $email; ?>" required>
                        </div>

                        <h2>Username</h2>
                        <div class="form-group">
                            <input type="text" name="username" placeholder="james123" value="<?php echo $username; ?>"
                                required>
                        </div>

                        <h2>Password</h2>
                        <div class="form-group">
                            <input type="password" name="signup_password" placeholder="••••••••••" required>
                        </div>

                        <h2>Repeat password</h2>
                        <div class="form-group">
                            <input type="password" name="repeat_password" placeholder="••••••••••" required>
                        </div>

                        <button type="submit" name="signup" class="btn">Sign up now</button>
                    </form>
                </div>

                <!-- Login Form -->
                <div class="auth-form">
                    <h2>Email</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <?php if (!empty($login_error)): ?>
                            <div style="color: #ffcccb; margin-bottom: 15px;"><?php echo $login_error; ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <input type="email" name="login_email" placeholder="jamescharles@gmail.com" required>
                        </div>

                        <h2>Password</h2>
                        <div class="form-group">
                            <input type="password" name="login_password" placeholder="••••••••••" required>
                        </div>

                        <button type="submit" name="login" class="btn">Sign In</button>

                        <div class="forgot-password">
                            <a href="#">Forgot password?</a>
                        </div>
                    </form>
                </div>
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