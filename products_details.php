<?php
session_start();
include('includes/connect.php');
include('functions/common_functions.php');

// Call the cart function to handle any add_to_cart actions
cart();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - Product Details</title>
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
        
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 50%;
        }
        
        .carousel-indicators button {
            background-color: rgba(0, 0, 0, 0.6);
            height: 12px;
            width: 12px;
            border-radius: 50%;
            margin: 0 5px;
        }
        
        .product-details {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .product-title {
            font-weight: 700;
            font-size: 2rem;
            color: var(--dark-color);
            margin-bottom: 15px;
        }
        
        .product-price {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--accent-color);
            margin: 15px 0;
        }
        
        .product-description {
            font-size: 1rem;
            line-height: 1.6;
            color: #6c757d;
            margin-bottom: 20px;
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

        <!-- Page Header -->
        <div class="bg-light p-3 mb-4">
            <div class="container text-center">
                <h2 class="fw-bold">Product Details</h2>
                <div class="my-3">
                    <img src="./images/gyan.png" alt="Gyan Chandra" style="max-width: 250px; height: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                </div>
                <p>Get detailed information about our products from GyanSphere</p>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <div class="col-md-12 product-details mb-4">
                    <?php
                    if(isset($_GET['product_id'])) {
                        $product_id = (int)$_GET['product_id'];
                        $select_query = "SELECT * FROM products WHERE product_id=? AND status='true'";
                        $stmt = mysqli_prepare($conn, $select_query);
                        mysqli_stmt_bind_param($stmt, "i", $product_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if(!$result) {
                            die("Query failed: " . mysqli_error($conn));
                        }
                        
                        if(mysqli_num_rows($result) == 0) {
                            echo "<div class='alert alert-danger text-center'>Product not found or is no longer available.</div>";
                        } else {
                            $row = mysqli_fetch_assoc($result);
                            $product_id = (int)$row['product_id'];
                            $product_title = htmlspecialchars($row['product_title']);
                            $product_description = htmlspecialchars($row['product_description']);
                            $product_image1 = htmlspecialchars($row['product_image1']);
                            $product_image2 = htmlspecialchars($row['product_image2']);
                            $product_image3 = htmlspecialchars($row['product_image3']);
                            $product_price = (float)$row['product_price'];
                            
                            // Check if images exist
                            $image1_path = "./admin_area/product_images/$product_image1";
                            $image2_path = "./admin_area/product_images/$product_image2";
                            $image3_path = "./admin_area/product_images/$product_image3";
                            
                            $image1_exists = file_exists($image1_path);
                            $image2_exists = file_exists($image2_path);
                            $image3_exists = file_exists($image3_path);
                            
                            $image1_src = $image1_exists ? $image1_path : "./images/no-image.jpg";
                            $image2_src = $image2_exists ? $image2_path : "./images/no-image.jpg";
                            $image3_src = $image3_exists ? $image3_path : "./images/no-image.jpg";
                            
                            echo "<div class='row'>
                                <div class='col-md-6 mb-4'>
                                    <div id='productCarousel' class='carousel slide' data-bs-ride='carousel'>
                                        <div class='carousel-indicators'>
                                            <button type='button' data-bs-target='#productCarousel' data-bs-slide-to='0' class='active' aria-current='true'></button>
                                            <button type='button' data-bs-target='#productCarousel' data-bs-slide-to='1'></button>
                                            <button type='button' data-bs-target='#productCarousel' data-bs-slide-to='2'></button>
                                        </div>
                                        <div class='carousel-inner rounded'>
                                            <div class='carousel-item active'>
                                                <img src='$image1_src' class='d-block w-100' alt='$product_title - Image 1' style='height: 400px; object-fit: contain;'>
                                            </div>
                                            <div class='carousel-item'>
                                                <img src='$image2_src' class='d-block w-100' alt='$product_title - Image 2' style='height: 400px; object-fit: contain;'>
                                            </div>
                                            <div class='carousel-item'>
                                                <img src='$image3_src' class='d-block w-100' alt='$product_title - Image 3' style='height: 400px; object-fit: contain;'>
                                            </div>
                                        </div>
                                        <button class='carousel-control-prev' type='button' data-bs-target='#productCarousel' data-bs-slide='prev'>
                                            <span class='carousel-control-prev-icon'></span>
                                            <span class='visually-hidden'>Previous</span>
                                        </button>
                                        <button class='carousel-control-next' type='button' data-bs-target='#productCarousel' data-bs-slide='next'>
                                            <span class='carousel-control-next-icon'></span>
                                            <span class='visually-hidden'>Next</span>
                                        </button>
                                    </div>
                                </div>
                                <div class='col-md-6'>
                                    <h1 class='product-title'>$product_title</h1>
                                    <p class='product-description'>$product_description</p>
                                    <p class='product-price'>$" . number_format($product_price, 2) . "</p>
                                    <div class='d-grid gap-2 d-md-flex justify-content-md-start mt-4'>
                                        <a href='index.php?add_to_cart=$product_id' class='btn btn-success btn-lg px-4'><i class='fa-solid fa-cart-plus'></i> Add to Cart</a>
                                        <a href='index.php' class='btn btn-secondary btn-lg px-4'><i class='fa-solid fa-arrow-left'></i> Continue Shopping</a>
                                    </div>
                                </div>
                            </div>";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<div class='alert alert-warning text-center'>No product selected. Please <a href='index.php'>go back</a> and select a product.</div>";
                    }
                    ?>
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