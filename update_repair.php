<?php
session_start();
include("config.php");
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
$repair_id = $_GET['repairid'];
$sql = "SELECT * FROM repairs WHERE RepairID = '$repair_id'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $id = $_POST["id"];
    // $repair_name = $_POST["repairname"];
    // $customer_name = $_POST["name"];
    // $phone = $_POST["phone"];
    // $msg = $_POST["msg"];
    // $status = $_POST["status"];
    // $sql = "UPDATE repairs SET RepairName = '$repair_name', CustomerName = '$customer_name', Phone = '$phone', Msg = '$msg', Status = '$status' WHERE RepairID=$repairid";
    $sql = "UPDATE repairs SET RepairName='" . $_POST['repairname'] . "', CustomerName='" . $_POST['name'] . "', Phone='" . $_POST['phone'] . "', Msg='" . $_POST['msg'] . "', Status='" . $_POST['status'] . "' WHERE RepairID='" . $_POST['id'] . "'";
    mysqli_query($link, $sql);
    mysqli_close($link);
    header("location: repair_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "header.php" ?>
</head>

<body>
    <center>
        <h1 style="margin-top: 5%;">UPDATE</h1><br>
        <div class="separator"></div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype='multipart/form-data'>
            RepairID : <input type='id' name='id' value="<?php echo $row['RepairID']; ?>" readonly><br><br>
            RepairName : <input type='item' name='repairname' value="<?php echo $row['RepairName']; ?>"><br><br>
            CustomerName : <input type='name' name='name' value="<?php echo $row['CustomerName']; ?>"><br><br>
            Phone : <input type='phone' name='phone' value="<?php echo $row['Phone']; ?>"><br><br>
            Message <br><textarea name='msg' rows='5' cols='40'><?php echo $row['Msg']; ?></textarea><br><br>
            Status :
            <input type="radio" name="status" <?php if (isset($row['Status']) && $row['Status'] == "Repair") echo "checked"; ?> value="Repair">
            ซ่อมบำรุง
            <input type="radio" name="status" <?php if (isset($row['Status']) && $row['Status'] == "Success") echo "checked"; ?> value="Success">
            เสร็จสิ้น
            <br>
            <br>
            <input type='submit' value='SUBMIT'>
        </form>
        <br>
        <a href="repair_admin.php">Back to Admin Page</a>
    </center>
</body>

</html>