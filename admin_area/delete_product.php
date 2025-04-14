<?php
if(isset($_GET['delete_product'])) {
    $delete_id = (int)$_GET['delete_product'];

    // First delete associated images
    $get_images = "SELECT product_image1, product_image2, product_image3 FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $get_images);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if($row) {
        // Delete physical image files if they exist
        foreach(['product_image1', 'product_image2', 'product_image3'] as $image) {
            if(!empty($row[$image])) {
                $image_path = "../product_images/" . $row[$image];
                if(file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }

        // Delete the product record
        $delete_product = "DELETE FROM products WHERE product_id = ?";
        $stmt = mysqli_prepare($conn, $delete_product);
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if($result) {
            echo "<script>alert('Product deleted successfully')</script>";
            echo "<script>window.open('index.php?view_products','_self')</script>";
        } else {
            echo "<script>alert('Error deleting product')</script>";
        }
    } else {
        echo "<script>alert('Product not found')</script>";
    }
}
?>
