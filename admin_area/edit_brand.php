<?php
if(isset($_GET['edit_brand'])) {
    $edit_id = (int)$_GET['edit_brand'];
    
    // Get current brand data
    $get_data = "SELECT * FROM `brands` WHERE brand_id=?";
    $stmt = mysqli_prepare($conn, $get_data);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if(!$row) {
        echo "<div class='alert alert-danger'>Brand not found</div>";
        exit();
    }

    $brand_title = htmlspecialchars($row['brand_title']);
?>

    <div class="container mt-5">
        <h1 class="text-center">Edit Brand</h1>
        <form action="" method="post">
            <div class="form-group mb-4">
                <label for="brand_title">Brand Title</label>
                <input type="text" name="brand_title" id="brand_title" class="form-control" value="<?php echo $brand_title; ?>" required>
            </div>

            <div class="form-group mb-4">
                <input type="submit" name="edit_brand" value="Update Brand" class="btn btn-success">
                <a href="index.php?view_brands" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

<?php
}

if(isset($_POST['edit_brand'])) {
    $brand_title = mysqli_real_escape_string($conn, $_POST['brand_title']);
    $edit_id = (int)$_GET['edit_brand'];

    $update_query = "UPDATE `brands` SET brand_title=? WHERE brand_id=?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $brand_title, $edit_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('Brand updated successfully')</script>";
        echo "<script>window.open('index.php?view_brands','_self')</script>";
    } else {
        echo "<script>alert('Error updating brand')</script>";
    }
}
?>
