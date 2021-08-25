<?php
session_start();
include("config.php");
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
$customer_id = $_SESSION['username'];
$orderid = $_POST['orderid'];
$sql = "SELECT * FROM paymentimages ORDER BY PaymentID DESC LIMIT 1";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$image_id = $row["PaymentID"] + 1;
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_FILES['file']['name'];
    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // Check extension
    if (in_array($imageFileType, $extensions_arr)) {

        // Convert to base64 
        $image_base64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']));
        $image = 'data:image/' . $imageFileType . ';base64,' . $image_base64;
        // Insert record
        $query = "INSERT INTO paymentimages (Image, CustomerID) VALUES('" . $image . "', '" . $customer_id . "' )";
        mysqli_query($link, $query);

        // Upload file
        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $name);

        // Update orders
        $sql = "UPDATE orders SET PaymentID = '" . $image_id . "' WHERE OrderID = '" . $orderid . "'";
        mysqli_query($link, $sql);
    }

    mysqli_close($link);

    header("location: payment.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "header.php" ?>
    <link href="styles.css" rel="stylesheet" media="all">
    <style>
        table.unstyledTable {
            border: 1px solid #000000;
            width: 100%;
            height: 150px;
            text-align: center;
        }

        table.unstyledTable td,
        table.unstyledTable th {
            border: 1px solid #AAAAAA;
        }

        table.unstyledTable thead {
            background: #DDDDDD;
        }

        table.unstyledTable thead th {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        div.wrapper {
            width: 1500px;
        }
    </style>

</head>

<body style="background-color: white;">
    <!-- NAVBAR -->
    <nav class="navbar sticky-top navbar-expand-sm navbar-light" style="background-color: #e3f2fd;">
        <a class="navbar-brand" href="home.php">
            <img src="logo.png" style="width: 125px;height: 110px;"></img>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <center>
                <ul class="navbar-nav" style="margin-left: 10px;">
                    <li class="nav-item" style="margin-left: 15px;">
                        <a class="nav-link" href="home.php">หน้าหลัก</a>
                    </li>
                    <li class="nav-item dropdown" style="margin-left: 15px;">
                        <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            หมวดหมู่สินค้า
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="product-all.php">สินค้าทั้งหมด</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="aircon.php">เครื่องปรับอากาศ</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="televis.php">ทีวีและเครื่องเสียง</a>
                        </div>
                    </li>
                    <li class="nav-item" style="margin-left: 15px;">
                        <a class="nav-link" href="check.php">ตรวจสถานะ</a>
                    </li>
                    <?php
                    if ($_SESSION["loggedin"] === true) { ?>
                        <li class="nav-item dropdown active" style="margin-left: 15px;">
                            <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $_SESSION["username"]; ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <!-- <a class="dropdown-item" href="#">ข้อมูลส่วนตัว</a>
                                <div class="dropdown-divider"></div> -->
                                <?php
                                $sql = "SELECT COUNT(CartDetailID) AS items FROM cartdetails WHERE CartID = " . $_SESSION["cartid"] . "";
                                // SELECT COUNT(CartDetailID) AS items FROM `cartdetails` WHERE CartID = 1
                                $result = mysqli_query($link, $sql);
                                $row = mysqli_fetch_array($result)
                                ?>
                                <a class="dropdown-item" href="cart.php?status=send" style="display: inline-flex;">
                                    รถเข็นของฉัน<div style="border: 5px solid tomato;border-radius: 50px;background-color: tomato;color: white;width: 24px;height: 24px;text-align: center;font-size: 12px;margin-left: 5px;">
                                        <?php echo $row['items'] ?>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="payment.php">ชำระออร์เดอร์สินค้า</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" style="color:tomato;">ออกจากระบบ</a>
                            </div>

                        </li>
                    <?php
                    } else { ?>
                        <li class="nav-item" style="margin-left: 15px;">
                            <a class="nav-link" href="#" data-toggle="<?php echo $sub_login_alert ?>" data-target="<?php echo $login_alert ?>">เข้าสู่ระบบ/สมัครสมาชิก</a>
                        </li>
                    <?php
                    } ?>

                </ul>
            </center>
        </div>
    </nav>
    <!-- ----- -->
    <center>
        <br><br>
        <h2>ช่องทางการชำระเงิน - ไทยพาณิชย์ (SCB) เลขที่บัญชี : 406552xxxx</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype='multipart/form-data' style="margin-top: 12%;">
            <label>เลือกออร์เดอร์ที่ต้องการชำระ:</label>
            <select name="orderid" id="orderid" style="width: 15%;">
                <?php
                $sql = "SELECT * FROM orders WHERE CustomerID = '" . $customer_id . "' AND PaymentID = 0";
                $result = mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($result)) {
                ?>
                    <option value="<?php echo $row["OrderID"]; ?>"><?php echo $row["OrderID"]; ?></option>
                <?php
                }
                ?>
            </select>
            <br><br><input type='file' name='file'>
            <input type='submit' value='SUBMIT'>
        </form>

    </center>

</body>

<footer style="margin-top: 20%;">
    <div clss="container" style="background-color: #031421; text-align: center;">
        <i class="fab fa-facebook-square" style="color: white; margin: 10px;"></i>
        <i class="fab fa-line" style="color: white; margin: 10px;"></i>
        <i class="fas fa-envelope-square" style="color: white; margin: 10px;"></i>
        <br>
        <a style="color: white; font-size: 16px;"> Create By : Phornchai Trakul-o-sot</a> <br>
        <a style="color: gray; font-size: 14px;"> © 2021 Electronic Fellows Co., Ltd.</a>
    </div>
</footer>

</html>