<?php
session_start();
$login_error = '';
$user_not_found = false; // Initialize to avoid undefined warning

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tech_store";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Login logic
    if (isset($_POST['login'])) {
        $sql = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $input_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($input_password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                header("Location: index.php");
                exit();
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "Invalid username.";
            $user_not_found = true; // Set flag to show the register link
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tech Store</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Body Background Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: url('images/earbuds.jpg') repeat center center fixed;
            background-size: current;
            background-position: center center;
            height: 50vh;
            animation: changeBackground 5s infinite;
        }

        @keyframes changeBackground {
            0% { background-image: url('images/earbuds.jpg'); }
            33% { background-image: url('images/laptop.webp'); }
            66% { background-image: url('images/iron.jpg'); }
            100% { background-image: url('images/router.jpg'); }
        }

        header {
            width: 100%;
            padding: 20px;
            background-color: green;
            color: #fff;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .form-container {
            display: none;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: slideDown 0.5s;
            z-index: 25;
        }

        .form-container h2 {
            margin: 0 0 20px;
        }

        .form-container input[type="text"], .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 18px;
            cursor: pointer;
            color: #333;
        }

        .error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .register-link {
            margin-top: 10px;
            font-size: 14px;
        }

        .register-link a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function toggleForm(formType) {
            const overlay = document.getElementById('overlay');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            if (overlay.style.display === 'none' || overlay.style.display === '') {
                overlay.style.display = 'block';
                if (formType === 'login') {
                    loginForm.style.display = 'block';
                    registerForm.style.display = 'none';
                } else {
                    registerForm.style.display = 'block';
                    loginForm.style.display = 'none';
                }
            } else {
                overlay.style.display = 'none';
                loginForm.style.display = 'none';
                registerForm.style.display = 'none';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const images = ['images/earbuds.jpg', 'images/laptop.webp', 'images/iron.jpg', 'images/router.jpg'];
            let currentImageIndex = 0;

            function changeBackground() {
                document.body.style.backgroundImage = `url(${images[currentImageIndex]})`;
                currentImageIndex = (currentImageIndex + 1) % images.length;
            }

            changeBackground(); 
            setInterval(changeBackground, 5000);
        });
    </script>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="images/logo1.jpg" alt="Tech Store Logo" class="logo">
            <h1>Tech Store</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="#" onclick="toggleForm('login')">Login</a>
                <a href="#" onclick="toggleForm('register')">Register</a>
            </nav>
        </div>
    </header>

    <div id="overlay" onclick="toggleForm()"></div>

    <!-- Login Form Modal -->
    <div id="login-form" class="form-container" onclick="event.stopPropagation();">
        <span class="close-btn" onclick="toggleForm('login')">&times;</span>
        <h2>Welcome Back!</h2>
        <p>Please login to continue</p>
        <form action="login.php" method="post">
            <div class="input-container">
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login">Login</button>
            <?php if ($login_error): ?>
                <p class="error"><?php echo $login_error; ?></p>
            <?php endif; ?>

            <?php if ($user_not_found): ?>
                <div class="register-link">
                    <!--<p>Don't have an account? <a href="#" onclick="toggleForm('register')">Register here</a></p>-->
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Register Form Modal -->
    <div id="register-form" class="form-container" onclick="event.stopPropagation();">
        <span class="close-btn" onclick="toggleForm('register')">&times;</span>
        <h2>Register</h2>
        <p>Create an account to get started</p>
        <form action="login.php" method="post">
            <div class="input-container">
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="register">Register</button>
            <?php if ($login_error): ?>
                <p class="error"><?php echo $login_error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body
