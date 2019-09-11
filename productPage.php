<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for productPage.php -->
    <link rel="stylesheet" type="text/css" href="css/styleProduct.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

    <title>Product Details | NW Books</title>
</head>
<body>
    <?php
        ob_start();

       include('include/databaseConnection.php');

       //include the header and the side navigation
       include("include/header.php");
    ?>
    <main>
    <?php
        include('include/listAuthors.php');

        include('include/side.php');

        $link = fConnectToDatabase();

        if(empty($_GET['isbn']) && empty($_GET['sendReview'])){
            echo "<h1 class = 'emptyProduct'>No product was chosen!</h1>";
        }
        else{

            $isbn = $_GET['isbn'];

            $isbn = fCleanString($link, $isbn, 10);

            $sql = "SELECT title, price, publisher, description, ISBN, pages, edition 
                    FROM bookdescriptions
                    WHERE ISBN = '$isbn'";

            $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

            $row = mysqli_fetch_array($result);

            $savings = number_format($row['price'] * .2, 2);
            $ourPrice = number_format($row['price'] - $savings, 2);


            echo "<div class = 'product'>
                    <h1 class = 'productTitle'>$row[title]</h1>
                    <h6 class = 'productAuthors'>By: ".fListAuthors($link, $isbn)."</h6>
                    <a href = 'images/$isbn.01.LZZZZZZZ.jpg' target = '_BLANK'><img class = 'productImage' src = 'images/$isbn.01.MZZZZZZZ.jpg'></a>
                    <div class = 'productInfo'>
                        <h3 class = 'listPrice'>List Price: <strike>$$row[price]</strike></h3>
                        <h3 class = 'ourPrice'>Our Price: $$ourPrice</h3>
                        <h3 class = 'saving'>You Save: $$savings (20%)</h3>
                        <h6 class = 'productIsbn'>ISBN: $isbn</h6>
                        <h6 class = 'productPublisher'>Publisher: $row[publisher]</h6>
                        <h6 class = 'productPages'>Pages: $row[pages]</h6>
                        <h6 class = 'productEdition'>Edition: $row[edition]</h6>
                    </div>
                    <div class = 'productDesc'>$row[description]
                    </div>
                </div>";

            echo "<a href = 'shoppingCart.php?addISBN=$isbn'><div class = 'cartBTN'>Add to Cart</div></a>";

            //Customer reviews
            echo "<a href = 'productPage.php?review=true&isbn=$isbn'><div class = 'reviewBTN'>Add a Review!</div></a>";
            if(isset($_GET['review'])){
                if($_GET['review'] == true){
                ?>

                <div class = 'reviewPopup'>
                    <h2 class = 'reviewTitle'>Reviewing: <?php echo $row['title'] ?></h2>
                    <form action='productPage.php?isbn=$isbn'>
                        <div class = 'formGroup'>
                            <label>Name:</label>
                            <input name = 'name' type = 'text' class = 'reviewInput' required><br>
                        </div>
                        <div class = 'formGroup'>
                            <label>City:</label>
                            <input name = 'city' type = 'text' class = 'reviewInput' required><br>
                        </div>
                        <div class = 'formGroup'>
                            <label>Rating (1-5):</label>
                            <input name = 'rating' type = 'number' min='1' max='5' class = 'reviewInput' required><br>
                        </div>
                        <div class = 'formGroup'>
                            <label>Review:</label>
                            <textarea name = 'review' rows = '6' cols = '18' maxlength = '400' class = 'reviewBox' required></textarea><br>
                        </div>
                        <input name = 'sendReview' type = 'submit' value = 'Finish Review' class = 'reviewSubmitBTN'>
                        <input type = 'hidden' name = 'isbn' value = <?php echo $isbn ?>>
                    </form>
                </div>

                <?php

                if(!empty($_GET[sendReview])){
                    $rating = $_GET[rating];
                    $name = $_GET[name];
                    $city = $_GET[city];
                    $review = mysqli_real_escape_string($link, $_GET[review]);
                    $isbn = $_GET[isbn];

                    $sql = "INSERT INTO bookreview (name, city, rating, review, ISBN)
                            VALUES ('$name', '$city', '$rating', '$review', '$isbn')";

                    mysqli_query($link, $sql) or die('Update error: ' . mysqli_error($link));

                    header("location: productPage.php?isbn=$isbn");

                }
            }}

            //display reviews

            $sql = "SELECT name, city, rating, review 
                    FROM bookreview
                    WHERE ISBN = '$isbn'";

            $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

            echo "<div class = 'allReviews'>";
            while($row = mysqli_fetch_array($result)){
                echo "<div class = 'reviewGroup'>
                        <p class = 'reviewNameCity'>Reviewed by $row[name] in $row[city]</p>
                        <p class = 'reviewRating'>".displayStars($row['rating'])."</p>
                        <p class = 'reviewReview'>$row[review]</p>
                      </div>";
            }
            echo "</div>";
        }
    ?>
    </main>
    <?php
        //include the footer
        include('include/footer.php');
    ?>
</body>
</html>