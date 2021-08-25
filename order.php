<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
include("config.php");
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
if($_GET['total'] == 0 || $_GET['total'] == null){
    header("location: home.php");
    exit;
}
$cart_id = $_SESSION['cartid'];
$customer_id = $_SESSION['username'];
$subtotal = $_GET['subtotal'];
$sumdis = $_GET['sumdis'];
$total = $_GET['total'];
$status = $_GET['status'];

$sql = "SELECT OrderID FROM orders ORDER BY OrderID DESC LIMIT 1";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$order_id = $row['OrderID'];
$order_id = $order_id+1;

$sql = "INSERT INTO orders (CustomerID, Status) VALUES ('$customer_id', '$status')";
mysqli_query($link, $sql);

?>
<center>
<img src="logo.png" style="width: 125px;height: 110px;margin-top: 2%"></img>
<h3>===================================================</h3>
<h4>ORDER ID# 000000000<?php echo $order_id ?></h4>
<h4>ORDER DATE# <?php echo date("Y-m-d H:i:s") ?></h4>
<h4>CUSTOMER ID# <?php echo $customer_id ?></h4>
<h4>STATUS# <?php echo $status ?></h4>
<h3>--------------------------------------------------------------------------------------</h3>
<?php
$sql = "SELECT cartdetails.ProductID, products.ProductName, products.Price, Qty FROM cartdetails
INNER JOIN products ON cartdetails.ProductID = products.ProductID
WHERE CartID = $cart_id";
$result = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($result)) {
    $product_id = $row['ProductID'];
    $qty = $row['Qty'];
    $sql = "INSERT INTO orderdetails (OrderID, ProductID, Qty) VALUES ('$order_id', '$product_id', '$qty')";
    mysqli_query($link, $sql);
?>

<h4 style="text-align: left;margin-left: 150px;"><label style="margin-right: 50px;"><?php echo $product_id; ?></label>
<label style="margin-left: 0px;"><?php echo $row['ProductName']; ?>(<?php echo $qty; ?>)</label>
<label style="margin-left: 100px;"><?php echo $row['Price']; ?></label>
</h4>

<?php 
} 
?>
<h3>--------------------------------------------------------------------------------------</h3>
<h3 style="text-align: left;margin-left: 140px;">SUBTOTAL <label style="margin-left: 280px;"><?php echo $subtotal ?></label></h3>
<h3 style="text-align: left;margin-left: 140px;">DISCOUNT <label style="margin-left: 280px;"><?php echo $sumdis ?></label></h3>
<h3 style="text-align: left;margin-left: 140px;">TOTAL <label style="margin-left: 315px;"><?php echo $total ?></label></h3>
<h3>===================================================</h3>
<a href="home.php">Back</a>
</center>
<?php
$sql = "SELECT CartDetailID, CartID, ProductID, Qty, COUNT(CartDetailID) AS items FROM cartdetails WHERE CartID = $cart_id AND ProductID = $product_id";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
if($row['items'] != 0 || '0') {
    $sql = "DELETE FROM cartdetails WHERE CartID = $cart_id";
    mysqli_query($link, $sql);
}
mysqli_close($link);
?>

<script>
    window.print()
</script>