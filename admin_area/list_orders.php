<h2 class="text-center mb-4">All Orders</h2>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Due Amount</th>
                <th>Invoice Number</th>
                <th>Total Products</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if user_orders table exists
            $check_table = "SHOW TABLES LIKE 'user_orders'";
            $table_result = mysqli_query($conn, $check_table);
            
            if(mysqli_num_rows($table_result) == 0) {
                echo "<tr><td colspan='7' class='text-center'>Orders table not found</td></tr>";
            } else {
                $get_orders = "SELECT * FROM user_orders ORDER BY order_id DESC";
                $result = mysqli_query($conn, $get_orders);
                
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $order_id = (int)$row['order_id'];
                        $amount = isset($row['amount_due']) ? number_format((float)$row['amount_due'], 2) : '0.00';
                        $invoice_number = htmlspecialchars($row['invoice_number']);
                        $total_products = isset($row['total_products']) ? (int)$row['total_products'] : 0;
                        $order_date = isset($row['order_date']) ? date('d M Y', strtotime($row['order_date'])) : 'N/A';
                        $order_status = htmlspecialchars($row['order_status']);
                        ?>
                        <tr>
                            <td><?php echo $order_id; ?></td>
                            <td>$<?php echo $amount; ?></td>
                            <td><?php echo $invoice_number; ?></td>
                            <td><?php echo $total_products; ?></td>
                            <td><?php echo $order_date; ?></td>
                            <td><?php echo $order_status; ?></td>
                            <td>
                                <a href="index.php?delete_order=<?php echo $order_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No orders found</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
