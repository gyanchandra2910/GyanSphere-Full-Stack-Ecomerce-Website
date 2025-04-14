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
    <title>GyanSphere - Your Shopping Destination</title>
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
        
        .hero-section {
            background-color: var(--primary-color);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
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
        
        /* For the carousel */
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 15px;
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
                            <a class="nav-link active" href="index.php"><i class="fa-solid fa-house"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="display_all.php"><i class="fa-solid fa-store"></i> Products</a>
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

        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container text-center">
                <h1 class="hero-title">Welcome to GyanSphere</h1>
                <div class="my-4">
                    <img src="./images/gyan.png" alt="Gyan Chandra" style="max-width: 300px; height: auto; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                </div>
                <p class="hero-subtitle">Discover amazing products at unbeatable prices</p>
                <a href="display_all.php" class="btn btn-light mt-3">Shop Now <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Second navbar for categories and brands -->
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
                        <!-- Fetch 9 products -->
                        <?php
                        // Modified getproducts function to limit to 9 products for homepage
                        global $conn;
                        
                        if(isset($_GET['category'])) {
                            $category_id = (int)$_GET['category'];
                            $select_query = "SELECT * FROM products WHERE category_id=? AND status='true' LIMIT 9";
                            $stmt = mysqli_prepare($conn, $select_query);
                            mysqli_stmt_bind_param($stmt, "i", $category_id);
                        } elseif(isset($_GET['brand'])) {
                            $brand_id = (int)$_GET['brand'];
                            $select_query = "SELECT * FROM products WHERE brand_id=? AND status='true' LIMIT 9";
                            $stmt = mysqli_prepare($conn, $select_query);
                            mysqli_stmt_bind_param($stmt, "i", $brand_id);
                        } else {
                            $select_query = "SELECT * FROM products WHERE status='true' ORDER BY RAND() LIMIT 9";
                            $stmt = mysqli_prepare($conn, $select_query);
                        }
                        
                        if (!$stmt) {
                            die("Query preparation failed: " . mysqli_error($conn));
                        }
                        
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if(!$result) {
                            die("Query execution failed: " . mysqli_error($conn));
                        }
                        
                        $product_count = mysqli_num_rows($result);
                        
                        if($product_count == 0) {
                            echo "<h3 class='text-center text-danger mt-5'>No products available in this category!</h3>";
                        }
                        
                        while($row = mysqli_fetch_assoc($result)){
                            $product_id = (int)$row['product_id'];
                            $product_title = htmlspecialchars($row['product_title']);
                            $product_description = htmlspecialchars($row['product_description']);
                            
                            // Limit description to 100 characters
                            if(strlen($product_description) > 100) {
                                $product_description = substr($product_description, 0, 100) . '...';
                            }
                            
                            $product_image1 = htmlspecialchars($row['product_image1']);
                            $product_price = (float)$row['product_price'];
                            
                            // Check if image exists
                            $image_path = "./admin_area/product_images/$product_image1";
                            $image_exists = file_exists($image_path);
                            $image_src = $image_exists ? $image_path : "./images/no-image.jpg";

                            echo "<div class='col-md-6 col-lg-4 mb-4'>
                                <div class='card h-100'>
                                    <img src='$image_src' class='card-img-top' alt='$product_title' style='height: 200px; object-fit: contain; padding: 10px;'>
                                    <div class='card-body d-flex flex-column'>
                                        <h5 class='card-title'>$product_title</h5>
                                        <p class='card-text flex-grow-1'>$product_description</p>
                                        <div class='mt-auto'>
                                            <p class='card-text fw-bold'>$" . number_format($product_price, 2) . "</p>
                                            <div class='d-flex justify-content-between'>
                                                <a href='index.php?add_to_cart=$product_id' class='btn btn-success btn-sm'><i class='fa-solid fa-cart-plus'></i> Add to cart</a>
                                                <a href='products_details.php?product_id=$product_id' class='btn btn-secondary btn-sm'><i class='fa-solid fa-eye'></i> View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }
                        mysqli_stmt_close($stmt);
                        ?>
                    </div>
                    
                    <!-- View all products button -->
                    <div class="text-center mt-4 mb-4">
                        <a href="display_all.php" class="btn btn-primary">View All Products <i class="fa-solid fa-arrow-right"></i></a>
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