<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Internal CSS for /include -->
    <link rel="stylesheet" type="text/css" href="css/styleInclude.css">

    <!-- Internal CSS for about.php -->
    <link rel="stylesheet" type="text/css" href="css/styleAbout.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">

    <title>About | NW Books</title>
</head>
<body>
    <?php
        //include connectection to database
        include('include/databaseConnection.php');
        
        //include the header and the side navigation
        include("include/header.php");
    ?>
    <main>
    <?php
        include('include/side.php');
    ?>
    <ul class = 'pageContent'>
          <li>Site created by JohnHenry Ward as a class project for <a href="http://yorktown.cbe.wwu.edu/sandvig/mis314/">MIS
            314</a> at Western Washington University. </li>
          <li>All product information is dynamically generated using PHP and mySQL.</li>
          <li>Product, customer and order information is stored in a mySQL database.</li>
          <li>Include files are used for all code that is used more
            than once (i.e. search/browse menu, ListAuthor function, 
            header and footer). </li>
          <li><span class="subHead">mySQL Database</span>
            <ul>
              <li>Normalized to 3rd normal form (or greater). Tables include:
                <ul>
                  <li>book details </li>
                  <li>book categories</li>
                  <li>relationship details-books (many-to-many) </li>
                  <li>authors</li>
                  <li>relationship authors-books  (many-to-many) </li>
                  <li>customers</li>
                  <li>orders</li>
                  <li>order items (one-to-many) </li>
                  <li>book reviews</li>
                </ul>
              </li>
            </ul>
          </li>
          <li><span class="subHead">Home page</span>
          <ul>
            <li>Selects three random items from from the
            database using a SQL statement.</li>
            <li>Generates the browse menu dynamically from the database using a SQL query that shows
              only the book categories that currently contain books.</li>
            <li>Truncates book descriptions at 250 characters.</li>
          </ul>
          </li>
          <li><span class="subHead">Search page</span>
                <ul>
                  <li>Cleans user entered data to protect against SQL Injection attacks and cross-site scripting. </li>
                  <li>Searches book title, description, author and
                    category fields in the database.</li>
                  <li>The mysql_num_rows() function is used
                    to count the number of books found by the search.</li>
                  <li>Responds gracefully to searches that return no matches.</li>
                </ul>
          </li>
          <li><span class="subHead">Shopping cart page</span>
                <ul>
                  <li>Uses a cookie to store the ISBNs of items in the
                    cart.</li>
                </ul>
          </li>
          <li><span class="subHead">Checkout pages</span>
                <ul>
                  <li>Searches the database for email addresses of existing
                    customer accounts and writes their shipping information in
                    the form on the order confirmation page.</li>
                  <li>Customer ID is encrypted using Rijndael encryption algorithm
                    </li>
                </ul>
          </li>
          <li><span class="subHead">Order Confirmation Page</span>
                <ul>
                  <li>Checks for shopping cart and prompts user if cart is
                    empty.</li>
                  <li>All fields are checked to make sure that they contain
                    information.</li>
                  <li>Checks email address in database and prompts user to try
                    again user if address not found.</li>
                  <li>Modifications made to customer information are updated in
                    the database.</li>
                  <li>Order information are written to the database.</li>
                  <li>An email is sent to the customer with the order
                    information.</li>
                    <li>The shopping cart is emptied by setting ItemCount to zero in the ShoppingCart cookie.</li>
                </ul>
          </li>
          <li><span class="subHead">Order History Page</span>
                <ul>
                  <li>Searches the database for all orders associated with
                    e-mail address</li>
                  <li>If no matching email address is found user is prompted to
                    try again.</li>
                </ul>
          </li>
          <li><span class="subHead">Enhancements</b></span>
                <ul>
                  <li>Upgraded graphical appearance</li>
                  <li>Autofill email box</li>
                  <li>Added category item count to browse menu</li>
                  <li>Book descriptions end on full word</li>
                  <li>HTML email sent rather than a plain text email</li>
                  <li>Shopping cart status box</li>
                  <li>Customer reviews</li>
                  <li>Added display to show who is logged in at the current session</li>
                </ul>
          </li>
          <li>Thanks to Amazon.com for the use of its
            icons, book images and book descriptions.</li>     
        </ul>

    </main>
    <?php
        //include the footer
        include('include/footer.php');
    ?>
</body>
</html>