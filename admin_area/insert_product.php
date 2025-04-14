<?php
include('../includes/connect.php');
if(isset($_POST['insert_product'])){
    // Escape all input values to prevent SQL injection
    $product_title = mysqli_real_escape_string($conn, $_POST['product_title']);
    $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $product_keywords = mysqli_real_escape_string($conn, $_POST['product_keywords']);
    $product_category = mysqli_real_escape_string($conn, $_POST['product_category']);
    $product_brand = mysqli_real_escape_string($conn, $_POST['product_brand']);
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $product_status = 'true';

    //accessing image
    $product_image1 = $_FILES['product_image1']['name'];
    $product_image2 = $_FILES['product_image2']['name'];
    $product_image3 = $_FILES['product_image3']['name'];

    //accessing image tmp name
    $product_image1_tmp = $_FILES['product_image1']['tmp_name'];
    $product_image2_tmp = $_FILES['product_image2']['tmp_name'];
    $product_image3_tmp = $_FILES['product_image3']['tmp_name'];

    // Validate file types
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    foreach(['product_image1', 'product_image2', 'product_image3'] as $image) {
        if(!in_array($_FILES[$image]['type'], $allowed_types)) {
            echo "<script>alert('Invalid file type for $image. Only JPG, PNG and GIF allowed.')</script>";
            exit();
        }
        if($_FILES[$image]['size'] > $max_size) {
            echo "<script>alert('File size too large for $image. Maximum size is 5MB.')</script>";
            exit();
        }
    }

    //checking empty condition and validation
    if(empty($product_title) || empty($product_description) || empty($product_keywords) || 
       empty($product_category) || empty($product_brand) || empty($product_image1) || 
       empty($product_image2) || empty($product_image3) || empty($product_price)){
        echo "<script>alert('Please fill all the fields')</script>";
        exit();
    }else{
        // Create directory if it doesn't exist
        if(!is_dir("../product_images")) {
            mkdir("../product_images", 0777, true);
        }

        move_uploaded_file($product_image1_tmp, "../product_images/$product_image1");
        move_uploaded_file($product_image2_tmp, "../product_images/$product_image2");
        move_uploaded_file($product_image3_tmp, "../product_images/$product_image3");

        //insert query with prepared statement
        $insert_query = "INSERT INTO products (product_title, product_description, product_keywords, 
                        category_id, brand_id, product_image1, product_image2, product_image3, 
                        product_price, date, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
                        
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "ssssssssds", $product_title, $product_description, 
                             $product_keywords, $product_category, $product_brand, 
                             $product_image1, $product_image2, $product_image3, 
                             $product_price, $product_status);
        
        $result = mysqli_stmt_execute($stmt);
        
        if($result){
            echo "<script>alert('Product has been inserted successfully')</script>";
            echo "<script>window.open('index.php?insert_product', '_self')</script>";
        } else {
            echo "<script>alert('Error inserting product: " . mysqli_error($conn) . "')</script>";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - Insert Product</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
    <style>
        :root {
            --primary-color: #198754;
            --secondary-color: #157347;
            --accent-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
        }
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(25, 135, 84, 0.1);
        }
        .btn-submit {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }
        .page-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center page-title">GyanSphere - Insert New Product</h2>
        <div class="form-container">
            <form action="insert_product.php" method="post" enctype="multipart/form-data">
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_title" class="form-label">Product Title</label>
                    <input type="text" name="product_title" id="product_title" class="form-control" placeholder="Enter Product Title" autocomplete="off" required maxlength="100">
                </div>
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_description" class="form-label">Product Description</label>
                    <textarea name="product_description" id="product_description" class="form-control" placeholder="Enter Product Description" rows="3" required maxlength="500"></textarea>
                </div>
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_keywords" class="form-label">Product Keywords</label>
                    <input type="text" name="product_keywords" id="product_keywords" class="form-control" placeholder="Enter Product Keywords" autocomplete="off" required maxlength="100">
                </div>
                <!-- categories -->
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_category" class="form-label">Category</label>
                    <select name="product_category" class="form-select" required>
                        <option value="">Select a Category</option>
                        <?php
                        $select_categories = "SELECT * FROM categories";
                        $result_categories = mysqli_query($conn, $select_categories);
                        if(!$result_categories) {
                            die("Query failed: " . mysqli_error($conn));
                        }
                        while($row_data = mysqli_fetch_assoc($result_categories)){
                            $category_title = htmlspecialchars(mysqli_real_escape_string($conn, $row_data['category_title']));
                            $category_id = (int)$row_data['category_id'];
                            echo "<option value='$category_id'>$category_title</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- brands -->
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_brand" class="form-label">Brand</label>
                    <select name="product_brand" class="form-select" required>
                        <option value="">Select a Brand</option>
                        <?php
                        $select_brands = "SELECT * FROM brands";
                        $result_brands = mysqli_query($conn, $select_brands);
                        if(!$result_brands) {
                            die("Query failed: " . mysqli_error($conn));
                        }
                        while($row_data = mysqli_fetch_assoc($result_brands)){
                            $brand_title = htmlspecialchars(mysqli_real_escape_string($conn, $row_data['brand_title']));
                            $brand_id = (int)$row_data['brand_id'];
                            echo "<option value='$brand_id'>$brand_title</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- images -->
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_image1" class="form-label">Product Image 1</label>
                    <input type="file" name="product_image1" id="product_image1" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                </div>
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_image2" class="form-label">Product Image 2</label>
                    <input type="file" name="product_image2" id="product_image2" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                </div>
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_image3" class="form-label">Product Image 3</label>
                    <input type="file" name="product_image3" id="product_image3" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                </div>
                <!-- price -->
                <div class="form-outline mb-4 w-75 m-auto">
                    <label for="product_price" class="form-label">Product Price</label>
                    <input type="text" name="product_price" id="product_price" class="form-control" placeholder="Enter Product Price" autocomplete="off" required pattern="[0-9]+(\.[0-9]{1,2})?" title="Enter a valid price (e.g. 10.99)">
                </div>
                <!-- submit -->
                <div class="form-outline mb-4 w-75 m-auto">
                    <input type="submit" name="insert_product" class="btn btn-submit px-4" value="Insert Product">
                </div>
            </form>
        </div>
        
        <div class="mt-5">
            <a href="index.php" class="btn btn-secondary mb-5">Back to Dashboard</a>
        </div>
    </div>
    
    <div class="container-fluid fixed-bottom">
        <div class="bg-success p-3 text-center">
            <p class="text-light mb-0">&copy; <?php echo date('Y'); ?> GyanSphere. All rights reserved. Developed by Gyan Chandra-2025</p>
        </div>
    </div>
</body>
<!-- Bootstrap JS link -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>