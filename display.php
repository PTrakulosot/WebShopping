<?php
include("config.php");

// $sql = "SELECT Image FROM images ORDER BY Image DESC LIMIT 1";
// $sql = "SELECT Image FROM images WHERE ImageID=3";
$sql = "SELECT images.Image, ProductID, ProductName, ProductDetail, Price, categories.CategoryName FROM `products` 
INNER JOIN images ON products.ImageID = images.ImageID
INNER JOIN categories ON products.CategoryID = categories.CategoryID
ORDER BY ProductID DESC LIMIT 1";

$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

$image_src = $row['Image'];
$product_id = $row['ProductID'];
$product_name = $row['ProductName'];
$product_detail = $row['ProductDetail'];
$price = $row['Price'];
$cate_name = $row['CategoryName'];

$customer_id = $row['CustomerID'];
$customer_name = $row['CustomerName'];
$gen = $row['Gender'];

?>

<img src='<?php echo $image_src; ?>'>
<!-- <h1>ID: <?php echo $customer_id; ?></h1>
<h1>NAME: <?php echo $customer_name; ?></h1>
<h1>SEX: <?php echo $gen; ?></h1> -->

<h1>ID: <?php echo $product_id; ?></h1>
<h1>NAME: <?php echo $product_name; ?></h1>
<h1>DETAIL: <?php echo $product_detail; ?></h1>
<h1>PRICE: <?php echo $price; ?></h1>
<h1>CATEGORY: <?php echo $cate_name; ?></h1>
<a href="upload.php">Back to Upload Page</a>

