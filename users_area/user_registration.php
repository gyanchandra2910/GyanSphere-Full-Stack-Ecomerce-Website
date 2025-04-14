<!-- connect file -->
<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
session_start();

// Handle form submission
if(isset($_POST['user_register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['user_username']);
    $email = mysqli_real_escape_string($conn, $_POST['user_email']); 
    $password = mysqli_real_escape_string($conn, $_POST['user_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['user_confirm_password']);
    $address = mysqli_real_escape_string($conn, $_POST['user_address']);
    $contact = mysqli_real_escape_string($conn, $_POST['user_contact']);
    $user_ip = getIPAddress();

    // Input validation
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($address) || empty($contact)) {
        echo "<script>alert('All fields are required')</script>";
        exit();
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format')</script>";
        exit();
    }

    // Password validation
    if(strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long')</script>";
        exit();
    }

    if($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match')</script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle image upload
    if(!isset($_FILES['user_image']) || $_FILES['user_image']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Please select a valid image file')</script>";
        exit();
    }

    $user_image = $_FILES['user_image']['name'];
    $temp_image = $_FILES['user_image']['tmp_name'];
    
    // Validate image file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = mime_content_type($temp_image);
    if(!in_array($file_type, $allowed_types)) {
        echo "<script>alert('Only JPG, PNG and GIF files are allowed')</script>";
        exit();
    }

    // Create unique filename
    $image_extension = pathinfo($user_image, PATHINFO_EXTENSION);
    $unique_image_name = uniqid() . '.' . $image_extension;
    
    // Create directory if it doesn't exist
    $upload_dir = "./user_images";
    if(!is_dir($upload_dir)) {
        if(!mkdir($upload_dir, 0777, true)) {
            echo "<script>alert('Failed to create upload directory')</script>";
            exit();
        }
    }
    
    if(!move_uploaded_file($temp_image, "$upload_dir/$unique_image_name")) {
        echo "<script>alert('Failed to upload image')</script>";
        exit();
    }

    // Check if username or email already exists
    $select_query = "SELECT * FROM user_table WHERE username=? OR user_email=?";
    $stmt = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username or email already exists')</script>";
        exit();
    }

    // Insert user data using prepared statement
    $insert_query = "INSERT INTO user_table (username, user_email, user_password, user_image, user_ip, user_address, user_mobile) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sssssss", $username, $email, $hashed_password, $unique_image_name, $user_ip, $address, $contact);
    
    if(mysqli_stmt_execute($stmt)) {
        // Get the newly inserted user_id
        $user_id = mysqli_insert_id($conn);
        
        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user_id;

        // Check for cart items
        $select_cart_items = "SELECT * FROM cart_details WHERE ip_address=?";
        $stmt = mysqli_prepare($conn, $select_cart_items);
        mysqli_stmt_bind_param($stmt, "s", $user_ip);
        mysqli_stmt_execute($stmt);
        $result_cart = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result_cart) > 0) {
            echo "<script>alert('You have items in your cart')</script>";
            echo "<script>window.open('checkout.php','_self')</script>";
        } else {
            echo "<script>window.open('../index.php','_self')</script>";
        }   
    } else {
        echo "<script>alert('Registration failed: " . mysqli_error($conn) . "')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - User Registration</title>
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
        
        .registration-form {
            max-width: 600px;
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
                            <a class="nav-link active" href="user_registration.php"><i class="fa-solid fa-user-plus"></i> Register</a>
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
                <h1>Create Account</h1>
                <p>Join our community to access exclusive offers and manage your orders at GyanSphere</p>
            </div>
        </div>

        <div class="container">
            <div class="registration-form">
                <h1 class="text-center mb-4">User Registration at GyanSphere</h1>
                <div class="text-center mb-4">
                    <img src="../images/gyan.png" alt="Gyan Chandra" style="max-width: 200px; height: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="user_username" placeholder="Enter your username" pattern="[A-Za-z0-9]+" minlength="3" maxlength="20" required>
                        <div class="invalid-feedback">Please enter a valid username (3-20 characters, letters and numbers only)</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="user_email" placeholder="Enter your email" required>
                        <div class="invalid-feedback">Please enter a valid email address</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="user_password" placeholder="Enter your password" minlength="8" required>
                        <div class="invalid-feedback">Password must be at least 8 characters long</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="user_confirm_password" placeholder="Confirm your password" required>
                        <div class="invalid-feedback">Passwords must match</div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="user_address" placeholder="Enter your address" required>
                        <div class="invalid-feedback">Please enter your address</div>
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="tel" class="form-control" id="contact" name="user_contact" placeholder="Enter your mobile number" pattern="[0-9]+" required>
                        <div class="invalid-feedback">Please enter a valid phone number</div>
                    </div>
                    <div class="mb-3">
                        <label for="user_image" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="user_image" name="user_image" accept="image/*" required>
                        <div class="invalid-feedback">Please select a profile picture</div>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success" name="user_register">
                            <i class="fa-solid fa-user-plus me-2"></i>Create Account
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <p>Already have an account? <a href="user_login.php" class="text-decoration-none text-success fw-bold">Login here</a></p>
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
    
    <!-- Form validation script -->
    <script>
        (function () {
            'use strict'
            
            // Fetch all forms we want to apply validation styles to
            var forms = document.querySelectorAll('.needs-validation')
            
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>
