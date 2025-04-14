<!-- connect file -->
<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
session_start();

// Handle login form submission
if(isset($_POST['user_login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['user_username']);
    $password = mysqli_real_escape_string($conn, $_POST['user_password']);

    // Query to check if username exists
    $select_query = "SELECT * FROM user_table WHERE username=?";
    $stmt = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Verify password
        if(password_verify($password, $row['user_password'])) {
            $_SESSION['username'] = htmlspecialchars($username);
            $_SESSION['user_id'] = (int)$row['user_id'];
            
            // Get user's IP address
            $user_ip = getIPAddress();
            
            // Check if user has items in cart using prepared statement
            $select_cart = "SELECT * FROM cart_details WHERE ip_address=?";
            $stmt = mysqli_prepare($conn, $select_cart);
            mysqli_stmt_bind_param($stmt, "s", $user_ip);
            mysqli_stmt_execute($stmt);
            $result_cart = mysqli_stmt_get_result($stmt);
            $rows_count = mysqli_num_rows($result_cart);
            
            if($rows_count > 0) {
                echo "<script>alert('Login successful')</script>";
                echo "<script>window.open('payment.php','_self')</script>";
            } else {
                echo "<script>alert('Login successful')</script>";
                echo "<script>window.open('profile.php','_self')</script>";
            }
        } else {
            echo "<script>alert('Invalid credentials')</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - User Login</title>
    <!-- Bootstrap CSS Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #198754;
            --secondary-color: #157347;
            --accent-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .logo {
            width: 12%;
            height: 12%;
        }
        
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .page-header {
            background-color: var(--primary-color);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .login-form {
            max-width: 450px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .form-control {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }
        
        .btn-success {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-success:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
        
        .text-light {
            color: var(--light-color) !important;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="../index.php">
                    <img src="../images/logo.png" alt="GyanSphere Logo" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php"><i class="fa-solid fa-house"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../display_all.php"><i class="fa-solid fa-store"></i> Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="user_registration.php"><i class="fa-solid fa-user-plus"></i> Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa-solid fa-info-circle"></i> Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart (<?php cart_item_numbers(); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa-solid fa-money-bill"></i> Total: $<?php total_cart_price(); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        <div class="page-header">
            <div class="container text-center">
                <h1 class="text-center mb-4">User Login at GyanSphere</h1>
                <div class="text-center mb-4">
                    <img src="../images/gyan.png" alt="Gyan Chandra" style="max-width: 200px; height: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                </div>
                <p>Access your account to manage orders and view purchase history at GyanSphere</p>
            </div>
        </div>

        <div class="container">
            <div class="login-form">
                <h2 class="text-center mb-4">Sign In</h2>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="user_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="user_username" name="user_username" placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Enter your password" required>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success" name="user_login">
                            <i class="fa-solid fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <p>Don't have an account? <a href="user_registration.php" class="text-decoration-none text-success fw-bold">Register here</a></p>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>About Us</h5>
                        <p>GyanSphere offers premium products at competitive prices. We're dedicated to providing an exceptional shopping experience.</p>
                    </div>
                    <div class="col-md-4">
                        <h5>Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="../index.php" class="text-light">Home</a></li>
                            <li><a href="../display_all.php" class="text-light">Products</a></li>
                            <li><a href="#" class="text-light">Contact</a></li>
                            <li><a href="../cart.php" class="text-light">Cart</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Connect With Us</h5>
                        <div class="d-flex gap-3 fs-4">
                            <a href="#" class="text-light"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-light"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.instagram.com/gyan.2910/" target="_blank" class="text-light"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.linkedin.com/in/gyanchandra29102003" target="_blank" class="text-light"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <hr class="bg-light">
                <div class="text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> GyanSphere. All rights reserved. Developed by Gyan Chandra-2025</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>