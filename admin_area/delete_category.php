<?php
if(isset($_GET['delete_category'])) {
    $delete_id = (int)$_GET['delete_category'];

    // Check if category exists and delete it
    $delete_category = "DELETE FROM categories WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $delete_category);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('Category deleted successfully')</script>";
        echo "<script>window.open('index.php?view_categories','_self')</script>";
    } else {
        echo "<script>alert('Error deleting category')</script>";
    }
}
?>
