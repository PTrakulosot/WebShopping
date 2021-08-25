<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: home.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_FILES['file']['name'];
    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    $sql = "SELECT ImageID FROM images ORDER BY ImageID DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $image_id = $row['ImageID'];


    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // Check extension
    if (in_array($imageFileType, $extensions_arr)) {

        // Convert to base64 
        $image_base64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']));
        $image = 'data:image/' . $imageFileType . ';base64,' . $image_base64;
        // Insert record
        $query = "insert into images(image) values('" . $image . "')";
        mysqli_query($link, $query);

        // Upload file
        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $name);

        // Query Products
        $product_name = $_POST["product_name"];
        $detail = $_POST["detail"];
        $price = $_POST["price"];
        $category = $_POST["category"];
        $sql = "INSERT INTO products (ProductName, ProductDetail, CategoryID, Price, ImageID) VALUES ('$product_name', '$detail', '$category', '$price' , '$image_id'+1)";
        mysqli_query($link, $sql);
    }

    mysqli_close($link);

    header("location: display.php");
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
            <li class="nav-item">
                <a href="repair_admin.php" class="nav-link text-dark font-italic bg-light">
                    <i class="fa fa-tools mr-3 text-primary fa-fw"></i>
                    Repair
                </a>
            </li>
            <li class="nav-item active">
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
        <h1>Upload New Product</h1>
        <div class="separator"></div>
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype='multipart/form-data'>
                    Name : <input type='text' name='product_name' value="<?php echo $product_name; ?>"><br><br>
                    Detail : <br><textarea name='detail' rows='5' cols='40'><?php echo $detail; ?></textarea><br><br>
                    Price : <input type='text' name='price' value="<?php echo $price; ?>"><br><br>
                    Category :
                    <input type="radio" name="category" <?php if (isset($category) && $category == "1") echo "checked"; ?> value="1">
                    เครื่องปรับอากาศ
                    <input type="radio" name="category" <?php if (isset($category) && $category == "2") echo "checked"; ?> value="2">
                    ทีวีและเครื่องเสียง
                    <br><br><input type='file' name='file'><br><br>
                    <input type='submit' value='SUBMIT'>
                </form>
                <br>
                <!-- <a href="home.php">Back to Home Page</a> -->
            </div>
        </div>
    </div>
</body>

<footer>

</footer>

</html>