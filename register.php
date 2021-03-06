<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (strlen(trim($_POST["username"])) < 4) {
        $username_err = "Username have at least 4 letters.";
    } elseif (strlen(trim($_POST["username"])) > 16) {
        $username_err = "Username haven't more than 16 letters.";
    } else {
        // Prepare a select statement
        $sql = "SELECT CustomerID FROM customers WHERE CustomerID = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password have at least 6 letters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

        $sql = "SELECT PhoneID FROM phone ORDER BY PhoneID DESC LIMIT 1";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $phone_id = $row['PhoneID'];

        $sql = "SELECT AddressID FROM address ORDER BY AddressID DESC LIMIT 1";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $address_id = $row['AddressID'];

        $sql = "SELECT CartID FROM carts ORDER BY CartID DESC LIMIT 1";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $cart_id = $row['CartID'];

        $fname = $_POST["first_name"];
        $lname = $_POST["last_name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $address_detail = $_POST["address"];
        $district = $_POST["city"];
        $province = $_POST["province"];
        $postal_code = $_POST["postal"];
        // Prepare an insert statement
        $sql = "INSERT INTO customers (CustomerID, CustomerPW, CustomerName, Email, PhoneID, AddressID, CartID) VALUES (?, ?, '$fname $lname' , '$email', '$phone_id'+1, '$address_id'+1, '$cart_id'+1)";


        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $sql = "INSERT INTO phone (FirstPhone) VALUES ('$phone')";
                mysqli_query($link, $sql);
                $sql = "INSERT INTO address (Detail, District, Province, PostalCode) VALUES ('$address_detail', '$district', '$province', '$postal_code')";
                mysqli_query($link, $sql);
                $sql = "INSERT INTO carts (CustomerID) VALUES ('$username')";
                mysqli_query($link, $sql);
                // Redirect to home page
                header("location: home.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include "header.php" ?>
    <script>
        $(function() {
            $('#phone').keydown(function(e) {
                var key = e.charCode || e.keyCode || 0;
                $text = $(this);
                if (key !== 8 && key !== 9) {
                    if ($text.val().length === 3) {
                        $text.val($text.val() + '-');
                    }
                    if ($text.val().length === 7) {
                        $text.val($text.val() + '-');
                    }
                }
                return (key == 8 || key == 9 || key == 46 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
            })

        });
    </script>
    <link href="regis.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper p-t-45 p-b-50" style="background-color: #e3f2fd;">
        <div class="wrapper wrapper--w790">
            <div class="card card-5">
                <div class="card-heading">
                    <h2 class="title">?????????????????????????????????</h2>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="name">Username</div>
                                <div class="value">
                                    <div class="input-group">
                                        <input class="input--style-5 " type="text" name="username" value="<?php echo $username; ?>" required="">
                                        <?php
                                        if (!empty($username_err)) { ?>
                                            <label class="label--long"><span style="color: red" class="help-block"><?php echo $username_err; ?></span></span></label>
                                        <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row m-b-55">
                                <div class="name">Password</div>
                                <div class="value">
                                    <div class="row row-space">
                                        <div class="col-6">
                                            <div class="input-group-desc">
                                                <input class="input--style-5" type="password" name="password" value="" required="">
                                                <?php
                                                if (empty($password_err)) { ?>
                                                    <label class="label--desc">Password</label>
                                                <?php
                                                } else { ?>
                                                    <label class="label--desc"><span style="color: red" class="help-block"><?php echo $password_err; ?></span></label>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group-desc">

                                                <input class="input--style-5" type="password" name="confirm_password" value="" required="">

                                                <?php
                                                if (empty($confirm_password_err)) { ?>
                                                    <label class="label--desc">Confirm Password</label>
                                                <?php
                                                } else { ?>
                                                    <label class="label--desc"><span style="color: red" class="help-block"><?php echo $confirm_password_err; ?></span></label>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row m-b-55">
                                <div class="name">Name</div>
                                <div class="value">
                                    <div class="row row-space">
                                        <div class="col-6">
                                            <div class="input-group-desc">
                                                <input class="input--style-5" type="text" name="first_name" required="">
                                                <label class="label--desc">First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group-desc">
                                                <input class="input--style-5" type="text" name="last_name" required="">
                                                <label class="label--desc">Last Name</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="name">Email</div>
                                <div class="value">
                                    <div class="input-group">
                                        <input class="input--style-5" type="email" name="email" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="name">Phone</div>
                                <div class="value">
                                    <div class="input-group">
                                        <input class="input--style-5" id="phone" type="phone" name="phone" maxlength="12" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="name">Address</div>
                                <div class="value">
                                    <div class="input-group">
                                        <textarea class="input--style-5 w-100" type="address" name="address" rows="2" required="" style="outline: none; border: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row m-b-55">
                                <div class="name"></div>
                                <div class="value">
                                    <div class="row row-space">
                                        <div class="col-4">
                                            <div class="input-group-desc">
                                                <input class="input--style-5" type="text" name="city" required="">
                                                <label class="label--desc">City</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group-desc">
                                                <!-- <div class="input--style-5 w-100"> -->
                                                    <!-- <select name="province" class="w-100" style="background-color: transparent; outline: none; border: none; height: 50px;" required="">
                                                        <option disabled="disabled" selected="selected">- select -</option>
                                                        <option>Subject 1</option>
                                                        <option>Subject 2</option>
                                                        <option>??????????????????</option>
                                                    </select> -->
                                                    <input class="input--style-5" type="text" name="province" required="">
                                                <!-- </div> -->
                                                <label class="label--desc">Province</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group-desc">
                                                <input class="input--style-5" type="text" name="postal" required="">
                                                <label class="label--desc">Postal Code</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-row p-t-20">
                            <label class="label label--block">Are you an existing customer?</label>
                            <div class="p-t-15">
                                <label class="radio-container m-r-55">Yes
                                    <input type="radio" checked="checked" name="exist">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="radio-container">No
                                    <input type="radio" name="exist">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div> -->
                            <div>
                                <center>
                                    <input style="margin-top:20px; margin-bottom:15px;" class="btn btn--radius-2 btn--green" type="submit" value="??????????????????">
                                    <a style="color:dimgray; font-size: 16px;" href="home.php">????????????????????????????????????</a>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>