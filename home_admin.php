<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "header.php" ?>
    <link rel="stylesheet" href="admin.css">
</head>

<body style="height: 100%;">
    <!-- Vertical navbar -->
    <div class="vertical-nav bg-white" id="sidebar">
        <p class="text-gray font-weight-bold text-uppercase px-3 small pb-4 mb-0">Main</p>

        <ul class="nav flex-column bg-white mb-0">
            <li class="nav-item active">
                <a href="home_admin.php" class="nav-link text-dark font-italic bg-light">
                    <i class="fa fa-th-large mr-3 text-primary fa-fw"></i>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a href="repair_admin.php" class="nav-link text-dark font-italic">
                    <i class="fa fa-tools mr-3 text-primary fa-fw"></i>
                    Repair
                </a>
            </li>
            <li class="nav-item">
                <a href="uploadnew.php" class="nav-link text-dark font-italic">
                    <i class="fa fa-cubes mr-3 text-primary fa-fw"></i>
                    Upload
                </a>
            </li>
            <li class="nav-item">
                <a href="logout.php" class="nav-link text-dark font-italic">
                    <i class="fa fa-sign-out-alt mr-3 text-danger fa-fw"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End vertical navbar -->

    <div class="page-content p-5" id="content">
        <h1>Orders Record</h1>
    <div class="separator"></div>
        <table class="unstyledTable">
            <thead>
                <tr>
                    <th>OrderID</th>
                    <th>CustomerID</th>
                    <th>OrderDate</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM orders INNER JOIN paymentimages ON orders.PaymentID = paymentimages.PaymentID";
                $result = mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    $payment_id = $row['PaymentID'];
                    $shipper_id = $row['ShipperID'];
                ?>
                    <tr>
                        <td><?php echo $row['OrderID']; ?></td>
                        <td><?php echo $row['CustomerID']; ?></td>
                        <td><?php echo $row['OrderDate']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td>
                            <?php
                            if ($payment_id == 0) {
                            ?>
                                <a href="" onclick="alert(ViewImg)">
                                    <i class="fas fa-file-image text-primary"></i>
                                </a>
                            <?php
                            } else {
                            ?>
                                <a href="displaypayment.php?paymentid=<?php echo $payment_id ?>">
                                    <i class="fas fa-file-image text-primary"></i>
                                </a>
                            <?php
                            }
                            ?>
                            &nbsp;
                            <?php
                            if ($shipper_id == NULL||$shipper_id == null) {
                            ?>
                                <a href="update.php?orderid=<?php echo $row['OrderID'] ?>&status=<?php echo $row['Status'] ?>" onclick="alert(Confirm)"> 
                                    <i class="far fa-check-circle text-danger"></i>
                                </a>
                            <?php
                            } else {
                            ?>
                                <a href="">
                                    <i class="far fa-check-circle text-success" onclick="alert(ConfirmWrong)"></i>
                                </a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            </tr>
        </table>
    </div>
    <script>
        var ViewImg = <?php echo json_encode("ไม่มีไฟล์การชำระเงินของเลขออร์เดอร์"); ?>;
        var Confirm = <?php echo json_encode("ยืนยันการสั่งซื้อออร์เดอร์สำเร็จ"); ?>;
        var ConfirmWrong = <?php echo json_encode("ไม่สามารถยืนยันออร์เดอร์ซ้ำ"); ?>;
    </script>
</body>

<footer>

</footer>

</html>