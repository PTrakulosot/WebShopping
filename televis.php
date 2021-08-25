<?php
$login_alert = "#myModal1";
$sub_login_alert = "modal";
$text_addcart_success = "เพิ่มสินค้าลงรถเข็นเรียบร้อย";
$text_addcart_wrong = "กรุณาเข้าสู่ระบบก่อนสั่งซื้อสินค้า";
$category_id = 2;
$title = "ทีวีและเครื่องเสียง";
session_start();
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT CustomerID, CustomerPW, CartID FROM customers WHERE CustomerID = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password, $cartid);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;
                            $_SESSION["cartid"] = $cartid;


                            // Redirect user to main page
                            header("location: home.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with this username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "header.php" ?>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
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
                    <li class="nav-item dropdown active" style="margin-left: 15px;">
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
                        <li class="nav-item dropdown" style="margin-left: 15px;">
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
    <!-- CONTENTS -->
    <div clss="container-fluid position-fixed" style="background-color: whitesmoke;">
        <br>
        <div class="container-fluid border border-dark" style="text-align: center; padding: 10px;">
            <h3><?php echo $title ?></h3>
        </div>
        <br>
        <br>
        <div class="container">
            <div class="row">
                <?php
                $sql = "SELECT * FROM products 
                INNER JOIN images ON products.ImageID = images.ImageID
                WHERE CategoryID = $category_id";
                $result = mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    $product_id = $row["ProductID"];
                    $qty = 1;
                    $discount = $row["Discount"];
                    $rl_price =  $row["Price"];
                    $dis = (($discount / 100) * $rl_price);
                    $sum = $rl_price - $dis;
                ?>
                    <div class="col-sm d-flex justify-content-center">
                        <div class="card" style="border-radius: 20px; margin: 10px; padding: 10px; width: 18rem;">
                            <div class="card-header" style="border-radius: 10px; background-color: white; border: 0.5px solid black;">
                                <!-- <img class="card-img-top" src="product.png"> -->
                                <img class="card-img-top" src="<?php echo $row["Image"]; ?>">
                            </div>
                            <?php
                            if ($discount != 0) {
                            ?>
                                <div class="discount"><?php echo $row["Discount"]; ?>%</div>
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row["ProductName"]; ?></h5>
                                <p class="card-text"><?php echo $row["ProductDetail"]; ?></p>
                                <br>
                                <br>

                                <?php if ($dis === 0) {
                                ?>
                                    <div class="price"><?php echo $row["Price"]; ?> บาท</div><br>
                                <?php } else { ?>
                                    <div class="ori-price">
                                        <label style="text-decoration: line-through;"><?php echo $row["Price"]; ?> บาท</label>
                                    </div>
                                    <div class="dis-price"><?php echo $sum; ?> บาท</div><br>
                                <?php } ?>
                                <?php if ($_SESSION["loggedin"] != true) { ?>
                                    <a class="btn btn-primary btn-lg btn-block" onclick="alert(AddCartWrong)">
                                        <i class="fas fa-shopping-cart" style="color: white;"></i>
                                    </a>
                                <?php } else { ?>
                                    <a class="btn btn-primary btn-lg btn-block" href="addcart.php?productid=<?php echo $product_id ?>&qty=<?php echo $qty ?>" onclick="alert(AddCartSuccess)">
                                        <i class="fas fa-shopping-cart" style="color: white;"></i>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <!-- CONTENTS -->

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

        <script>
            var AddCartSuccess = <?php echo json_encode($text_addcart_success); ?>;
            var AddCartWrong = <?php echo json_encode($text_addcart_wrong); ?>;
        </script>
</body>
<footer>
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