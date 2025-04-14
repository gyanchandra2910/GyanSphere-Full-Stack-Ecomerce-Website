<?php
if(isset($_GET['edit_user'])) {
    $edit_id = (int)$_GET['edit_user'];
    
    // Get current user data
    $get_data = "SELECT * FROM `user_table` WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $get_data);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if(!$row) {
        echo "<div class='alert alert-danger'>User not found</div>";
        exit();
    }

    $username = htmlspecialchars($row['username']);
    $user_email = htmlspecialchars($row['user_email']);
    $user_address = htmlspecialchars($row['user_address']);
    $user_mobile = htmlspecialchars($row['user_mobile']);
?>

    <div class="container mt-5">
        <h1 class="text-center">Edit User</h1>
        <form action="" method="post">
            <div class="form-group mb-4">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>" required>
            </div>

            <div class="form-group mb-4">
                <label for="user_email">Email</label>
                <input type="email" name="user_email" id="user_email" class="form-control" value="<?php echo $user_email; ?>" required>
            </div>

            <div class="form-group mb-4">
                <label for="user_address">Address</label>
                <textarea name="user_address" id="user_address" class="form-control" required><?php echo $user_address; ?></textarea>
            </div>

            <div class="form-group mb-4">
                <label for="user_mobile">Mobile</label>
                <input type="tel" name="user_mobile" id="user_mobile" class="form-control" value="<?php echo $user_mobile; ?>" required>
            </div>

            <div class="form-group mb-4">
                <input type="submit" name="edit_user" value="Update User" class="btn btn-success">
                <a href="index.php?list_users" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

<?php
}

if(isset($_POST['edit_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $user_address = mysqli_real_escape_string($conn, $_POST['user_address']);
    $user_mobile = mysqli_real_escape_string($conn, $_POST['user_mobile']);
    $edit_id = (int)$_GET['edit_user'];

    $update_query = "UPDATE `user_table` SET username=?, user_email=?, user_address=?, user_mobile=? WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssi", $username, $user_email, $user_address, $user_mobile, $edit_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('User updated successfully')</script>";
        echo "<script>window.open('index.php?list_users','_self')</script>";
    } else {
        echo "<script>alert('Error updating user')</script>";
    }
}
?>
