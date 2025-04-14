<?php
if(isset($_GET['delete_brand'])) {
    $delete_id = (int)$_GET['delete_brand'];

    // Check if brand exists and delete it
    $delete_brand = "DELETE FROM brands WHERE brand_id = ?";
    $stmt = mysqli_prepare($conn, $delete_brand);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('Brand deleted successfully')</script>";
        echo "<script>window.open('index.php?view_brands','_self')</script>";
    } else {
        echo "<script>alert('Error deleting brand')</script>";
    }
}
?>
