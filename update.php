<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
include("config.php");
$sql = "SELECT * FROM shippers";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$shipper_id = $row['ShipperID']+1;
$order_id = $_GET['orderid'];
$status = $_GET['status'];
$tax = "FSHTH";
$shippertax = $tax . $shipper_id;

if($status == "receive") {
    $sql = "UPDATE orders SET ShipperID = 0 WHERE OrderID=$order_id";
    mysqli_query($link, $sql);
    header("location: home_admin.php");
    exit;
} else {
    $sql = "UPDATE orders SET ShipperID = $shipper_id  WHERE OrderID=$order_id";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO shippers (ShipperTax) VALUES ('$shippertax')";
    mysqli_query($link, $sql);
    header("location: home_admin.php");
    exit;
}
?>