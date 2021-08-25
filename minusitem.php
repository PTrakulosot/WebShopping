<?php
session_start();
include("config.php");
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
$detail_id = $_GET['detailid'];

$sql = "SELECT Qty FROM cartdetails WHERE CartDetailID = $detail_id";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$old_qty = $row['Qty'];
$qty = 1;
if($old_qty > 1) {
    $sql = "UPDATE cartdetails SET Qty = $old_qty - $qty WHERE CartDetailID = $detail_id";
    mysqli_query($link, $sql);
    header("location: cart.php");
} else {
    header("location: cart.php");
}
mysqli_close($link);
?>