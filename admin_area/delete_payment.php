<?php
if(isset($_GET['delete_payment'])) {
    $payment_id = (int)$_GET['delete_payment'];
    
    // Prepare and execute delete query
    $delete_query = "DELETE FROM user_payments WHERE payment_id=?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $payment_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('Payment deleted successfully')</script>";
        echo "<script>window.location.href='index.php?list_payments'</script>";
    } else {
        echo "<script>alert('Error deleting payment')</script>";
    }
}
?>
