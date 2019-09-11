<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for checkout03.php -->
    <link rel="stylesheet" type="text/css" href="css/styleCheckout03.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

    <title>NorthWest Books</title>
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

        //get inputs from querystring
        $email = $_POST['email'];
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        // $custID = $_POST['custID'];

        $subTotal = 0;
        $bookDetails = '';


        //set validation flag
        $isValid = true;

        //validate inputs
        echo "<p class = 'validationFailed'>";
            if(!fIsValidEmail($email)){
                echo "Invalid email<br>";
                $isValid = false;
            }

            if(!fIsValidLength($fName, 2, 20)){
                echo "Invalid first name (2 - 20 characters)<br>";
                $isValid = false;
            }

            if(!fIsValidLength($lName, 2, 30)){
                echo "Invalid last name (2 - 30 characters)<br>";
                $isValid = false;
            }

            if(!fIsValidLength($street, 2, 50)){
                echo "Invalid street (2 - 50 characters)<br>";
                $isValid = false;
            }

            if(!fIsValidLength($city, 2, 30)){
                echo "Invalid city (2 - 30 characters)<br>";
                $isValid = false;
            }

            if(!fIsValidStateAbbr($state)){
                echo "Invalid 2-character state abbreviation<br>";
                $isValid = false;
            }

            if(!fIsValidZip($zip)){
                echo "Invalid 5-character zipcode<br>";
                $isValid = false;
            }
        "</p>";

        //at least one element is not valid
        if(!$isValid){
            echo "<input class='backBTN' type='button' value='Go Back' onclick='history.back()'>";
        }
        //all inputs are valid
        else{

        //returning customer, retreive custID
        
            if(strlen($custID) > 0){
                // $custID = $decrypt($custIDe, $secretPassword);
                $custID = $custID;
            }
        
        //new customer, set temporary custID, will be changed later
        else{
            $custID = 0; //temporary placeholder
        }

        //new customer, insert custInfo into database
        if($custID == 0){
            $sql = "INSERT INTO bookcustomers (fName, lName, email, street, city, state, zip) 
                    VALUES ('$fName', '$lName', '$email', '$street', '$city', '$state', '$zip')";
            //get the autoIncrement custID for order history
            $custID = mysqli_insert_id($link);

            mysqli_query($link, $sql) or die('Insert error: ' . mysqli_error($link));
        }
        //returning customer, update custInfo in case it was changed
        else{
            $sql = "UPDATE bookcustomers
                    SET fName ='$fName', lName ='$lName', email ='$email', street ='$street', city ='$city', state ='$state', zip ='$zip'
                    WHERE custID = '$custID'";
            
            mysqli_query($link, $sql) or die('Update error: ' . mysqli_error($link));
        }

        // retrieve bookArray(if there are books in cart) that is storing the cart and unserialize into $bookArray
        if (isset($_COOKIE['myCart2']) && $_COOKIE['BookCount'] > 0) {
            $orderTime = date('Y-m-d');
            $bookArray = unserialize($_COOKIE['myCart2']);
            //clear the cart
            setcookie('myCart2', null, time() - 60000);
            setcookie('BookCount', null, time() - 60000);

            $sql = "INSERT INTO bookorders (custID, orderDate)
                    VALUES ($custID, '$orderTime')";

            mysqli_query($link, $sql) or die('Insert error: ' . mysqli_error($link));

            $orderID = mysqli_insert_id($link);

            //for each book ordered in 1 order
            foreach($bookArray as $isbn => $qty){
                $discount = 0.8;
                $sql = "INSERT INTO bookorderitems (orderID, isbn, qty, price) 
                        VALUES ($orderID, '$isbn', $qty, (select (price * $discount) from bookdescriptions where ISBN = '$isbn'))";
                
                mysqli_query($link, $sql) or die('Insert error: ' . mysqli_error($link));
            }
        
        // display the shipping information
        echo "
        <h1 class = 'shippingConfirm'>Shipping Confirmation</h1>
        <div class = 'shippingInfo'>
            <p>Shipping Address</p>
            <p>$fName $lName</p>
            <p>$street $city</p>
            <p>$state $zip</p>
            <p>Order Number: $orderID</p>
        </div>
        ";

        // display the books ordered
        $sql = "SELECT ISBN, title, price
                FROM bookdescriptions
                WHERE ";

        //loops to get each isbn listed and concatenates
        foreach($bookArray as $isbn => $qty){
            $sql .= "ISBN = '$isbn' OR ";
        }
        $sql = substr($sql, 0, strlen($sql) - 3);

        //query the result
        $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

        //start the cart out of the loop so it displays only once
        echo "<table class='cartTable'>
                    <tr>
                        <th>Title</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>";

        if (count($bookArray)) {
            
            //for each book in the cart
            foreach ($bookArray as $isbn => $qty) {

                //loop through to get the contents of the book
                while($row = mysqli_fetch_array($result)){
                    $isbn = $row['ISBN'];
                    $qty = $bookArray[$isbn];
                    $title = $row['title'];
                    $price = number_format($row['price'] - ($row['price'] * .2), 2);
                    $itemTotal = number_format($qty * $price, 2);
                    $subTotal += number_format($itemTotal, 2);
                    $shipping = number_format(3.49 + (0.99 * $qty - 1), 2);
                    $total = number_format($subTotal + $shipping, 2);
                    $bookDetails .= $title." ";
                
                //the table rows are echoed, one for each book
                echo "
                <tr>
                <td>
                    <a class='booktitle' href='ProductPage.php?isbn=$isbn'>$title</a> 
                </td>
                <td>$qty</td>
                <td>$$price</td>
                <td>$$itemTotal</td>
                </tr>";
                }
            }
        }

        echo "</table>"; //end of table cartTable

        //show shipping price and total price
        echo "<div class = 'shipTotal'>
                <p>Sub-Total: $$subTotal</p>
                <p>*Shipping: $$shipping</p>
                <p>Total: $$total</p>
              </div>";

        //send HTML email
        $to = $email;
        $subject = 'NorthWest Books Order Confirmation';
        $message = "
        <html>
        <head>
            <title>NorthWest Books Order Confirmation</title>
            <style type = 'text/css'>
                .header{
                    text-align: center;
                    color: #2B7A78;
                    font-size: 30px;
                }
                .text{
                    color: #17252A;
                    font-size: 16px;
                }
                .shipping{
                    color: #2B7A78;
                    font-size: 24px;
                    padding-bottom: 10px;
                    margin-bottom: 0;
                }
                .info{
                    font-size: 16px;
                    color: #17252A;
                }
                p{
                	margin: 3px;
                    padding: 0;
                }
            </style>
        </head>
        <body>
            <h1 class='header'>Thank Your for Ordering!</h1>
            <p class = 'text'>Thank you so much for shopping with NorthWest Books. We hope to see you again soon $fName!</p>
            <h6 class = 'shipping'>Shipping Address</h6>
            <p class = 'info'>$fName $lName</p>
            <p class = 'info'>$street $city</p>
            <p class = 'info'>$state $zip</p>
            <p class = 'info'>Order Number: $orderID</p>
            <p class = 'info'>Order Date: $orderTime</p>
            <p class = 'info'>Total Cost: $$total</p>
        </body>
        </html>";

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: NorthWest Books <johnhenry514@gmail.com>';

        // Mail it
        //mail($to, $subject, $message, implode("\r\n", $headers));

              
        } //end of if checking if cart is empty

        else{
            // display the shipping information
            echo "
            <h1 class = 'shippingConfirm'>Updated Information</h1>
            <div class = 'shippingInfo'>
                <p><u>Shipping Address</u></p>
                <p>$fName $lName</p>
                <p>$street $city</p>
                <p>$state $zip</p>
            </div>
            ";

            $sql = "UPDATE bookcustomers
                    SET fName ='$fName', lName ='$lName', email ='$email', street ='$street', city ='$city', state ='$state', zip ='$zip'
                    WHERE custID = '$custID'";
            
            mysqli_query($link, $sql) or die('Update error: ' . mysqli_error($link));

            //send email confirmation
            $subject = "NorthWest Books Information Update";
            $body = "$bookDetails $email \r\n$fName $lName \r\n$street $city \r\n$state $zip";
            // mail will work if on correct PHP server
            // mail($email, $subject, $body, 'From: johnhenry514@gmail.com');
        
        } //end of else

        //encrypt the custID to pass through querystring
        // $custIDe = encrypt($custID, $secretPassword);

        //keep shopping and order history buttons
        echo "<div class = 'cartBTNS'>
                <a class = 'leaveCartBTN' href = 'index.php'>Continue Shopping</a>
              </div>
              <form action='orderHistory.php' method='POST' class='orderHistoryForm'>
                    <input type = 'submit' value = 'Order History' class='orderHistoryBTN'>
                    <input type = 'hidden' value = '$custID' name = 'custIDe'>
                </form>";

        }//end of else where inputs are valid
    ?>


    </main>
    <?php
        //include the footer
        include('include/footer.php');
    ?>
</body>
</html>