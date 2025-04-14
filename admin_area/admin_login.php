<?php
include('../includes/connect.php');

if(isset($_POST['admin_login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if admin exists
    $select_query = "SELECT * FROM admin_table WHERE admin_name=?";
    $stmt = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row['admin_password'])) {
            session_start();
            $_SESSION['admin_username'] = $username;
            echo "<script>alert('Login successful')</script>";
            echo "<script>window.open('index.php','_self')</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - Admin Login</title>
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
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13,110,253,0.3);
        }
        a {
            color: #0d6efd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #0a58ca;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center" style="color: #198754; font-size: 36px; font-weight: bold; margin-bottom: 30px;">GyanSphere Admin Login</h1>
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
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                    </div>

                                    <div class="form-group text-center">
                                        <input type="submit" name="admin_login" value="Login" class="btn btn-primary px-5 py-2">
                                    </div>

                                    <div class="text-center mt-4">
                                        <p class="mb-0">Don't have an account? <a href="admin_registration.php" class="fw-bold">Register here</a></p>
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
