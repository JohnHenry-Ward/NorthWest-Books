<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for checkout01.php -->
    <link rel="stylesheet" type="text/css" href="css/styleCheckout01.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

    <title>Sign In | NW Books</title>
</head>
<body>
    <?php
        //include the header and the side navigation
        include("include/header.php");
    ?>
    <main>
    <?php
        include("include/databaseConnection.php");

        include('include/side.php');

        include('include/validationUtilities.php');

        if(isset($_COOKIE['BookCount'])){
            $qty = $_COOKIE['BookCount'];
        }
        
        if(isset($_COOKIE['email'])){
            $email = $_COOKIE['email'];
        }

        //display how many items in the cart
        echo "<div class = 'itemCount'>";
        if(isset($totalbooks)){
            if($totalbooks == 0){
                echo "Your cart is empty";
            }
            elseif($totalbooks != 1){
                echo $totalbooks . " items in your cart";
            }
            else{
                echo $totalbooks . " item in your cart";
            }
        }
        echo "</div>";
    
    ?>

    <p class = 'chHeader'>Please enter your email address</p>

    <form method = 'POST' action = 'checkout02.php' class = 'chForm'>
        <input type = 'email' name = 'email' class = 'email' placeholder = 'yourEmail@example.com' value = <?php echo $email ?>>
        <input type = 'submit' class = 'proceedBTN' value = 'Proceed to Checkout'>
    </form>


    </main>
    <?php

        //include the footer
        include('include/footer.php');
    ?>
</body>
</html>