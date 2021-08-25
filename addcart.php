<?php
session_start();
include("config.php");
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
$product_id = $_GET['productid'];
$qty = $_GET['qty'];
$cart_id = $_SESSION['cartid'];
$txt = '';

$sql = "SELECT CartDetailID, CartID, ProductID, Qty, COUNT(CartDetailID) AS items FROM cartdetails WHERE CartID = $cart_id AND ProductID = $product_id";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$old_qty = $row['Qty'];
$cartdetail_id = $row['CartDetailID'];
if($row['items'] == 0 || '0') {
    $sql = "INSERT INTO cartdetails (CartID, ProductID, Qty) VALUES ('$cart_id', '$product_id', '$qty')";
    mysqli_query($link, $sql);
    
} else {
    $sql = "UPDATE cartdetails SET Qty = $old_qty+$qty WHERE CartDetailID = $cartdetail_id";
    mysqli_query($link, $sql);
}
mysqli_close($link);

header("location: home.php");
?>

<h1>Test: <?php echo $old_qty+$qty; ?></h1>
<h1>PD_ID: <?php echo $product_id; ?></h1>
<h1>QTY: <?php echo $qty; ?></h1>
<h1>CT_ID: <?php echo $cart_id; ?></h1>
<h1>row[cartid]: <?php echo $row['CartID']; ?></h1>
<h1>row[cartdetail_id]: <?php echo $cartdetail_id; ?></h1>
<h1>row[productid]: <?php echo $row['ProductID']; ?></h1>
<h1>row[qty]: <?php echo $row['Qty']; ?></h1>
<h1>row[items]: <?php echo $row['items']; ?></h1>
