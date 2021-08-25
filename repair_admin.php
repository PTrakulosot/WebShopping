<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $repair_id = $_POST["repairid"];
    $repair_name = $_POST["repairname"];
    $customer_name = $_POST["customername"];
    $phone = $_POST["phone"];
    $msg = $_POST["msg"];
    $sql = "INSERT INTO repairs (RepairID, RepairName, CustomerName, Phone, Msg) VALUES ('$repair_id', '$repair_name', '$customer_name', '$phone', '$msg')";
    mysqli_query($link, $sql);
    header("location: repair_admin.php");
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
            <li class="nav-item">
                <a href="home_admin.php" class="nav-link text-dark font-italic">
                    <i class="fa fa-th-large mr-3 text-primary fa-fw"></i>
                    Home
                </a>
            </li>
            <li class="nav-item active">
                <a href="repair_admin.php" class="nav-link text-dark font-italic bg-light">
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
        <h1>Repairs Record</h1>
        <div class="separator"></div>
        <div class="row">
            <div class="col-lg-4">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="card" style="background-color: lightgray;">
                        <center>
                            <br>
                            <h3>INSERT REPAIRS</h3>
                            <br><input type='id' name='repairid' value="" placeholder="RepairID" style="border-radius: 8px; border: 1px solid black;"><br>
                            <br><input type='item' name='repairname' value="" placeholder="RepairName" style="border-radius: 8px; border: 1px solid black;"><br>
                            <br><input type='name' name='customername' value="" placeholder="CustomerName" style="border-radius: 8px; border: 1px solid black;"><br>
                            <br><input type='phone' id="phone" name='phone' value="" placeholder="Phone" maxlength="12" style="border-radius: 8px; border: 1px solid black;"><br>
                            <br><textarea type="text" name="msg" rows="2" cols="25" placeholder="Msg" style="border-radius: 8px; border: 1px solid black;"></textarea><br><br>
                        </center>
                    </div>
                    <center>
                        <br>
                        <button type="button" class="btn btn-light bg-white rounded-pill shadow-sm px-4 mb-4 w-100"><input class="btn" type="submit" value="ยืนยัน"><small class="text-uppercase font-weight-bold"></small></button>
                        <br>
                    </center>
                </form>
            </div>
            <div class="col-lg-8">
                <table class="unstyledTable">
                    <thead>
                        <tr>
                            <th>RepairID</th>
                            <th>RepairName</th>
                            <th>CustomerName</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM repairs";
                        $result = mysqli_query($link, $sql);
                        while ($row = mysqli_fetch_array($result)) {

                        ?>
                            <tr>
                                <td style="font-size: 18px;"><?php echo $row['RepairID']; ?></td>
                                <td style="font-size: 18px;"><?php echo $row['RepairName']; ?></td>
                                <td style="font-size: 18px;"><?php echo $row['CustomerName']; ?></td>
                                <td style="font-size: 18px;"><?php echo $row['Phone']; ?></td>
                                <td style="font-size: 18px;"><?php echo $row['Status']; ?></td>
                                <td style="font-size: 18px;"><?php echo $row['CheckIN']; ?></td>
                                <td>
                                    <a href="update_repair.php?repairid=<?php echo $row['RepairID'] ?>">
                                        <i class="fas fa-edit text-primary"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script>
        // var ViewImg = <?php echo json_encode("ไม่มีไฟล์การชำระเงินของเลขออร์เดอร์"); ?>;
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
</body>

<footer>

</footer>

</html>