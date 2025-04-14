<?php
if(isset($_GET['delete_order'])) {
    $order_id = (int)$_GET['delete_order'];
    
    // Prepare and execute delete query
    $delete_query = "DELETE FROM user_orders WHERE order_id=?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('Order deleted successfully')</script>";
        echo "<script>window.location.href='index.php?list_orders'</script>";
    } else {
        echo "<script>alert('Error deleting order')</script>";
    }
}
?>
