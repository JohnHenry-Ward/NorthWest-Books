<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for checkout02.php -->
    <link rel="stylesheet" type="text/css" href="css/styleCheckout02.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

    <title>Your Info | NW Books</title>
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

        include('include/encryption.php');

        $email = $_POST['email'];

        setcookie('email', $email, time() + 68400);

        //if email is valid
        if(fIsValidEmail($email)){

            $sql = "SELECT *
                    FROM bookCustomers
                    WHERE email = '$email'";

            //query the random result
            $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));
                        
            //if email is not in database
            if (mysqli_num_rows($result) == 0 ) {
                echo "<p class = 'customerHeader'>New Customer - Please provide your shipping address.</p>";
            }
            //if email is in database
            else {
                echo "<p class = 'customerHeader'>Returning Customer - Please confirm your mailing and e-mail addresses.</p>"; 
                $row = mysqli_fetch_array($result);
                // $custIDe = encrypt($row['custID'], $secretPassword);
            }
            ?>
            <form class = 'chForm' action = 'checkout03.php' method = 'POST'>
                <div class = 'chFormGroup'>
                    <label for = 'email'>Email: </label>
                    <input type="email" name="email" value = "<?php echo $email ?>" required>
                </div>
                <div class = 'chFormGroup'>
                    <label for = 'fName'>First Name: </label>
                    <input type="text" name="fName" value = "<?php echo $row['fName'];?>" required>
                </div>
                <div class = 'chFormGroup'>
                    <label for = 'lName'>Last Name: </label>
                    <input type="text" name="lName" value = "<?php echo $row['lName'];?>" required>
                </div>
                <div class = 'chFormGroup'>
                    <label for = 'street'>Street: </label>
                    <input type="text" name="street" value = "<?php echo $row['street'];?>" required>
                </div>
                <div class = 'chFormGroup'>
                    <label for = 'city'>City: </label>
                    <input type="text" name="city" value = "<?php echo $row['city'];?>" required>
                </div>
                <div class = 'chFormGroup'>
                    <label for = 'state'>State: </label>
                    <input type="text" name="state" value = "<?php echo $row['state'];?>" required>
                </div>
                <div class = 'chFormGroup'>
                    <label for = 'zip'>Zip: </label>
                    <input type="text" name="zip" value = "<?php echo $row['zip'];?>" required>
                </div>      
                    <input type="submit" value="Place Your Order" class = 'chFormBTN'>
                    <input type = "hidden" name = 'custIDe' value="<?php echo $custID ?>">
            </form>
                        
            <!-- hidden form field for orderHistory display for returning customers-->
            <?php
            if(isset($custID)){
            if(strlen($custID) > 0){ ?>
            <form method = 'POST' action = 'orderHistory.php' class = 'orderHistoryForm'>
                <input type="submit" value="Order History" class = 'chFormBTN'>
                <input type="hidden" name="custIDe" value="<?php echo $custID ?>">
            </form>
            <?php
            }}  
        }
        //if email is not valid  
        else{
            echo "<p class = 'invalidEmail'>Please provide a valid email!</p>
            <a href = 'checkout01.php'><div class = 'backBTN'>Go Back</div></a>
            ";
        }

    ?>

    </main>
    <?php
        //include the footer
        include('include/footer.php');
    ?>
</body>
</html>