<!-- connect file -->
<?php
include('./includes/connect.php');
include('./functions/common_functions.php');
session_start(); // Add session_start() since we're checking session variables

// Handle remove operation
if(isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    $delete_query = "DELETE FROM cart_details WHERE product_id = $remove_id AND ip_address='" . mysqli_real_escape_string($conn, getIPAddress()) . "'";
    $result = mysqli_query($conn, $delete_query);
    if($result) {
        echo "<script>alert('Item removed from cart');</script>";
        echo "<script>window.open('cart.php','_self');</script>";
    }
}

// Handle update operation 
if(isset($_POST['update_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $qty = (int)$_POST['qty'];
    if($qty < 1) {
        echo "<script>alert('Quantity must be at least 1');</script>";
    } else {
        $update_query = "UPDATE cart_details SET quantity = $qty 
                        WHERE product_id = $product_id AND ip_address='" . mysqli_real_escape_string($conn, getIPAddress()) . "'";
        $result = mysqli_query($conn, $update_query);
        if($result) {
            echo "<script>alert('Quantity updated');</script>";
            echo "<script>window.open('cart.php','_self');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - Cart Details</title>
    <!-- bootstrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- bootstrap font link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        .cart-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- navbar -->
    <div class="container-fluid p-0">
        <!-- first child -->
        <nav class="navbar navbar-expand-lg navbar-light bg-success">
            <div class="container-fluid">
                <img src="./images/logo.png" alt="GyanSphere Logo" class="logo">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="display_all.php">Products</a>
                        </li>
                        <?php if(!isset($_SESSION['username'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="users_area/user_registration.php">Register</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup><?php cart_item_numbers(); ?></sup></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">Total Price: $<?php total_cart_price(); ?></a>
                        </li>       
                    </ul>
                </div>
            </div>
        </nav>
        <!-- calling cart function -->
        <?php cart(); ?>    

        <!-- second child -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Welcome <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></a>
                </li>
                <li class="nav-item">
                    <?php if(!isset($_SESSION['username'])): ?>
                        <a class="nav-link" href="users_area/user_login.php">Login</a>
                    <?php else: ?>
                        <a class="nav-link" href="users_area/logout.php">Logout</a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>

        <!-- third child -->
        <div class="bg-light">
            <h3 class="text-center">GyanSphere</h3>
            <div class="text-center my-3">
                <img src="./images/gyan.png" alt="Gyan Chandra" style="max-width: 200px; height: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            </div>
            <p class="text-center">Communications is at the heart of e-commerce and community</p>
        </div>
        <!-- fourth child -->
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive mb-5">
                        <?php
                        global $conn;
                        $ip = getIPAddress();
                        
                        // Check if cart is empty
                        $check_cart = "SELECT COUNT(*) as count FROM cart_details WHERE ip_address='" . mysqli_real_escape_string($conn, $ip) . "'";
                        $result_check = mysqli_query($conn, $check_cart);
                        $row_count = mysqli_fetch_assoc($result_check);
                        
                        if($row_count['count'] > 0) {
                            // Cart has items - show table
                        ?>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Product Title</th>
                                    <th>Product Image</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>    
                                    <th>Remove</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <!-- php code to display dynamic data    -->
                                <?php
                                $total_price = 0;
                                
                                // Join with products table to get all needed product details
                                $select_query = "SELECT p.product_title, p.product_image1, p.product_price, c.quantity, c.product_id 
                                               FROM cart_details c
                                               JOIN products p ON c.product_id = p.product_id 
                                               WHERE c.ip_address='" . mysqli_real_escape_string($conn, $ip) . "'";
                                
                                $result = mysqli_query($conn, $select_query);
                                if(!$result) {
                                    die("Query failed: " . mysqli_error($conn));
                                }
                                
                                while($row = mysqli_fetch_assoc($result)) {
                                    $product_title = htmlspecialchars($row['product_title']);
                                    $product_image = htmlspecialchars($row['product_image1']);
                                    $quantity = (int)$row['quantity'];
                                    $price = (float)$row['product_price'];
                                    $product_id = (int)$row['product_id'];
                                    $total = $price * $quantity;
                                    $total_price += $total;
                                    ?>
                                    <tr>
                                        <td><?php echo $product_title; ?></td>
                                        <td><img src="./admin_area/product_images/<?php echo $product_image; ?>" alt="<?php echo $product_title; ?>" class="cart-img"></td>
                                        <td><?php echo $quantity; ?></td>
                                        <td>$<?php echo number_format($total, 2); ?></td>
                                        <td><a href="cart.php?remove=<?php echo $product_id; ?>" class="text-danger" onclick="return confirm('Are you sure you want to remove this item?')"><i class="fa-solid fa-trash"></i></a></td>
                                        <td>
                                            <form action="" method="post" class="d-flex justify-content-center align-items-center">
                                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                                <input type="number" name="qty" value="<?php echo $quantity; ?>" class="form-control w-50 me-2" min="1">
                                                <input type="submit" value="Update" name="update_cart" class="btn btn-info btn-sm">
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="d-flex mb-3 align-items-center">
                            <h4 class="px-3">Subtotal:</h4>
                            <h5 class="text-info px-3">$<?php echo number_format($total_price, 2); ?></h5>
                            <div class="ms-auto">
                                <a href="index.php" class="btn btn-success mx-2">Continue Shopping</a>
                                <a href="./users_area/checkout.php" class="btn btn-success mx-2">Proceed to Checkout</a>
                            </div>
                        </div>
                        <?php
                        } else {
                            // Cart is empty - show message
                            echo "<h3 class='text-center text-danger'>Your cart is empty!</h3>";
                            echo "<div class='text-center mt-3'>";
                            echo "<a href='index.php' class='btn btn-success'>Continue Shopping</a>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- last child -->
        <!-- include footer -->
        <div class="footer pt-3">
            <div class="container">
                <div class="row mb-3">
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
            </div>
            <?php include('./includes/footer.php'); ?>
        </div>
    </div>

</body>
<!-- bootstrap js link -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>