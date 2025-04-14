<?php
// Check if session is not already started and no output has been sent
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// Only include files if they haven't been included already
if (!function_exists('getproducts')) {
    include('../includes/connect.php');
    include('../functions/common_functions.php');
}

// Verify database connection
if(!isset($conn)) {
    die("<div class='alert alert-danger'>Database connection failed. Please check your configuration.</div>");
}
?>
<h3 class="text-center p-3 mb-4" style="background-color: #f8f9fa; border-radius: 10px; color: #198754; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <i class="fas fa-box"></i> GyanSphere Products
</h3>
<table class="table table-bordered mt-5">
    <thead class="bg-success text-light">
        <tr>
            <th>Product ID</th>
            <th>Product Title</th>
            <th>Product Image</th>
            <th>Product Price</th>
            <th>Total Sold</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody class="bg-light">
        <?php
        $get_products = "SELECT * FROM `products`";
        $result = mysqli_query($conn, $get_products);
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
        $number = 0;
        while($row = mysqli_fetch_assoc($result)){
            $product_id = (int)$row['product_id'];
            $product_title = htmlspecialchars($row['product_title']);
            $product_image1 = htmlspecialchars($row['product_image1']);
            $product_price = (float)$row['product_price'];
            $status = htmlspecialchars($row['status']);
            $number++;
            ?>
            <tr>
                <td><?php echo $number; ?></td>
                <td><?php echo $product_title; ?></td>
                <td><img src='./product_images/<?php echo $product_image1; ?>' class='admin_image' style="width: 80px; height: 80px;" alt='<?php echo $product_title; ?>'/></td>
                <td>$<?php echo number_format($product_price, 2); ?></td>
                <td><?php 
                    $get_count = "SELECT COUNT(*) as count FROM `orders_pending` WHERE product_id=?";
                    $stmt = mysqli_prepare($conn, $get_count);
                    mysqli_stmt_bind_param($stmt, "i", $product_id);
                    mysqli_stmt_execute($stmt);
                    $result_count = mysqli_stmt_get_result($stmt);
                    $row_count = mysqli_fetch_assoc($result_count);
                    echo $row_count['count'];
                    mysqli_stmt_close($stmt);
                ?></td>
                <td><?php echo $status; ?></td>
                <td><a href='index.php?edit_products=<?php echo $product_id ?>' class='text-dark'><i class='fa-solid fa-pen-to-square'></i></a></td>
                <td><a href='index.php?delete_product=<?php echo $product_id ?>' class='text-dark' onclick="return confirm('Are you sure you want to delete this product?');"><i class='fa-solid fa-trash'></i></a></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>