<?php
$login_alert = "#myModal1";
$sub_login_alert = "modal";
session_start();
include("config.php");
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
$cart_id = $_SESSION['cartid'];
$status = $_GET['status'];
$sql = "SELECT COUNT(CartDetailID) AS items FROM cartdetails WHERE CartID = $cart_id ";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

if($row['items'] == 0 || $row['items'] == null){
    header("location: home.php");
    exit;
}
$cart_id = $_SESSION["cartid"];
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "header.php" ?>
    <link href="styles.css" rel="stylesheet" media="all">
    <style>
        table.blueTableTop tr {
            border: 1px solid #0064ce;
            background-color: #0064ce;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            color: white;
            padding: 10px;
            font-size: 20px;
            width: 98%;
            margin: 10px;
        }

        table.blueTableTop td {
            width: 95%;
        }

        table.whiteTable tr {
            border: 1px solid #d5d5d5;
            background-color: #fafcff;
            color: black;
            padding: 10px;
            font-size: 20px;
            width: 98%;
            margin: 10px;
        }

        table.whiteTable td {
            width: 95%;
        }

        table.blueTableBot tr {
            border: 1px solid #0064ce;
            background-color: #0064ce;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
            color: white;
            padding: 10px;
            font-size: 22px;
            width: 98%;
            margin: 10px;
        }

        table.blueTableBot td {
            width: 95%;
        }

        div.wrapper {
            width: 1000px;
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
                                $sql = "SELECT COUNT(CartDetailID) AS items FROM cartdetails WHERE CartID = ".$_SESSION["cartid"]."";
                                // SELECT COUNT(CartDetailID) AS items FROM `cartdetails` WHERE CartID = 1
                                $result = mysqli_query($link, $sql);
                                $row = mysqli_fetch_array($result)
                                ?>
                                <a class="dropdown-item" href="cart.php?status=send" style="display: inline-flex;">
                                    รถเข็นของฉัน<div style="border: 5px solid tomato;border-radius: 50px;background-color: tomato;color: white;width: 24px;height: 24px;text-align: center;font-size: 12px;margin-left: 5px;">
                                    <?php echo $row['items']?>
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
        <div class="wrapper">
            <table class="blueTableTop" style="width: 100%;">
                <tbody>
                    <tr style="display: inline-flex;">
                        <td>สินค้า</td>
                        <td style="text-align: center;margin-left: 180px;">จำนวน</td>
                        <td style="text-align: right;">ราคา / หน่วย</td>
                    </tr>
                </tbody>
            </table>
            <?php
            $subtotal = 0;
            $sumdis = 0;
            $sql = "SELECT images.Image, CartDetailID, cartdetails.ProductID, products.ProductName, Qty, products.Price, products.Discount FROM cartdetails 
                INNER JOIN products ON cartdetails.ProductID = products.ProductID
                INNER JOIN images ON products.ImageID = images.ImageID
                WHERE CartID = $cart_id";
            $result = mysqli_query($link, $sql);
            while ($row = mysqli_fetch_array($result)) {
                $detail_id = $row['CartDetailID'];
                $price = $row['Price'];
                $qty = $row['Qty'];
                $discount = $row['Discount'];
                $dis = (($discount / 100) * $price) * $qty;
                $sumdis = $sumdis + $dis;
                $sumprice = $price * $qty;
                $subtotal = $subtotal + $sumprice;
                $total = $subtotal - $sumdis;
            ?>
                <table class="whiteTable" style="width: 100%;">
                    <tbody>
                        <tr style="display: inline-flex;">
                            <td style="display: inline-flex;">
                                <a href="deleteitem.php?detailid=<?php echo $detail_id ?>">
                                    <i class="fas fa-trash-alt" style="color: red;"></i>
                                </a>
                                <!-- <img src="product.png"> -->
                                <img style="border-radius: 100px; border: 0.5px solid black; margin: 10px;" src="<?php echo $row["Image"]; ?>">
                                <h4 style="margin-top: 60px;"><?php echo $row["ProductName"]; ?></h4>
                            </td>
                            <td style="text-align: right; align-items: flex-end;">
                                <a href="minusitem.php?detailid=<?php echo $detail_id ?>">
                                    <i class="btn fas fa-minus-circle"></i>
                                </a>
                                <input type="text" name="qty" style="width:15%;text-align:center;border:1px solid #DEDEDF;margin-top: 60px;" value="<?php echo $row["Qty"]; ?>" disabled>
                                <a href="plusitem.php?detailid=<?php echo $detail_id ?>">
                                    <i class="btn fas fa-plus-circle"></i>
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <h4 style="margin-top: 60px;"><?php echo $row["Price"]; ?>&nbsp;&nbsp;&nbsp;บาท</h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php
            }
            ?>

            <table class="blueTableBot" style="width: 100%;">
                <tbody>
                    <tr style="display: inline-flex;">
                        <!-- <td></td>
                        <td style="text-align: right;"></td> -->
                        <td style="text-align: right;">
                            รวมราคาสินค้า<label style="margin-left: 20px;"><?php echo $subtotal ?></label>&nbsp;&nbsp;&nbsp;บาท<br>
                            ราคาส่วนลด <label style="margin-left: 20px;"><?php echo $sumdis ?></label>&nbsp;&nbsp;&nbsp;บาท<br>
                            -----------------------------------------------------------------------------------------------------------------<br>
                            ยอดรวมราคา<label style="margin-left: 20px;"><?php echo $total ?></label>&nbsp;&nbsp;&nbsp;บาท
                        </td>
                    </tr>
                </tbody>
            </table>
            <form method="get">
            <label>เลือกช่องทางรับสินค้า:</label>
            <select name="status" id="status">
                <?php
                if($_GET['status'] == 'receive')
                {
                ?>
                    <option value="send">จัดส่งสินค้า</option>
                    <option value="receive" selected>รับสินค้าด้วยตนเอง</option>
                <?php
                } else if($_GET['status'] == 'send') {
                ?>
                    <option value="send" selected>จัดส่งสินค้า</option>
                    <option value="receive">รับสินค้าด้วยตนเอง</option>
                <?php
                } else {
                ?>
                    <option value="send" selected>จัดส่งสินค้า</option>
                    <option value="receive">รับสินค้าด้วยตนเอง</option>
                <?php
                }
                ?>
            </select>
            <input type="submit" value="ยืนยัน">
            </form>
            <a href="order.php?subtotal=<?php echo $subtotal ?>&sumdis=<?php echo $sumdis ?>&total=<?php echo $total ?>&status=<?php echo $status ?>" class="btn btn-success btn-lg btn-block">
                ยืนยันสั่งซื้อสินค้า
            </a>
        </div>
    </center>

    <!-- The Modal Login -->
    <div class="modal fade" id="myModal1">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-dark">
                    <h5 class="modal-title" id="exampleModalLabel">เข้าสู่ระบบ / สมัครสมาชิก</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body" style="font-size: 18px; font-weight: bolder">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div style="margin-top: 1.5pc;" class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $username; ?>" required="">
                            <span style="color: red" class="help-block"><?php echo $username_err; ?></span>
                        </div>
                        <div style="margin-top: 1.5pc;" class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <input type="password" name="password" class="form-control" placeholder="Password" value="" required="">
                            <span style="color: red" class="help-block"><?php echo $password_err; ?></span>
                        </div>

                        <input type="submit" class="btn btn-success aaa shadow w-100" value="เข้าสู่ระบบ">
                        <a href="register.php"><input type="button" class="btn btn-secondary aaa shadow w-100" value="สมัครสมาชิก"></a>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- The Modal Login -->
</body>

</html>