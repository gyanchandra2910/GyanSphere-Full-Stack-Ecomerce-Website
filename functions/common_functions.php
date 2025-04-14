<?php
// Prevent multiple inclusions
if (!defined('COMMON_FUNCTIONS_INCLUDED')) {
    define('COMMON_FUNCTIONS_INCLUDED', true);

    // Include database connection
    include(__DIR__ . '/../includes/connect.php');

    // Getting products from database
    function getproducts() {
        global $conn;

        if(isset($_GET['category'])) {
            $category_id = (int)$_GET['category'];
            $select_query = "SELECT * FROM products WHERE category_id=? AND status='true'";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, "i", $category_id);
        } elseif(isset($_GET['brand'])) {
            $brand_id = (int)$_GET['brand'];
            $select_query = "SELECT * FROM products WHERE brand_id=? AND status='true'";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, "i", $brand_id);
        } else {
            $select_query = "SELECT * FROM products WHERE status='true' ORDER BY RAND()";
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

        while($row = mysqli_fetch_assoc($result)){
            $product_id = (int)$row['product_id'];
            $product_title = htmlspecialchars($row['product_title']);
            $product_description = htmlspecialchars($row['product_description']);
            $product_image1 = htmlspecialchars($row['product_image1']);
            $product_price = (float)$row['product_price'];

            echo "<div class='col-md-4 mb-2'>
                <div class='card'>
                    <img src='./admin_area/product_images/$product_image1' class='card-img-top' alt='$product_title' style='height: 200px; object-fit: contain;'>
                    <div class='card-body'>
                        <h5 class='card-title'>$product_title</h5>
                        <p class='card-text'>$product_description</p>
                        <p class='card-text'>$$product_price</p>
                        <a href='index.php?add_to_cart=$product_id' class='btn btn-success'>Add to cart</a>
                        <a href='products_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                    </div>
                </div>
            </div>";
        }
        mysqli_stmt_close($stmt);
    }

    // Getting all products
    function get_all_products() {
        global $conn;

        // Debug: Check if connection is valid
        if (!$conn) {
            echo "<div class='alert alert-danger'>Database connection is not valid</div>";
            return;
        }

        // Build the query based on filters
        if(isset($_GET['category'])) {
            $category_id = (int)$_GET['category'];
            $select_query = "SELECT * FROM products WHERE category_id=? AND status='true' ORDER BY RAND()";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, "i", $category_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        } elseif(isset($_GET['brand'])) {
            $brand_id = (int)$_GET['brand'];
            $select_query = "SELECT * FROM products WHERE brand_id=? AND status='true' ORDER BY RAND()";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, "i", $brand_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        } else {
            $select_query = "SELECT * FROM products WHERE status='true' ORDER BY RAND()";
            $result = mysqli_query($conn, $select_query);
        }

        if (!$result) {
            echo "<div class='alert alert-danger'>Query failed: " . mysqli_error($conn) . "</div>";
            return;
        }

        $num_of_rows = mysqli_num_rows($result);
        if ($num_of_rows == 0) {
            echo "<h2 class='text-center text-danger'>No products found</h2>";
            return;
        }

        while($row = mysqli_fetch_assoc($result)){
            $product_id = (int)$row['product_id'];
            $product_title = htmlspecialchars($row['product_title']);
            $product_description = htmlspecialchars($row['product_description']);
            $product_image1 = htmlspecialchars($row['product_image1']);
            $product_price = (float)$row['product_price'];

            // Check if image exists
            $image_path = "./admin_area/product_images/$product_image1";
            $image_exists = file_exists($image_path);
            $image_src = $image_exists ? $image_path : "./images/no-image.jpg";

            echo "<div class='col-md-4 mb-2'>
                <div class='card'>
                    <img src='$image_src' class='card-img-top' alt='$product_title' style='height: 200px; object-fit: contain;'>
                    <div class='card-body'>
                        <h5 class='card-title'>$product_title</h5>
                        <p class='card-text'>$product_description</p>
                        <p class='card-text'>$$product_price</p>
                        <a href='index.php?add_to_cart=$product_id' class='btn btn-success'>Add to cart</a>
                        <a href='products_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                    </div>
                </div>
            </div>";
        }
    }

    //getting unique categories
    function get_unique_categories() {
        global $conn;
        if(isset($_GET['category'])) {
            $category_id = (int)$_GET['category'];
            $select_query = "SELECT * FROM products WHERE category_id=? AND status='true'";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, "i", $category_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if(!$result) {
                die("Query failed: " . mysqli_error($conn));
            }
            
            $num_of_rows = mysqli_num_rows($result);
            if($num_of_rows == 0) {
                echo "<h2 class='text-center text-danger'>No products found in this category</h2>";
                return;
            }
            
            while($row = mysqli_fetch_assoc($result)){
                $product_id = (int)$row['product_id'];
                $product_title = htmlspecialchars($row['product_title']);
                $product_description = htmlspecialchars($row['product_description']);
                $product_image1 = htmlspecialchars($row['product_image1']);
                $product_price = (float)$row['product_price'];
                
                // Check if image exists
                $image_path = "./admin_area/product_images/$product_image1";
                $image_exists = file_exists($image_path);
                $image_src = $image_exists ? $image_path : "./images/no-image.jpg";
                
                echo "<div class='col-md-4 mb-2'>
                    <div class='card'>
                        <img src='$image_src' class='card-img-top' alt='$product_title' style='height: 200px; object-fit: contain;'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_title</h5>
                            <p class='card-text'>$product_description</p>
                            <p class='card-text'>$$product_price</p>
                            <a href='index.php?add_to_cart=$product_id' class='btn btn-success'>Add to cart</a>
                            <a href='products_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                        </div>
                    </div>
                </div>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //getting unique brands
    function get_unique_brands() {
        global $conn;
        if(isset($_GET['brand'])) {
            $brand_id = (int)$_GET['brand'];
            $select_query = "SELECT * FROM products WHERE brand_id=? AND status='true'";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, "i", $brand_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if(!$result) {
                die("Query failed: " . mysqli_error($conn));
            }
            
            $num_of_rows = mysqli_num_rows($result);
            if($num_of_rows == 0) {
                echo "<h2 class='text-center text-danger'>No products found for this brand</h2>";
                return;
            }
            
            while($row = mysqli_fetch_assoc($result)){
                $product_id = (int)$row['product_id'];
                $product_title = htmlspecialchars($row['product_title']);
                $product_description = htmlspecialchars($row['product_description']);
                $product_image1 = htmlspecialchars($row['product_image1']);
                $product_price = (float)$row['product_price'];
                
                // Check if image exists
                $image_path = "./admin_area/product_images/$product_image1";
                $image_exists = file_exists($image_path);
                $image_src = $image_exists ? $image_path : "./images/no-image.jpg";
                
                echo "<div class='col-md-4 mb-2'>
                    <div class='card'>
                        <img src='$image_src' class='card-img-top' alt='$product_title' style='height: 200px; object-fit: contain;'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_title</h5>
                            <p class='card-text'>$product_description</p>
                            <p class='card-text'>$$product_price</p>
                            <a href='index.php?add_to_cart=$product_id' class='btn btn-success'>Add to cart</a>
                            <a href='products_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                        </div>
                    </div>
                </div>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    function getbrands() {
        global $conn;
        $select_brands = "SELECT * FROM brands";
        $result_brands = mysqli_query($conn, $select_brands);
        
        if(!$result_brands) {
            die("Query failed: " . mysqli_error($conn));
        }
        
        while($row_data = mysqli_fetch_assoc($result_brands)){
            $brand_title = htmlspecialchars($row_data['brand_title']);
            $brand_id = (int)$row_data['brand_id'];
            echo "<li class='list-group-item'>
                <a href='index.php?brand=$brand_id'>$brand_title</a>
            </li>";
        }
    }

    function getcategories() {
        global $conn;
        $select_categories = "SELECT * FROM categories";
        $result_categories = mysqli_query($conn, $select_categories);
        
        if(!$result_categories) {
            die("Query failed: " . mysqli_error($conn));
        }
        
        while($row_data = mysqli_fetch_assoc($result_categories)){
            $category_title = htmlspecialchars($row_data['category_title']);
            $category_id = (int)$row_data['category_id'];
            echo "<li class='list-group-item'>
                <a href='index.php?category=$category_id'>$category_title</a>
            </li>";
        }
    }

    function search_product() {
        global $conn;
        if(isset($_GET['search_data_product'])) {
            $search_data_value = htmlspecialchars($_GET['search_data']);
            $select_query = "SELECT * FROM products WHERE (product_keywords LIKE ? OR product_title LIKE ?) AND status='true'";
            $stmt = mysqli_prepare($conn, $select_query);
            $search_param = "%$search_data_value%";
            mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if(!$result) {
                die("Query failed: " . mysqli_error($conn));
            }
            
            $num_of_rows = mysqli_num_rows($result);
            if($num_of_rows == 0) {
                echo "<h2 class='text-center text-danger'>No results match. No products found on this category!</h2>";
                return;
            }
            
            while($row = mysqli_fetch_assoc($result)){
                $product_id = (int)$row['product_id'];
                $product_title = htmlspecialchars($row['product_title']);
                $product_description = htmlspecialchars($row['product_description']);
                $product_image1 = htmlspecialchars($row['product_image1']);
                $product_category = (int)$row['category_id'];
                $product_brand = (int)$row['brand_id'];
                $product_price = (float)$row['product_price'];
                
                echo "<div class='col-md-4 mb-2'>
                    <div class='card'>
                        <img src='./admin_area/product_images/$product_image1' class='card-img-top' alt='$product_title' style='height: 200px; object-fit: contain;'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_title</h5>
                            <p class='card-text'>$product_description</p>
                            <p class='card-text'>$$product_price</p>
                            <a href='index.php?add_to_cart=$product_id' class='btn btn-success'>Buy Now</a>
                            <a href='index.php' class='btn btn-secondary'>Go Home</a>   
                        </div>
                    </div>
                </div>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //view details function 
    function view_details() {
        global $conn;
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
            
            while($row = mysqli_fetch_assoc($result)){
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
                
                echo "<div class='col-md-12 mb-4'>
                    <div class='card'>
                        <div class='row g-0'>
                            <div class='col-md-6'>
                                <div id='productCarousel' class='carousel slide' data-bs-ride='carousel'>
                                    <div class='carousel-indicators'>
                                        <button type='button' data-bs-target='#productCarousel' data-bs-slide-to='0' class='active'></button>
                                        <button type='button' data-bs-target='#productCarousel' data-bs-slide-to='1'></button>
                                        <button type='button' data-bs-target='#productCarousel' data-bs-slide-to='2'></button>
                                    </div>
                                    <div class='carousel-inner'>
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
                                    </button>
                                    <button class='carousel-control-next' type='button' data-bs-target='#productCarousel' data-bs-slide='next'>
                                        <span class='carousel-control-next-icon'></span>
                                    </button>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$product_title</h5>
                                    <p class='card-text'>$product_description</p>
                                    <p class='card-text'><strong>Price: </strong>$$product_price</p>
                                    <div class='mt-4'>
                                        <a href='index.php?add_to_cart=$product_id' class='btn btn-success'>Add to cart</a>
                                        <a href='index.php' class='btn btn-secondary'>Continue Shopping</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    //get ip address of user    
    function getIPAddress() {
        //whether ip is from share internet  
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from proxy
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from remote address
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    //cart function
    function cart() {
        if(isset($_GET['add_to_cart'])) {
            global $conn;
            $get_product_id = (int)$_GET['add_to_cart'];
            $get_ip_address = getIPAddress();
            
            // Use transaction to prevent race conditions
            mysqli_begin_transaction($conn);
            
            try {
                // First check if the item is already in cart for this IP
                $select_query = "SELECT * FROM cart_details WHERE ip_address=? AND product_id=? FOR UPDATE";
                $stmt = mysqli_prepare($conn, $select_query);
                mysqli_stmt_bind_param($stmt, "si", $get_ip_address, $get_product_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if(!$result) {
                    throw new Exception("Query failed: " . mysqli_error($conn));
                }
                
                if(mysqli_num_rows($result) > 0) {
                    // Item exists, update quantity
                    $update_query = "UPDATE cart_details SET quantity = quantity + 1 WHERE ip_address=? AND product_id=?";
                    $stmt = mysqli_prepare($conn, $update_query);
                    mysqli_stmt_bind_param($stmt, "si", $get_ip_address, $get_product_id);
                    
                    if(!mysqli_stmt_execute($stmt)) {
                        throw new Exception("Update failed: " . mysqli_error($conn));
                    }
                    
                    $message = "Item quantity updated in cart";
                } else {
                    // Item doesn't exist, insert new record
                    $insert_query = "INSERT INTO cart_details (product_id, ip_address, quantity) VALUES (?, ?, 1)";
                    $stmt = mysqli_prepare($conn, $insert_query);
                    mysqli_stmt_bind_param($stmt, "is", $get_product_id, $get_ip_address);
                    
                    if(!mysqli_stmt_execute($stmt)) {
                        // If insert fails, try one more time with REPLACE INTO
                        $replace_query = "REPLACE INTO cart_details (product_id, ip_address, quantity) VALUES (?, ?, 1)";
                        $stmt = mysqli_prepare($conn, $replace_query);
                        mysqli_stmt_bind_param($stmt, "is", $get_product_id, $get_ip_address);
                        
                        if(!mysqli_stmt_execute($stmt)) {
                            throw new Exception("Insert failed: " . mysqli_error($conn));
                        }
                    }
                    
                    $message = "Item added to cart";
                }
                
                // Commit the transaction
                mysqli_commit($conn);
                
                echo "<script>alert('$message');</script>";
                
                // Get the current page URL to redirect back
                $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
                echo "<script>window.open('$redirect_url','_self');</script>";
                
            } catch (Exception $e) {
                // Rollback on error
                mysqli_rollback($conn);
                
                // Try one more time with REPLACE INTO as a last resort
                try {
                    $replace_query = "REPLACE INTO cart_details (product_id, ip_address, quantity) VALUES (?, ?, 1)";
                    $stmt = mysqli_prepare($conn, $replace_query);
                    mysqli_stmt_bind_param($stmt, "is", $get_product_id, $get_ip_address);
                    mysqli_stmt_execute($stmt);
                    
                    echo "<script>alert('Item added to cart');</script>";
                    
                    // Get the current page URL to redirect back
                    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
                    echo "<script>window.open('$redirect_url','_self');</script>";
                    
                } catch (Exception $inner_e) {
                    // Just log the error and redirect without showing PHP errors
                    error_log("Cart error: " . $inner_e->getMessage());
                    echo "<script>alert('Could not add item to cart. Please try again.');</script>";
                    echo "<script>window.open('index.php','_self');</script>";
                }
            }
        }
    }

    // function to get cart items numbers
    function cart_item() {
        global $conn;
        $ip = getIPAddress();
        $select_query = "SELECT SUM(quantity) as total FROM cart_details WHERE ip_address=?";
        $stmt = mysqli_prepare($conn, $select_query);
        mysqli_stmt_bind_param($stmt, "s", $ip);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
        
        $row = mysqli_fetch_assoc($result);
        $count = $row['total'] ?? 0;
        mysqli_stmt_close($stmt);
        return $count;
    }

    function total_cart_price() {
        global $conn;
        $ip = getIPAddress();
        $total_price = 0;
        
        $select_query = "SELECT p.product_price, c.quantity 
                         FROM cart_details c
                         JOIN products p ON c.product_id = p.product_id 
                         WHERE c.ip_address=?";
        
        $stmt = mysqli_prepare($conn, $select_query);
        mysqli_stmt_bind_param($stmt, "s", $ip);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
        
        while($row = mysqli_fetch_assoc($result)) {
            $total_price += ($row['product_price'] * $row['quantity']);
        }
        mysqli_stmt_close($stmt);
        echo $total_price;
    }

    //function to get cart items numbers
    function cart_item_numbers() {
        global $conn;
        $ip = getIPAddress();
        
        $select_query = "SELECT SUM(quantity) as total FROM cart_details WHERE ip_address=?";
        $stmt = mysqli_prepare($conn, $select_query);
        mysqli_stmt_bind_param($stmt, "s", $ip);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
        
        $row = mysqli_fetch_assoc($result);
        $count = $row['total'] ?? 0;
        mysqli_stmt_close($stmt);
        echo $count;
    }

    //function to get user id
    function get_user_id() {
        if(isset($_SESSION['user_id'])) {
            return (int)$_SESSION['user_id'];
        }
        return null;
    }

    //function to get user name 
    function get_user_name() {
        if(isset($_SESSION['username'])) {
            return htmlspecialchars($_SESSION['username']);
        }
        return null;
    }

    //function to get user email
    function get_user_email() {
        global $conn;
        if(isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            // Get email from database since it's not stored in session
            $username = mysqli_real_escape_string($conn, $_SESSION['username']);
            $query = "SELECT user_email FROM user_table WHERE username='$username'";
            $result = mysqli_query($conn, $query);
            if($result && $row = mysqli_fetch_assoc($result)) {
                return $row['user_email'];
            }
        }
        return null;
    }

    // Get user order details with prepared statement
    function get_user_order_details() {
        global $conn;
        $user_id = get_user_id();
        
        $select_query = "SELECT * FROM user_orders WHERE user_id=? ORDER BY order_date DESC";
        $stmt = mysqli_prepare($conn, $select_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
        return $result;
    }

    // Edit account details
    function edit_account($email, $mobile, $address, $image = null) {
        global $conn;
        $user_id = get_user_id();
        
        $update_query = "UPDATE user_table SET user_email=?, user_mobile=?, user_address=?";
        $params = [$email, $mobile, $address];
        $types = "sss";
        
        if($image) {
            $update_query .= ", user_image=?";
            $params[] = $image;
            $types .= "s";
        }
        
        $update_query .= " WHERE user_id=?";
        $params[] = $user_id;
        $types .= "i";
        
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if(mysqli_stmt_execute($stmt)) {
            return true;
        }
        return false;
    }

    // Delete user account and related data
    function delete_account() {
        global $conn;
        $user_id = get_user_id();
        
        mysqli_begin_transaction($conn);
        try {
            // Delete from cart_details
            $delete_cart = "DELETE FROM cart_details WHERE ip_address=?";
            $stmt = mysqli_prepare($conn, $delete_cart);
            mysqli_stmt_bind_param($stmt, "s", getIPAddress());
            mysqli_stmt_execute($stmt);
            
            // Delete from orders_pending
            $delete_pending = "DELETE FROM orders_pending WHERE user_id=?";
            $stmt = mysqli_prepare($conn, $delete_pending);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            
            // Delete from user_orders
            $delete_orders = "DELETE FROM user_orders WHERE user_id=?";
            $stmt = mysqli_prepare($conn, $delete_orders);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            
            // Delete user account
            $delete_user = "DELETE FROM user_table WHERE user_id=?";
            $stmt = mysqli_prepare($conn, $delete_user);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            
            mysqli_commit($conn);
            session_destroy();
            return true;
        } catch(Exception $e) {
            mysqli_rollback($conn);
            return false;
        }
    }
}