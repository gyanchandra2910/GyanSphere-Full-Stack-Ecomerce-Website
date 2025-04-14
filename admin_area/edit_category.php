<?php
if(isset($_GET['edit_category'])) {
    $edit_id = (int)$_GET['edit_category'];
    
    // Get current category data
    $get_data = "SELECT * FROM `categories` WHERE category_id=?";
    $stmt = mysqli_prepare($conn, $get_data);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if(!$row) {
        echo "<div class='alert alert-danger'>Category not found</div>";
        exit();
    }

    $category_title = htmlspecialchars($row['category_title']);
?>

    <div class="container mt-5">
        <h1 class="text-center">Edit Category</h1>
        <form action="" method="post">
            <div class="form-group mb-4">
                <label for="category_title">Category Title</label>
                <input type="text" name="category_title" id="category_title" class="form-control" value="<?php echo $category_title; ?>" required>
            </div>

            <div class="form-group mb-4">
                <input type="submit" name="edit_category" value="Update Category" class="btn btn-success">
                <a href="index.php?view_categories" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

<?php
}

if(isset($_POST['edit_category'])) {
    $category_title = mysqli_real_escape_string($conn, $_POST['category_title']);
    $edit_id = (int)$_GET['edit_category'];

    $update_query = "UPDATE `categories` SET category_title=? WHERE category_id=?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $category_title, $edit_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('Category updated successfully')</script>";
        echo "<script>window.open('index.php?view_categories','_self')</script>";
    } else {
        echo "<script>alert('Error updating category')</script>";
    }
}
?>
