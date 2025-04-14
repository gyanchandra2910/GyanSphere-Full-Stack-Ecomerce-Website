<h2 class="text-center mb-4">All Users</h2>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Image</th>
                <th>Address</th>
                <th>Mobile</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $get_users = "SELECT * FROM user_table ORDER BY user_id";
            $result = mysqli_query($conn, $get_users);
            
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $user_id = (int)$row['user_id'];
                    $username = htmlspecialchars($row['username']);
                    $user_email = htmlspecialchars($row['user_email']);
                    $user_image = htmlspecialchars($row['user_image']);
                    $user_address = htmlspecialchars($row['user_address']);
                    $user_mobile = htmlspecialchars($row['user_mobile']);
                    ?>
                    <tr>
                        <td><?php echo $user_id; ?></td>
                        <td><?php echo $username; ?></td>
                        <td><?php echo $user_email; ?></td>
                        <td><img src="../users_area/user_images/<?php echo $user_image; ?>" alt="User Image" width="50"></td>
                        <td><?php echo $user_address; ?></td>
                        <td><?php echo $user_mobile; ?></td>
                        <td>
                            <a href="index.php?edit_user=<?php echo $user_id; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="index.php?delete_user=<?php echo $user_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
