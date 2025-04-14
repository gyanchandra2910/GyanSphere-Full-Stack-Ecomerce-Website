<?php
if(isset($_GET['delete_user'])) {
    $delete_id = (int)$_GET['delete_user'];

    // Check if user exists and delete it
    $delete_user = "DELETE FROM user_table WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $delete_user);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('User deleted successfully')</script>";
        echo "<script>window.open('index.php?list_users','_self')</script>";
    } else {
        echo "<script>alert('Error deleting user')</script>";
    }
}
?>
