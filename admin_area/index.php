<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_username'])) {
    echo "<script>window.open('admin_login.php','_self')</script>";
    exit();
}

include('../includes/connect.php');
include('../functions/common_functions.php');
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - Admin Dashboard</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-bottom: 80px; /* Add padding to prevent content from being hidden by fixed footer */
        }
        .navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logo {
            height: 50px;
            width: auto; /* Add width:auto to maintain aspect ratio */
        }
        .admin_image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover; /* Add object-fit to prevent image distortion */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .button button {
            margin: 5px;  /* Add horizontal margin */
            border: none;
            transition: transform 0.2s ease-in-out;
        }
        .button button:hover {
            transform: scale(1.05);
        }
        .button a {
            text-decoration: none;
            font-weight: bold;
            display: block; /* Make entire button clickable */
            padding: 5px 10px;
        }
        .bg-light h3 {
            font-weight: bold;
            color: #333;
        }
        .bg-success {
            background: linear-gradient(45deg, #28a745, #218838) !important; /* Add !important to override Bootstrap */
        }
        .fixed-bottom {
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }
        .container.my-3 {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-height: 300px; /* Add minimum height for content area */
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <!-- First child -->
        <nav class="navbar navbar-expand-lg navbar-light bg-success">
            <div class="container-fluid">
                <img src="../images/logo.png" alt="GyanSphere Logo" class="logo">
                <nav class="navbar navbar-expand-lg">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light">Welcome <?php echo $_SESSION['admin_username']; ?></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>

        <!-- second child -->
        <div class="bg-light">
            <h3 class="text-center p-2">GyanSphere Admin Panel</h3>
        </div>

        <!-- third child -->
        <div class="row">
            <div class="col-md-12 bg-secondary p-3 d-flex align-items-center">
                <div class="px-3">
                    <a href="#"><img src="../images/gyan.png" alt="Admin" class="admin_image"></a>
                </div>
                <div class="button text-center">
                    <button class="btn btn-success"><a href="insert_product.php" class="text-light">Insert Products</a></button>
                    <button class="btn btn-success"><a href="index.php?view_products" class="text-light">View Products</a></button>
                    <button class="btn btn-success"><a href="index.php?insert_categories" class="text-light">Insert Categories</a></button>
                    <button class="btn btn-success"><a href="index.php?view_categories" class="text-light">View Categories</a></button>
                    <button class="btn btn-success"><a href="index.php?insert_brands" class="text-light">Insert Brands</a></button>
                    <button class="btn btn-success"><a href="index.php?view_brands" class="text-light">View Brands</a></button>
                    <button class="btn btn-success"><a href="index.php?list_orders" class="text-light">All Orders</a></button>
                    <button class="btn btn-success"><a href="index.php?list_payments" class="text-light">All Payments</a></button>
                    <button class="btn btn-success"><a href="index.php?list_users" class="text-light">List Users</a></button>
                    <button class="btn btn-danger"><a href="admin_login.php" class="text-light">Logout</a></button>
                </div>
            </div>
        </div>

        <!-- fourth child -->
        <div class="container my-3">
            <?php
            if(isset($_GET['insert_categories'])){
                include('insert_categories.php');
            }
            if(isset($_GET['insert_brands'])){
                include('insert_brands.php');
            }    
            if(isset($_GET['view_products'])){
                include('view_products.php');
            }
            if(isset($_GET['view_categories'])){
                include('view_categories.php');
            }
            if(isset($_GET['view_brands'])){
                include('view_brands.php');
            }
            if(isset($_GET['list_orders'])){
                include('list_orders.php');
            }
            if(isset($_GET['list_payments'])){
                include('list_payments.php');
            }
            if(isset($_GET['list_users'])){
                include('list_users.php');
            }
            if(isset($_GET['edit_products'])){
                include('edit_products.php');
            }
            if(isset($_GET['delete_product'])){
                include('delete_product.php');
            }
            if(isset($_GET['edit_category'])){
                include('edit_category.php');
            }
            if(isset($_GET['delete_category'])){
                include('delete_category.php');
            }
            if(isset($_GET['edit_brand'])){
                include('edit_brand.php');
            }
            if(isset($_GET['delete_brand'])){
                include('delete_brand.php');
            }
            if(isset($_GET['list_orders'])){
                include('list_orders.php');
            }
            if(isset($_GET['delete_order'])){
                include('delete_order.php');
            }
            if(isset($_GET['list_payments'])){
                include('list_payments.php');
            }
            if(isset($_GET['delete_payment'])){
                include('delete_payment.php');
            }   
            if(isset($_GET['list_users'])){
                include('list_users.php');
            }
            if(isset($_GET['delete_user'])){
                include('delete_user.php');
            }
            if(isset($_GET['edit_user'])){
                include('edit_user.php');
            }
            ?>
        </div>

        <!-- last child -->
        <div class="bg-success p-3 text-center fixed-bottom">
            <p class="text-light mb-0">&copy; <?php echo date('Y'); ?> GyanSphere. All rights reserved. Developed by Gyan Chandra-2025</p>
        </div>
    </div>

    <!-- Bootstrap JS link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
