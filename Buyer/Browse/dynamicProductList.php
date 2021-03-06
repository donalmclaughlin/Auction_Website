<?php include 'config.php';
session_start();
$userID = $_SESSION['userID'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Product list</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript"
            src="dynamicProductListController.js">
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('table tr').click(function () {
                window.location = $(this).data('href');
                return false;
            });
        });
    </script>
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand">Auction Website</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="../BuyerProfile/BuyerProfile.php"><span class="glyphicon glyphicon-user"></span>
                    My Account</a></li>
            <li><a href="../Browse/categoryGallery.php" class="active"><span
                            class="glyphicon glyphicon-shopping-cart"></span> Categories</a>
            <li class="active"><a href="../../logout.php" class="active"><span
                            class="glyphicon glyphicon-log-out"></span> Logout</a>
            </li>
        </ul>
    </div>
</nav>


<?php include 'searchBarHeader.php';

$productSearch = $_POST["search"];

$category2 = $_POST["searchCategories"];

$category1 = $_GET["category"];

date_default_timezone_set('Europe/London');
$dateNow = date("Y-m-d H:i:s");

?>

<div id="container1">
    <select class="smaller" name="Name Alphabetical" id="nameAlphabetical" onclick="sortName()">
        <option value="" disabled selected>Name</option>
        <option value="Name Alphabetical">Name Alphabetical</option>
    </select>

    <select class="smaller" name="Ending soon" id="endingSoon" onclick="endingSoon(value)">
        <option value="" disabled selected>Sort by End Date</option>
        <option value="Ending soonest">Ending soonest</option>
        <option value="Ending latest">Ending latest</option>
    </select>

    <select class="smaller" name="Condition Filter" id="condition" onclick="filterCategory()">
        <option value="" disabled selected>Condition</option>
        <option value="e">New or Used</option>
        <option value="New">New</option>
        <option value="Used">Used</option>
    </select>

    <select class="smaller" name="Reserve price" id="reservePrice" onclick="sortReservePrice(value)">
        <option value="" disabled selected>Sort Reserve Price</option>
        <option value="High to Low">High to Low</option>
        <option value="Low to High">Low to High</option>
    </select>

    <select class="smaller" name="Highest bid" id="highestBid" onclick="sortHighestBid(value)">
        <option value="" disabled selected>Sort Highest Bid</option>
        <option value="High to Low">High to Low</option>
        <option value="Low to High">Low to High</option>
    </select>
</div>

<div id="container3">
    <?php
    if (isset($category1)) {

        $sql = "SELECT prod_ID, prod_name, prod_category, prod_description, prod_start_price, prod_reserve_price, prod_end_date, prod_condition, prod_picture, prod_highest_bid FROM product WHERE prod_category = '$category1' AND '$dateNow' <= prod_end_date";
    } // run search for all categories
    else if ($category2 == "*") {
        $sql = "SELECT prod_ID, prod_name, prod_category, prod_description, prod_start_price, prod_reserve_price, prod_end_date, prod_condition, prod_picture, prod_highest_bid FROM product WHERE prod_name LIKE '%$productSearch%' AND '$dateNow' <= prod_end_date";
    } // run search within a category
    else {

        $sql = "SELECT prod_ID, prod_name, prod_category, prod_description, prod_start_price, prod_reserve_price, prod_end_date, prod_condition, prod_picture, prod_highest_bid FROM product WHERE (prod_name LIKE '%$productSearch%') AND (prod_category = '$category2')  AND '$dateNow' <= prod_end_date";
    }
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    ?>
    <table id="myTable" class="table table-bordered table-hover">
        <tr>
            <th style="text-align: center"></th>
            <th style="text-align: center">Name</th>
            <th style="text-align: center">Category</th>
            <th style="text-align: center">End date</th>
            <th style="text-align: center">Condition</th>
            <th style="text-align: center">Reserve Price</th>
            <th style="text-align: center">Current Bid</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $prod_ID = $row["prod_ID"];
            ?>


            <tr data-href='../product/productPage.php?prod_ID=<?php echo $prod_ID; ?>'>

                <td>
                    <?php echo '<a href="../product/productPage.php?prod_ID=' . $prod_ID . '"><img class="productimage" src="data:image/jpeg;base64,' . base64_encode($row['prod_picture']) . '"/></a>';
                    ?>
                </td>

                <td><?php echo $row["prod_name"]; ?> </td>
                <td><?php echo $row["prod_category"]; ?> </td>
                <td><?php echo $row["prod_end_date"]; ?> </td>
                <td><?php echo $row["prod_condition"]; ?> </td>
                <td><?php echo $row["prod_reserve_price"]; ?> </td>
                <td><?php echo $row["prod_highest_bid"]; ?> </td>

            </tr>

        <?php }
        ?>

        <?php
        } else {
            echo "0 results";
        }

        mysqli_close($db);
        ?>
</div>
</body>
</html>




