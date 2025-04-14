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

// Get user's orders
$get_orders = "SELECT * FROM user_orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = mysqli_prepare($conn, $get_orders);
if(!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
if(!mysqli_stmt_execute($stmt)) {
    die("Query execution failed: " . mysqli_error($conn));
}

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">My Orders</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-success text-light">
                    <tr>
                        <th>Order #</th>
                        <th>Amount Due</th>
                        <th>Total Products</th>
                        <th>Invoice Number</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['order_id']; ?></td>
                                <td>$<?php echo $row['amount_due']; ?></td>
                                <td><?php echo $row['total_products']; ?></td>
                                <td><?php echo $row['invoice_number']; ?></td>
                                <td><?php echo $row['order_date']; ?></td>
                                <td><?php echo $row['order_status']; ?></td>
                                <td>
                                    <?php
                                    if($row['order_status'] == 'complete') {
                                        echo "<a href='confirmpayment.php?order_id=" . $row['order_id'] . "' class='btn btn-success' disabled>Payment Complete</a>";
                                    } else {
                                        echo "<a href='confirmpayment.php?order_id=" . $row['order_id'] . "' class='btn btn-primary'>Confirm Payment</a>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
