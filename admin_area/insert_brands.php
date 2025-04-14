<?php
include('../includes/connect.php');
if(isset($_POST['insert_brand'])){
    $brand_title = mysqli_real_escape_string($conn, $_POST['brand_title']); // Escape special characters

    // Convert to lowercase for case-insensitive comparison
    $select_query = "SELECT * FROM brands WHERE LOWER(brand_title) = LOWER('$brand_title')";
    $result_select = mysqli_query($conn, $select_query);
    $number = mysqli_num_rows($result_select);
    if($number > 0){
        echo "<script>alert('This brand is already present in the database')</script>";
    }
    else{
        $insert_query = "INSERT INTO brands (brand_title) VALUES ('$brand_title')";
        $result = mysqli_query($conn, $insert_query);
        if($result){
            echo "<script>alert('Brand has been inserted successfully')</script>";
        } else {
            echo "<script>alert('Error inserting brand: " . mysqli_error($conn) . "')</script>";
        }
    }
}
?> 

<h2 class="text-center">Insert Brands</h2>
<form action="" method="post" class="mb-2">
    <div class="input-group w-90 mb-2">
        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span>
        <input type="text" class="form-control" placeholder="Insert Brand Name" aria-label="Brand Name" aria-describedby="basic-addon1" required="required" name="brand_title" maxlength="50">
    </div>

    <div class="input-group w-10 mb-2">
        <input type="submit" class="btn btn-success border-0 p-2 my-3" name="insert_brand" value="Insert Brand">
    </div>
</form>
