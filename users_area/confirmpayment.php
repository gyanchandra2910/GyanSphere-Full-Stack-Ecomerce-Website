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

// Get order ID from URL and validate it's a positive integer
if(!isset($_GET['order_id']) || !filter_var($_GET['order_id'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
    echo "<script>window.open('profile.php','_self')</script>";
    exit();
}

$order_id = (int)$_GET['order_id'];
$user_id = get_user_id();
// Get order details including invoice number and amount due
$verify_query = "SELECT order_id, invoice_number, amount_due FROM user_orders WHERE order_id = ? AND user_id = ? AND order_status = 'pending'";
$stmt = mysqli_prepare($conn, $verify_query);
if(!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
if(!mysqli_stmt_execute($stmt)) {
    die("Query execution failed: " . mysqli_error($conn));
}

$result = mysqli_stmt_get_result($stmt);
if(!$result || mysqli_num_rows($result) == 0) {
    echo "<script>window.open('profile.php','_self')</script>";
    exit();
}

$order_details = mysqli_fetch_assoc($result);
$invoice_number = $order_details['invoice_number'];
$amount = $order_details['amount_due'];

if(isset($_POST['confirm_payment'])) {
    $payment_mode = filter_input(INPUT_POST, 'payment_mode', FILTER_SANITIZE_STRING);
    
    // Validate payment mode
    if(!$payment_mode) {
        echo "<script>alert('Please select a payment mode')</script>";
        exit();
    }
    // Insert payment record
    $current_date = date('Y-m-d H:i:s');
    $insert_query = "INSERT INTO user_payments (order_id, invoice_number, amount, payment_mode, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    if(!$stmt) {
        die("Query preparation failed: " . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "isdss", $order_id, $invoice_number, $amount, $payment_mode, $current_date);
    if(!mysqli_stmt_execute($stmt)) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);

    // Update order status
    $update_query = "UPDATE user_orders SET order_status = 'complete' WHERE order_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    if(!$stmt) {
        die("Query preparation failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
    if(!mysqli_stmt_execute($stmt)) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);

    echo "<script>alert('Payment confirmed successfully')</script>";
    echo "<script>window.open('profile.php','_self')</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <!-- Navbar -->     
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <img src="../images/logo.png" alt="Store Logo" class="logo">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php"><i class="fas fa-home"></i>Home</a>
                    </li>       
                    <li class="nav-item">
                        <a class="nav-link" href="../display_all.php"><i class="fas fa-store"></i>Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cart.php"><i class="fas fa-shopping-cart"></i>Cart</a>
                    </li>   
                    <li class="nav-item">
                        <a class="nav-link" href="../contact.php"><i class="fas fa-envelope"></i>Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>  

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Confirm Payment</h4>   
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($invoice_number); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="text" class="form-control" value="$<?php echo htmlspecialchars($amount); ?>" readonly>
                            </div>                  
                            <div class="mb-3">
                                <label for="payment_mode" class="form-label">Payment Mode</label>
                                <select class="form-select" id="payment_mode" name="payment_mode" required>
                                    <option value="">Select Payment Mode</option>
                                    <option value="upi">UPI</option>
                                    <option value="netbanking">Net Banking</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="cod">Cash on Delivery</option>
                                    <option value="payoffline">Pay Offline</option>
                                </select>
                            </div>
                            <button type="submit" name="confirm_payment" class="btn btn-success">Confirm Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
