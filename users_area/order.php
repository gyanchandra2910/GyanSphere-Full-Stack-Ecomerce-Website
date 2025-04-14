<?php
include '../functions/common_functions.php';
include '../includes/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = get_user_id();

if(!isset($_SESSION['username'])) {
    echo "<script>window.open('user_login.php','_self')</script>";
    exit();
}

// Get total items and total price of all items
$total_price = 0;
$total_items = 0;

$ip = getIPAddress();
// Generate invoice number using just timestamp to avoid overflow
$invoice_number = time(); 
$status = 'pending';

// Get cart items
$cart_query = "SELECT c.*, p.product_price, p.product_id 
               FROM cart_details c
               JOIN products p ON c.product_id = p.product_id 
               WHERE c.ip_address = '" . mysqli_real_escape_string($conn, $ip) . "'";
$result_cart = mysqli_query($conn, $cart_query);

if(!$result_cart) {
    die("Query failed: " . mysqli_error($conn));
}

$total_items = mysqli_num_rows($result_cart);

// Calculate total price and store cart items for pending orders
$cart_items = array();
while($row = mysqli_fetch_assoc($result_cart)) {
    $total_price += ($row['product_price'] * $row['quantity']);
    $cart_items[] = array(
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity']
    );
}

// Insert order if cart is not empty
if($total_items > 0) {
    $insert_order = "INSERT INTO user_orders (user_id, amount_due, invoice_number, total_products, order_date, order_status) 
                    VALUES (?, ?, ?, ?, NOW(), ?)";
                    
    $stmt = mysqli_prepare($conn, $insert_order);
    mysqli_stmt_bind_param($stmt, "idiis", $user_id, $total_price, $invoice_number, $total_items, $status);
    
    if(mysqli_stmt_execute($stmt)) {
        // Insert pending orders first
        $insert_pending_order = "INSERT INTO orders_pending (user_id, invoice_number, product_id, quantity, order_status) VALUES (?, ?, ?, ?, ?)";
        $stmt_pending = mysqli_prepare($conn, $insert_pending_order);
        
        foreach($cart_items as $item) {
            mysqli_stmt_bind_param($stmt_pending, "iiiis", $user_id, $invoice_number, $item['product_id'], $item['quantity'], $status);
            mysqli_stmt_execute($stmt_pending);
        }
        
        mysqli_stmt_close($stmt_pending);
        
        // Clear cart after successful order and pending orders insertion
        $clear_cart = "DELETE FROM cart_details WHERE ip_address = '" . mysqli_real_escape_string($conn, $ip) . "'";
        mysqli_query($conn, $clear_cart);
        
        echo "<script>alert('Order placed successfully!')</script>";
        echo "<script>window.open('profile.php?my_orders','_self')</script>";
    } else {
        echo "<script>alert('Error placing order: " . mysqli_error($conn) . "')</script>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Your cart is empty!')</script>";
    echo "<script>window.open('../cart.php','_self')</script>";
}
?>
