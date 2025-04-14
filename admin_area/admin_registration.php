<?php
include('../includes/connect.php');

if(isset($_POST['admin_registration'])) {
    $admin_name = mysqli_real_escape_string($conn, $_POST['username']);
    $admin_email = mysqli_real_escape_string($conn, $_POST['email']); 
    $admin_password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if($admin_password != $confirm_password) {
        echo "<script>alert('Passwords do not match')</script>";
        exit();
    }

    // Check if email already exists
    $select_query = "SELECT * FROM admin_table WHERE admin_email=?";
    $stmt = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt, "s", $admin_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if(mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists')</script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

    // Insert new admin
    $insert_query = "INSERT INTO admin_table (admin_name, admin_email, admin_password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sss", $admin_name, $admin_email, $hashed_password);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('Registration successful')</script>";
        echo "<script>window.open('admin_login.php','_self')</script>";
    } else {
        echo "<script>alert('Registration failed')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .admin-photo {
            height: 100%;
            background: url('../images/gyan.png') center/contain no-repeat;
            background-color: #f8f9fa;
            border-radius: 15px 0 0 15px;
            position: relative;
        }
        .admin-photo::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 15px 0 0 15px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #198754, #157347);
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }
        a {
            color: #198754;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #157347;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center" style="color: #198754; font-size: 36px; font-weight: bold; margin-bottom: 30px;">GyanSphere Admin Registration</h1>    
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="admin-photo" style="min-height: 600px">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body p-5">
                                <form action="" method="post">
                                    <div class="form-group mb-4">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm your password" required>
                                    </div>

                                    <div class="form-group text-center">
                                        <input type="submit" name="admin_registration" value="Register" class="btn btn-primary px-5 py-2">
                                    </div>

                                    <div class="text-center mt-4">
                                        <p class="mb-0">Already have an account? <a href="admin_login.php" class="fw-bold">Login here</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid fixed-bottom">
        <div class="bg-success p-3 text-center">
            <p class="text-light mb-0">&copy; <?php echo date('Y'); ?> GyanSphere. All rights reserved. Developed by Gyan Chandra-2025</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
