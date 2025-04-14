<h2 class="text-center mb-4">All Payments</h2>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Invoice Number</th>
                <th>Amount</th>
                <th>Payment Mode</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if user_payments table exists
            $check_table = "SHOW TABLES LIKE 'user_payments'";
            $table_result = mysqli_query($conn, $check_table);
            
            if(mysqli_num_rows($table_result) == 0) {
                echo "<tr><td colspan='7' class='text-center'>Payments table not found</td></tr>";
            } else {
                $get_payments = "SELECT * FROM user_payments ORDER BY payment_id DESC";
                $result = mysqli_query($conn, $get_payments);
                
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $payment_id = (int)$row['payment_id'];
                        $order_id = (int)$row['order_id'];
                        $invoice_number = htmlspecialchars($row['invoice_number']);
                        $amount = isset($row['amount']) ? number_format((float)$row['amount'], 2) : '0.00';
                        $payment_mode = htmlspecialchars($row['payment_mode']);
                        $date = isset($row['date']) ? date('d M Y', strtotime($row['date'])) : 'N/A';
                        ?>
                        <tr>
                            <td><?php echo $payment_id; ?></td>
                            <td><?php echo $order_id; ?></td>
                            <td><?php echo $invoice_number; ?></td>
                            <td>$<?php echo $amount; ?></td>
                            <td><?php echo $payment_mode; ?></td>
                            <td><?php echo $date; ?></td>
                            <td>
                                <a href="index.php?delete_payment=<?php echo $payment_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment?');">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No payments found</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
