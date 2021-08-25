<?php
session_start();
include("config.php");
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
$detail_id = $_GET['detailid'];

$sql = "DELETE FROM cartdetails WHERE CartDetailID = $detail_id";
mysqli_query($link, $sql);
mysqli_close($link);
header("location: cart.php");
?>