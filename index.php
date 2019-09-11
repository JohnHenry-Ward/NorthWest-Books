<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for index.php -->
    <link rel="stylesheet" type="text/css" href="css/styleIndex.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

    <title>NorthWest Books</title>
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

        include('include/side.php');

        //connect to database
        $link = fConnectToDatabase();

        //select 3 random books, price, and description
        $sql = "SELECT title, description, isbn
                FROM bookdescriptions
                ORDER BY rand() LIMIT 3";

        //query the random result
        $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

        //put the result into an array, loop through to display each
        while($row = mysqli_fetch_array($result)){
            $desc = largeText($row['description']);
            echo "<div class = 'randBook'>
                    <h1 class = 'bookTitle'><a href = 'productPage.php?isbn=$row[isbn]'>$row[title]</a></h1>
                    <h6 class = 'bookAuthor'>By: ".fListAuthors($link, $row['isbn'])."</h6>
                    <a href = 'productPage.php?isbn=$row[isbn]'><img class = 'bookImage' src = 'images/$row[isbn].01.THUMBZZZ.jpg'></a>
                    <p class = 'bookDesc'>$desc<a href = 'productPage.php?isbn=$row[isbn]'>Read More</a></p>
                  </div>";
        }
    ?>
    </main>
    <?php
        //include the footer
        include('include/footer.php');
    ?>
</body>
</html>