<?php
include_once("include/databaseConnection.php");
$link = fConnectToDatabase();
$qty = 0;
$subTotal = 0;

//Shopping cart uses cookies to store cart items.
//PHP script uses an array for adding, removing and displaying the cart items.
//Cookies can contain only string data so array must be serialized.

$cookieName = "myCart2";
// retrieve cookie and unserialize into $bookArray
if (isset($_COOKIE[$cookieName])) {
   $bookArray = unserialize($_COOKIE[$cookieName]);
}
// Add items to cart
if(isset($_GET['addISBN'])){
    $addISBN = fCleanString($link, $_GET[addISBN], 10);
    if (strlen($addISBN) > 0) {
    if (isset($addISBN, $bookArray)) {
        // Increment by +1
        $bookArray[$addISBN] += 1;
    } else {
        // Add new item to cart
        $bookArray[$addISBN] = 1;
    }
    header("location:shoppingCart.php");
}
}
// Remove items from cart
if(isset($_GET['deleteISBN'])){
$deleteISBN = fCleanString($link, $_GET['deleteISBN'], 10);
    if (strlen($deleteISBN) > 0) {
    if (isset($bookArray[$deleteISBN])) {
        // Deincrement by 1
        $bookArray[$deleteISBN] -= 1;
        // remove ISBN from array if qty==0
        if ($bookArray[$deleteISBN] == 0) {
            unset($bookArray[$deleteISBN]);
        }
    }
    header("location:shoppingCart.php");
    }
}
if (isset($bookArray)) {
   // Write cookie
   setcookie($cookieName, serialize($bookArray), time() + 60 * 60 * 24 * 180);

   //Count total books in cart
   $totalbooks = 0;
   foreach ($bookArray as $isbn => $qty) {
      $totalbooks += $qty;
   }
   setCookie('BookCount', $totalbooks, time() + 60 * 60 * 24 * 180);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for shoppingCart.php -->
    <link rel="stylesheet" type="text/css" href="css/styleCart.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

    <title>Your Cart | NW Books</title>
</head>
<body>
    <?php
        //include the header and the side navigation
        include("include/header.php");
    ?>
    <main>
    <?php
        include('include/side.php');

        //display how many items in the cart
        echo "<div class = 'itemCount'>";
            if($totalbooks == 0){
                echo "Your cart is empty";
            }
            elseif($totalbooks != 1){
                echo $totalbooks . " items in your cart";
            }
            else{
                echo $totalbooks . " item in your cart";
            }
        echo "</div>";
    
        //if cart is not empty
        if($qty > 0){

        $sql = "SELECT ISBN, title, price
                FROM bookdescriptions
                WHERE ";

        //loops to get each isbn listed and concatenates
        foreach($bookArray as $isbn => $qyt){
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
                        <th>Add/Remove</th>
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
                
                //the table rows are echoed, one for each book
                echo "
                <tr>
                <td>
                    <a class='booktitle' href='ProductPage.php?isbn=$isbn'>$title</a> 
                </td>
                <td>$qty</td>
                <td>$$price</td>
                <td>$$itemTotal</td>
                <td>
                    <a href='?addISBN=$isbn'>Add</a><br>
                    <a href='?deleteISBN=$isbn'>Remove</a>
                </td>
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

        //keep shopping and checkout buttons
        echo "<div class = 'cartBTNS'>
        <a class = 'leaveCartBTN' href = 'index.php'>Continue Shopping</a>
        <a class = 'proceedCartBTN' href = 'checkout01.php'>Proceed to Checkout</a>
        </div>";
    
        } //end of if statement checking if books are in cart at all
    ?>
    </main>
    <?php
        //include the footer
        include('include/footer.php');
    ?>
</body>
</html>