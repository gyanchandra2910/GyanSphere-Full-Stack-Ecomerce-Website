<h2 class="text-center mb-4">All Brands</h2>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Brand Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $get_brands = "SELECT * FROM brands ORDER BY brand_id";
            $result = mysqli_query($conn, $get_brands);
            
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $brand_id = (int)$row['brand_id'];
                    $brand_title = htmlspecialchars($row['brand_title']);
                    ?>
                    <tr>
                        <td><?php echo $brand_id; ?></td>
                        <td><?php echo $brand_title; ?></td>
                        <td>
                            <a href="index.php?edit_brand=<?php echo $brand_id; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="index.php?delete_brand=<?php echo $brand_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this brand?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>No brands found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
