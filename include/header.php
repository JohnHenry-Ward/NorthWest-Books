<?php

if(isset($_COOKIE['BookCount']) && $_COOKIE['BookCount'] > 0){
    $cart = $_COOKIE['BookCount'];
}
else{
    $cart = '';
}

if(isset($_COOKIE['email'])){
    $email = "Logged in as: ".$_COOKIE['email'];
}
else{
    $email = '';
}
?>

<header>
        <h1 class="title"><i class="fas fa-tree"></i><a href = 'index.php'>NorthWest Books</a><i class="fas fa-tree"></i></h1>
        <div class="account">
            <a href = 'checkout01.php'>
                <i class="fas fa-user-circle">  Account</i>
            </a>
            <p class = 'accountEmail'><?php echo $email ?></p>
        </div>
        <div class="cart">
            <a href = 'shoppingCart.php'>
            <?php echo $cart ?><i class="fas fa-shopping-cart">  Cart</i>
            </a>
        </div>
        <div class="about">
            <a href = 'about.php'>
                <i class="fas fa-question">  About</i>
            </a>
        </div>
        <div class="clearFix"></div>
</header>