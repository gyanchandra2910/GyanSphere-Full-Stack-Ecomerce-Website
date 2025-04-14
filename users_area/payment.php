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

$user_id = get_user_id();
$username = get_user_name(); 
$user_email = get_user_email();
$user_ip = getIPAddress();

// Get total amount from cart
$total_amount = 0;
$cart_query = "SELECT p.product_price, c.quantity 
               FROM cart_details c
               JOIN products p ON c.product_id = p.product_id 
               WHERE c.ip_address='" . mysqli_real_escape_string($conn, $user_ip) . "'";
$result = mysqli_query($conn, $cart_query);
if(!$result) {
    die("Query failed: " . mysqli_error($conn));
}
while($row = mysqli_fetch_array($result)) {
    $total_amount += ($row['product_price'] * $row['quantity']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GyanSphere - Payment Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .payment-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .payment-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .payment-card:hover {
            transform: translateY(-5px);
        }
        .payment-header {
            background: linear-gradient(45deg, #28a745, #218838);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .payment-body {
            padding: 30px;
        }
        .payment-option {
            border: 2px solid #e9ecef;
            padding: 25px;
            margin: 15px 0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .payment-option:hover {
            border-color: #28a745;
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .payment-option.active {
            border-color: #28a745;
            background-color: #f8f9fa;
        }
        .payment-icon {
            width: 60px;
            height: 60px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 24px;
            color: #28a745;
        }
        .payment-details {
            flex-grow: 1;
        }
        .payment-methods {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        .payment-method-icon {
            font-size: 24px;
            color: #6c757d;
            transition: color 0.3s ease;
        }
        .payment-method-icon:hover {
            color: #28a745;
        }
        .order-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .summary-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .btn-pay {
            background: linear-gradient(45deg, #28a745, #218838);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        .logo-img {
            max-width: 150px;
            height: auto;
            margin: 20px auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="text-center mb-4">
            <img src="../images/logo.png" alt="GyanSphere Logo" class="logo-img">
            <div class="mt-3">
                <img src="../images/gyan.png" alt="Gyan Chandra" style="max-width: 200px; height: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            </div>
        </div>
        
        <div class="payment-card">
            <div class="payment-header">
                <h3 class="mb-0">Complete Your Payment at GyanSphere</h3>
            </div>
            <div class="payment-body">
                <div class="order-summary">
                    <h5 class="mb-4">Order Summary</h5>
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>$<?php echo htmlspecialchars(number_format($total_amount, 2)); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-item">
                        <strong>Total Amount</strong>
                        <strong>$<?php echo htmlspecialchars(number_format($total_amount, 2)); ?></strong>
                    </div>
                </div>

                <h5 class="mb-4">Select Payment Method</h5>
                
                <div class="payment-option" onclick="window.location='order.php'">
                    <div class="payment-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="payment-details">
                        <h5>Credit/Debit Card</h5>
                        <p class="text-muted">Pay securely using your credit or debit card</p>
                        <div class="payment-methods">
                            <i class="fab fa-cc-visa payment-method-icon"></i>
                            <i class="fab fa-cc-mastercard payment-method-icon"></i>
                            <i class="fab fa-cc-amex payment-method-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="payment-option" onclick="window.location='order.php'">
                    <div class="payment-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="payment-details">
                        <h5>Bank Transfer</h5>
                        <p class="text-muted">Transfer money directly from your bank account</p>
                        <div class="payment-methods">
                            <i class="fas fa-university payment-method-icon"></i>
                            <i class="fas fa-exchange-alt payment-method-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="payment-option" onclick="window.location='order.php'">
                    <div class="payment-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="payment-details">
                        <h5>UPI Payment</h5>
                        <p class="text-muted">Pay using UPI apps like Google Pay, PhonePe, etc.</p>
                        <div class="payment-methods">
                            <i class="fab fa-google-pay payment-method-icon"></i>
                            <i class="fas fa-mobile-alt payment-method-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="payment-option" onclick="window.location='order.php'">
                    <div class="payment-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="payment-details">
                        <h5>Cash on Delivery</h5>
                        <p class="text-muted">Pay when you receive your order</p>
                        <div class="payment-methods">
                            <i class="fas fa-money-bill-wave payment-method-icon"></i>
                            <i class="fas fa-truck payment-method-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="../cart.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Cart
                    </a>
                    <button class="btn btn-pay text-white" onclick="window.location='order.php'">
                        Proceed to Payment <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5">
        <div class="bg-success p-3 text-center">
            <div class="container mb-3">
                <div class="d-flex justify-content-center gap-3 fs-4">
                    <a href="#" class="text-light"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/gyan.2910/" target="_blank" class="text-light"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/gyanchandra29102003" target="_blank" class="text-light"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <p class="text-white mb-0">&copy; <?php echo date('Y'); ?> GyanSphere. All rights reserved. Developed by Gyan Chandra-2025</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add active class to clicked payment option
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>