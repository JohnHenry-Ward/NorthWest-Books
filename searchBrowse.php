<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for searchBrowse.php -->
    <link rel="stylesheet" type="text/css" href="css/styleSearchBrowse.css">

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

        //get the books based on either user search or set categories
        if(!empty($_GET['search'])){
            $search = mysqli_real_escape_string($link, $_GET['search']);
            $sql = "SELECT DISTINCT d.isbn, title, description, price
                    FROM bookauthors a, bookauthorsbooks ba, bookdescriptions d,
                    bookcategoriesbooks cb, bookcategories c
                    WHERE a.AuthorID = ba.AuthorID
                    AND ba.ISBN = d.ISBN
                    AND d.ISBN = cb.ISBN
                    AND c.CategoryID = cb.CategoryID
                    AND (CategoryName = '$search'
                    OR title LIKE '%$search%'
                    OR description LIKE '%$search%'
                    OR publisher LIKE '%$search%' 
                    OR concat_ws(' ', nameF, nameL, nameF) LIKE '%$search%' )
                    ORDER BY title";
        }
        elseif(!empty($_GET['browse'])){
            $browse = $_GET['browse'];
            $sql = "SELECT DISTINCT bookdescriptions.isbn, title, description, price
                    FROM bookcategories, bookcategoriesbooks, bookdescriptions
                    WHERE bookcategories.CategoryName = '$browse'
                    AND bookcategories.CategoryID = bookcategoriesbooks.CategoryID
                    AND bookcategoriesbooks.ISBN = bookdescriptions.ISBN
                    ORDER BY title";
        }

        //query the result
        $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

        //echo the amount of books found after either search or browse
        if(!empty($_GET['search'])){
            echo "<div class = 'sbResult'>".mysqli_num_rows($result)." books found with '$search'</div>";
        }
        elseif(!empty($_GET['browse'])){
            echo "<div class = 'sbResult'>".mysqli_num_rows($result)." books in the $browse category</div>";
        }

        //check if their is at least one result, or that the search field wasn't blank
        if(mysqli_num_rows($result) == 0 || (empty($search) && empty($browse))){
            echo "<div class = 'sbResult'>Try a different search!</div>";
        }
        else{
            //put the result into an array, loop through to display each
            while($row = mysqli_fetch_array($result)){
                $desc = largeText($row['description']);
                echo "<div class = 'sbBook'>
                <h1 class = 'sbTitle'><a href = 'productPage.php?isbn=$row[isbn]'>$row[title]</a><br></h1>
                    <h6 class = 'sbAuthor'>By: ".fListAuthors($link, $row['isbn'])."</h6>
                    <img class = 'sbImage' src = 'images/$row[isbn].01.THUMBZZZ.jpg'>
                    <p class = 'sbDesc'>$desc<a href = 'productPage.php?isbn=$row[isbn]'>Read More</a></p>
                    </div>";
            }
        }
    ?>
    </main>
    <?php
        //include the footer
        include("include/footer.php");
    ?>
</body>
</html>