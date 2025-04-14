<?php
if(isset($_GET['edit_products'])) {
    $edit_id = (int)$_GET['edit_products'];
    $get_data = "SELECT * FROM `products` WHERE product_id=?";
    $stmt = mysqli_prepare($conn, $get_data);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if(!$row) {
        echo "<div class='alert alert-danger'>Product not found</div>";
        exit();
    }

    $product_title = htmlspecialchars($row['product_title']);
    $product_description = htmlspecialchars($row['product_description']);
    $product_keywords = htmlspecialchars($row['product_keywords']);
    $category_id = (int)$row['category_id'];
    $brand_id = (int)$row['brand_id'];
    $product_image1 = htmlspecialchars($row['product_image1']);
    $product_image2 = htmlspecialchars($row['product_image2']);
    $product_image3 = htmlspecialchars($row['product_image3']);
    $product_price = (float)$row['product_price'];
?>

    <div class="container mt-5">
        <h1 class="text-center">Edit Product</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group mb-4">
                <label for="product_title">Product Title</label>
                <input type="text" name="product_title" id="product_title" class="form-control" value="<?php echo $product_title; ?>" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="product_description">Product Description</label>
                <textarea name="product_description" id="product_description" class="form-control" rows="6" required><?php echo $product_description; ?></textarea>
            </div>

            <div class="form-group mb-4">
                <label for="product_keywords">Product Keywords</label>
                <input type="text" name="product_keywords" id="product_keywords" class="form-control" value="<?php echo $product_keywords; ?>" required>
            </div>

            <div class="form-group mb-4">
                <label for="product_category">Select Category</label>
                <select name="product_category" id="product_category" class="form-control" required>
                    <?php
                    $select_category = "SELECT * FROM `categories`";
                    $result_category = mysqli_query($conn, $select_category);
                    while($category_row = mysqli_fetch_assoc($result_category)) {
                        $cat_id = (int)$category_row['category_id'];
                        $cat_title = htmlspecialchars($category_row['category_title']);
                        $selected = ($cat_id === $category_id) ? 'selected' : '';
                        echo "<option value='$cat_id' $selected>$cat_title</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="product_brand">Select Brand</label>
                <select name="product_brand" id="product_brand" class="form-control" required>
                    <?php
                    $select_brand = "SELECT * FROM `brands`";
                    $result_brand = mysqli_query($conn, $select_brand);
                    while($brand_row = mysqli_fetch_assoc($result_brand)) {
                        $b_id = (int)$brand_row['brand_id'];
                        $b_title = htmlspecialchars($brand_row['brand_title']);
                        $selected = ($b_id === $brand_id) ? 'selected' : '';
                        echo "<option value='$b_id' $selected>$b_title</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mb-4">
                <label>Current Images:</label>
                <div class="d-flex gap-2">
                    <img src="product_images/<?php echo $product_image1; ?>" alt="Image 1" class="img-fluid" style="width: 100px;">
                    <img src="product_images/<?php echo $product_image2; ?>" alt="Image 2" class="img-fluid" style="width: 100px;">
                    <img src="product_images/<?php echo $product_image3; ?>" alt="Image 3" class="img-fluid" style="width: 100px;">
                </div>
            </div>

            <div class="form-group mb-4">
                <label>Update Images:</label>
                <div class="mb-2">
                    <label for="product_image1" class="form-label">Image 1</label>
                    <input type="file" name="product_image1" id="product_image1" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="product_image2" class="form-label">Image 2</label>
                    <input type="file" name="product_image2" id="product_image2" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="product_image3" class="form-label">Image 3</label>
                    <input type="file" name="product_image3" id="product_image3" class="form-control">
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="product_price">Product Price</label>
                <input type="number" name="product_price" id="product_price" class="form-control" value="<?php echo $product_price; ?>" step="0.01" min="0" required>
            </div>

            <div class="form-group mb-4">
                <input type="submit" name="edit_product" value="Update Product" class="btn btn-success">
                <a href="index.php?view_products" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

<?php
}

if(isset($_POST['edit_product'])) {
    $product_title = mysqli_real_escape_string($conn, $_POST['product_title']);
    $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $product_keywords = mysqli_real_escape_string($conn, $_POST['product_keywords']);
    $product_category = (int)$_POST['product_category'];
    $product_brand = (int)$_POST['product_brand'];
    $product_price = (float)$_POST['product_price'];
    $edit_id = (int)$_GET['edit_products'];

    // Initialize update query
    $update_query = "UPDATE `products` SET 
        product_title=?, 
        product_description=?,
        product_keywords=?,
        category_id=?,
        brand_id=?,
        product_price=?";
    
    $params = [$product_title, $product_description, $product_keywords, 
               $product_category, $product_brand, $product_price];
    $types = "sssiid"; // string, string, string, int, int, double

    // Handle image uploads
    $image_fields = ['product_image1', 'product_image2', 'product_image3'];
    foreach($image_fields as $field) {
        if(!empty($_FILES[$field]['name'])) {
            $image = $_FILES[$field]['name'];
            $tmp_image = $_FILES[$field]['tmp_name'];
            
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if(!in_array($_FILES[$field]['type'], $allowed_types)) {
                echo "<script>alert('Invalid file type for $field. Only JPG, PNG and GIF allowed.')</script>";
                continue;
            }
            
            // Validate file size (5MB max)
            if($_FILES[$field]['size'] > 5 * 1024 * 1024) {
                echo "<script>alert('File size too large for $field. Maximum size is 5MB.')</script>";
                continue;
            }

            // Move file and update query
            move_uploaded_file($tmp_image, "product_images/$image");
            $update_query .= ", $field=?";
            $params[] = $image;
            $types .= "s";
        }
    }

    $update_query .= " WHERE product_id=?";
    $params[] = $edit_id;
    $types .= "i";

    // Prepare and execute update
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($result) {
        echo "<script>alert('Product updated successfully')</script>";
        echo "<script>window.location.href='index.php?view_products'</script>";
    } else {
        echo "<script>alert('Error updating product')</script>";
    }
}
?>
