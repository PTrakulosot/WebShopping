<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}
include("config.php");
$payment_id = $_GET['paymentid'];
// $sql = "SELECT Image FROM images ORDER BY Image DESC LIMIT 1";
$sql = "SELECT Image FROM paymentimages WHERE PaymentID=$payment_id";
// $sql = "SELECT images.Image, ProductID, ProductName, ProductDetail, Price, categories.CategoryName FROM `products` 
// INNER JOIN images ON products.ImageID = images.ImageID
// INNER JOIN categories ON products.CategoryID = categories.CategoryID
// ORDER BY ProductID DESC LIMIT 1";

$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

$image_src = $row['Image'];

?>

<img src='<?php echo $image_src; ?>'>
<a href="home_admin.php">Back to Admin Page</a>

