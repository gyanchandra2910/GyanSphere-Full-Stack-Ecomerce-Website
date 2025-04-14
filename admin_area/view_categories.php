<h2 class="text-center mb-4">All Categories</h2>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Category Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $get_categories = "SELECT * FROM categories ORDER BY category_id";
            $result = mysqli_query($conn, $get_categories);
            
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $category_id = (int)$row['category_id'];
                    $category_title = htmlspecialchars($row['category_title']);
                    ?>
                    <tr>
                        <td><?php echo $category_id; ?></td>
                        <td><?php echo $category_title; ?></td>
                        <td>
                            <a href="index.php?edit_category=<?php echo $category_id; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="index.php?delete_category=<?php echo $category_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>No categories found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
