<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if(!isset($_SESSION['username'])) {
    echo "<script>window.open('user_login.php','_self')</script>";
    exit();
}

$username = $_SESSION['username'];
$user_id = get_user_id();

// Get user details
$select_query = "SELECT * FROM user_table WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $select_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$user_email = $row['user_email'];
$user_address = $row['user_address'];
$user_mobile = $row['user_mobile'];
$user_image = $row['user_image'] ?? 'user.png'; // Default to user.png if no image set

// Handle edit account form submission
if(isset($_POST['edit_account'])) {
    $new_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $new_mobile = mysqli_real_escape_string($conn, $_POST['user_mobile']); 
    $new_address = mysqli_real_escape_string($conn, $_POST['user_address']);
    
    // Handle image upload
    if(isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
        $image_tmp = $_FILES['user_image']['tmp_name'];
        $image_name = $user_id . '_' . $_FILES['user_image']['name'];
        move_uploaded_file($image_tmp, "user_images/$image_name");
        
        if(edit_account($new_email, $new_mobile, $new_address, $image_name)) {
            echo "<script>alert('Account updated successfully')</script>";
            echo "<script>window.location.href='profile.php'</script>";
        }
    } else {
        if(edit_account($new_email, $new_mobile, $new_address)) {
            echo "<script>alert('Account updated successfully')</script>";
            echo "<script>window.location.href='profile.php'</script>";
        }
    }
}

// Handle delete account
if(isset($_POST['delete_account'])) {
    if(delete_account()) {
        session_destroy();
        echo "<script>alert('Account deleted successfully')</script>";
        echo "<script>window.location.href='../index.php'</script>";
    }
}

// Handle payment confirmation
if(isset($_POST['confirm_payment'])) {
    $order_id = $_POST['order_id'];
    echo "<script>window.location.href='confirmpayment.php?order_id=" . $order_id . "'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - User Dashboard</title>
    <!-- Bootstrap CSS Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        
        .sidebar-link {
            color: #333;
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: block;
            text-decoration: none;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            color: #fff;
            background-color: var(--primary-color);
            transform: translateX(5px);
        }
        
        .sidebar-link i {
            margin-right: 10px;
            width: 20px;
        }
        
        .sidebar {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 20px;
            height: 100%;
        }
        
        .content-area {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 25px;
        }
        
        .user-info {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            padding: 20px;
            border-radius: 15px;
            color: white;
            margin-bottom: 20px;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stats-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .stats-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .stats-title {
            color: #6c757d;
            font-size: 1rem;
        }
        
        .orders-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .orders-table thead {
            background-color: var(--primary-color);
            color: white;
        }
        
        .orders-table th {
            padding: 15px;
            font-weight: 500;
        }
        
        .orders-table td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        .orders-table tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .order-id {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .btn-custom {
            background-color: var(--primary-color);
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            color: white;
        }
        
        .btn-custom:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-custom-danger {
            background-color: var(--accent-color);
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            color: white;
        }
        
        .btn-custom-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
            color: white;
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
                            <a class="nav-link active" href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
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
                <h1>My Dashboard</h1>
                <div class="mb-3 mt-3">
                    <img src="../images/gyan.png" alt="Gyan Chandra" style="max-width: 250px; height: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                </div>
                <p>Manage your account, view orders, and more at GyanSphere</p>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 mb-4">
                    <div class="sidebar p-4">
                        <div class="text-center mb-4">
                            <img src="user_images/<?php echo htmlspecialchars($user_image); ?>" alt="Profile Picture" class="profile-img mb-3">
                            <div class="user-info">
                                <h4 class="mb-0"><?php echo htmlspecialchars($username); ?></h4>
                                <small>Member since <?php echo date('M Y'); ?></small>
                            </div>
                        </div>
                        
                        <div class="list-group">
                            <a href="profile.php" class="sidebar-link <?php echo !isset($_GET['edit_account']) && !isset($_GET['my_orders']) && !isset($_GET['delete_account']) ? 'active' : ''; ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="profile.php?my_orders" class="sidebar-link <?php echo isset($_GET['my_orders']) ? 'active' : ''; ?>">
                                <i class="fas fa-shopping-bag"></i> My Orders
                            </a>
                            <a href="profile.php?edit_account" class="sidebar-link <?php echo isset($_GET['edit_account']) ? 'active' : ''; ?>">
                                <i class="fas fa-user-edit"></i> Edit Account
                            </a>
                            <a href="profile.php?delete_account" class="sidebar-link <?php echo isset($_GET['delete_account']) ? 'active' : ''; ?>">
                                <i class="fas fa-user-times"></i> Delete Account
                            </a>
                            <a href="logout.php" class="sidebar-link">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9 p-4">
                    <?php
                    if(isset($_GET['my_orders'])) {
                        // Display user orders
                        $orders = get_user_order_details();
                        if(mysqli_num_rows($orders) > 0) {
                            echo "<h3 class='mb-4'>My Orders</h3>
                            <div class='table-responsive orders-table'>
                                <table class='table table-borderless mb-0'>
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Amount</th>
                                            <th>Products</th>
                                            <th>Invoice Number</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                                    
                            $order_array = array();
                            while($order = mysqli_fetch_assoc($orders)) {
                                $order_array[] = $order;
                            }
                            
                            // Sort orders by date in descending order (newest first)
                            usort($order_array, function($a, $b) {
                                return strtotime($b['order_date']) - strtotime($a['order_date']); 
                            });
                            
                            foreach($order_array as $order) {
                                $order_status = $order['order_status'] == 'complete' ? 'complete' : 'pending';
                                $status_badge = $order_status == 'complete' ? 'bg-success' : 'bg-warning';
                                
                                echo "<tr>
                                    <td class='order-id'>#{$order['order_id']}</td>
                                    <td>\${$order['amount_due']}</td>
                                    <td>{$order['total_products']}</td>
                                    <td>{$order['invoice_number']}</td>
                                    <td>" . date('d M Y', strtotime($order['order_date'])) . "</td>
                                    <td><span class='badge $status_badge'>$order_status</span></td>
                                    <td>";
                                if($order['order_status'] != 'complete') {
                                    echo "<form method='post' style='display:inline;'>
                                        <input type='hidden' name='order_id' value='{$order['order_id']}'>
                                        <button type='submit' name='confirm_payment' class='btn btn-sm btn-success'>Confirm Payment</button>
                                    </form>";
                                }
                                echo "</td></tr>";
                            }
                            
                            echo "</tbody></table></div>";
                        } else {
                            echo "<div class='alert alert-info'>No orders found</div>";
                        }
                    }
                    else if(isset($_GET['edit_account'])) {
                        // Display edit account form
                        ?>
                        <h3 class="mb-4">Edit Account</h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="user_email" value="<?php echo htmlspecialchars($user_email); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" class="form-control" name="user_mobile" value="<?php echo htmlspecialchars($user_mobile); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="user_address" required><?php echo htmlspecialchars($user_address); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="user_image" accept="image/*">
                            </div>
                            <button type="submit" name="edit_account" class="btn btn-success">Update Account</button>
                        </form>
                        <?php
                    }
                    else if(isset($_GET['delete_account'])) {
                        // Display delete account confirmation
                        ?>
                        <h3 class="mb-4">Delete Account</h3>
                        <div class="alert alert-danger">
                            <h5>Are you sure you want to delete your account?</h5>
                            <p>This action cannot be undone. All your data will be permanently deleted.</p>
                            <form action="" method="post">
                                <button type="submit" name="delete_account" class="btn btn-danger">Yes, Delete My Account</button>
                                <a href="profile.php" class="btn btn-secondary">No, Keep My Account</a>
                            </form>
                        </div>
                        <?php
                    }
                    else {
                        // Default dashboard view
                        ?>
                        <h3 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <div class="card order-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-user-circle"></i> Personal Information</h5>
                                        <hr>
                                        <p class="card-text">
                                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user_email); ?><br>
                                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($user_mobile); ?><br>
                                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($user_address); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            // Get total orders
                            $get_orders = "SELECT COUNT(*) as total_orders FROM user_orders WHERE user_id = ?";
                            $stmt = mysqli_prepare($conn, $get_orders);
                            mysqli_stmt_bind_param($stmt, "i", $user_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $orders_data = mysqli_fetch_assoc($result);
                            ?>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card order-card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Total Orders</h5>
                                        <hr>
                                        <div class="stats-number"><?php echo $orders_data['total_orders']; ?></div>
                                        <p class="text-muted">Orders Placed</p>
                                    </div>
                                </div>
                            </div>

                            <?php
                            // Get pending orders
                            $get_pending = "SELECT COUNT(*) as pending_orders FROM user_orders WHERE user_id = ? AND order_status = 'pending'";
                            $stmt = mysqli_prepare($conn, $get_pending);
                            mysqli_stmt_bind_param($stmt, "i", $user_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $pending_data = mysqli_fetch_assoc($result);
                            ?>

                            <div class="col-md-4 mb-3">
                                <div class="card order-card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><i class="fas fa-clock"></i> Pending Orders</h5>
                                        <hr>
                                        <div class="stats-number"><?php echo $pending_data['pending_orders']; ?></div>
                                        <p class="text-muted">Orders Pending</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
