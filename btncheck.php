<?php
session_start();
require_once "config.php";
$id = $_GET['id'];
$sql = "SELECT * FROM orders WHERE OrderID = '$id'";
if ($result = mysqli_query($link, $sql)) {
    $row = mysqli_fetch_array($result);
    if ($row['ShipperID'] != NULL || $row['ShipperID'] != null) {
        header("location: check.php?txt=ยืนยันการชำระค่าสินค้าเรียบร้อย");
    } else {
        $sql = "SELECT * FROM repairs WHERE RepairID = '$id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        if ($row['Status'] == "Success") {
            header("location: check.php?txt=การซ่อมแซมเสร็จสิ้น");
        } else if($row['Status'] == "Repair"){
            header("location: check.php?txt=อยู่ระหว่างการซ่อมแซม");
        } else {
            header("location: check.php?txt=รอยืนยันการชำระค่าสินค้า");
        }
        
    }
} else {
    $sql = "SELECT * FROM repairs WHERE RepairID = '$id'";

    $row = mysqli_fetch_array($result);
    if ($row['Status'] == "Success") {
        header("location: check.php?txt=การซ่อมแซมเสร็จสิ้น");
    } else {
        header("location: check.php?txt=อยู่ระหว่างการซ่อมแซม");
    }
}
