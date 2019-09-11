<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for orderHistory.php -->
    <link rel="stylesheet" type="text/css" href="css/styleOrderHistory.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

    <title>Order History | NW Books</title>
</head>
<body>
    <?php
        include('include/databaseConnection.php');

        //include the header and the side navigation
        include("include/header.php");
    ?>
    <main>
    <?php
        include('include/listAuthors.php');
        include('include/encryption.php');
        include('include/side.php');

        //get the encrypted orderID
        $custID = $_POST['custID'];
        //$custID = decrypt($custID, $secretPassword);

        //connect to database
        $link = fConnectToDatabase();

        $sql = "SELECT bookorders.orderID, custID, bookorderitems.isbn, title, qty, orderDate 
                FROM bookorders, bookorderitems, bookdescriptions 
                WHERE custID = $custID AND bookorders.orderID = bookorderitems.orderID AND bookorderitems.isbn = bookdescriptions.isbn
                ORDER BY bookorders.orderID";

        //query the result
        $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

        echo "<h1 class = 'orderHistoryHeader'>Order History</h1>";

        echo "<table class = 'orderHistoryTable'>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>
                    <td>
                        <a href = 'productPage.php?isbn=$row[isbn]'><img class = 'bookImage' src = '/sandvig/mis314/assignments/bookstore/bookimages/$row[isbn].01.THUMBZZZ.jpg'></a>
                        <p>Order ID: $row[orderID] | Order Date: $row[orderDate]</p>
                        <p><a href = 'productPage.php?isbn=$row[isbn]'>$row[title]</a></p>
                        <p>By: ".fListAuthors($link, $row[isbn])."</p>
                        <p>Qty: $row[qty]</p>
                    </td>
                </tr>";
        }
        echo "</table>";
        
    ?>
    </main>
    <?php
        //include the footer
        include("include/footer.php");
    ?>
</body>
</html>