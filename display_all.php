<!-- connect file -->
<?php
include('includes/connect.php');
include('functions/common_functions.php');
session_start();

// Call cart function at the beginning to handle add_to_cart requests
cart();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - All Products</title>
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
        
        .card {
            transition: all 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .card-title {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .btn-success {
            background-color: var(--primary-color);
            border: none;
            transition: all 0.3s;
        }
        
        .btn-success:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
        
        .sidebar-title {
            font-weight: 600;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            margin-bottom: 0;
            background-color: var(--primary-color);
            color: white;
        }
        
        .list-group-item {
            transition: all 0.3s;
        }
        
        .list-group-item a {
            color: #212529 !important;
            text-decoration: none;
            display: block;
            font-weight: 500;
        }
        
        .list-group-item:hover {
            background-color: rgba(25, 135, 84, 0.1);
            transform: translateX(5px);
        }
        
        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            margin-top: 30px;
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
                <a class="navbar-brand" href="index.php">
                    <img src="./images/logo.png" alt="GyanSphere Logo" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="display_all.php"><i class="fa-solid fa-store"></i> Products</a>
                        </li>
                        <?php
                        if(isset($_SESSION['username'])){
                            echo "<li class='nav-item'>
                                <a class='nav-link' href='./users_area/profile.php'><i class='fa-solid fa-user'></i> My Account</a>
                            </li>";
                        }else{
                            echo "<li class='nav-item'>
                                <a class='nav-link' href='./users_area/user_registration.php'><i class='fa-solid fa-user-plus'></i> Register</a>
                            </li>";
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa-solid fa-info-circle"></i> Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart (<?php cart_item_numbers(); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa-solid fa-money-bill"></i> Total: $<?php total_cart_price(); ?></a>
                        </li>
                    </ul>
                    <form class="d-flex" action="search_product.php" method="get">
                        <input class="form-control me-2" type="search" placeholder="Search products" name="search_data">
                        <input type="submit" value="Search" class="btn btn-light" name="search_data_product">
                    </form>
                </div>
            </div>
        </nav>

        <!-- Second navbar for categories and brands -->
        <div class="bg-light p-3 mb-4">
            <div class="container text-center">
                <h2 class="fw-bold">All Products</h2>
                <div class="my-3">
                    <img src="./images/gyan.png" alt="Gyan Chandra" style="max-width: 250px; height: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                </div>
                <p>Browse our entire collection of quality products at GyanSphere</p>
            </div>
        </div>

        <div class="container-fluid mb-4">
            <div class="row">
                <!-- Side navigation -->
                <div class="col-md-3 col-lg-2 d-none d-md-block">
                    <!-- Categories -->
                    <div class="card mb-4">
                        <h5 class="sidebar-title p-2 text-center">Categories</h5>
                        <ul class="list-group list-group-flush">
                            <?php getcategories(); ?>
                        </ul>
                    </div>
                    
                    <!-- Brands -->
                    <div class="card mb-4">
                        <h5 class="sidebar-title p-2 text-center">Brands</h5>
                        <ul class="list-group list-group-flush">
                            <?php getbrands(); ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="col-md-9 col-lg-10">
                    <!-- Products -->
                    <div class="row px-1">
                        <?php
                        // Display products based on filters
                        if(isset($_GET['category'])) {
                            get_unique_categories();
                        } elseif(isset($_GET['brand'])) {
                            get_unique_brands();
                        } else {
                            get_all_products();
                        }
                        ?>
                    </div>
                </div>
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
                            <li><a href="index.php" class="text-light">Home</a></li>
                            <li><a href="display_all.php" class="text-light">Products</a></li>
                            <li><a href="#" class="text-light">Contact</a></li>
                            <li><a href="cart.php" class="text-light">Cart</a></li>
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