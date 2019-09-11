<?php

// database functions ************************************************

function fCleanString($link, $UserInput, $MaxLen) {
   //Escape special characters - very important.
   //mysqli_real_escape_string requires database connection
   $UserInput = mysqli_real_escape_string($link, $UserInput);
   
   //tidy up and truncate to max length.
   $UserInput = strip_tags($UserInput);
   $UserInput = trim($UserInput);
   return substr($UserInput, 0, $MaxLen);
}

function fCleanNumber($UserInput) {
   $pattern = "/[^0-9\.]/"; //remove everything except 0-9 and period
   $UserInput = preg_replace($pattern, "", $UserInput);
   return substr($UserInput, 0, 8);
}

//takes a large section of text, shortens it and adds a redmore link to the product page of that item
function largeText($string){
   $string = strip_tags($string);

   if(strlen($string) > 500){
      $stringEnd = substr($string, 0, 500);
      $stringShort = substr($string, 0, strrpos($stringEnd, ' ', 0));
      $stringShort .= '... ';
   }

   return $stringShort;
}

function fConnectToDatabase() {
   //For code reusability this function is often located in its own file.
   //Pages that require database assess include it with include('connection.php');
   //where 'connection.php' is the name of your connect file.
   //Create a connection object
   //@ suppresses errors.  
   //parameters: mysqli_connect('my_server', 'my_user', 'my_password', 'my_db');  
   $link = @mysqli_connect('localhost', 'root', '', 'nwbooks');

   //handle connection errors
   if (!$link) {
      die('Connection Error: ' . mysqli_connect_error());
   }
   return $link;
}

//returns the correct rating (1-5) displayed as stars
function displayStars($rating){
   $stars;
   if($rating == 1){
      $stars = '&#9733&#9734&#9734&#9734&#9734';
   }
   if($rating == 2){
      $stars = '&#9733&#9733&#9734&#9734&#9734';
   }
   if($rating == 3){
      $stars = '&#9733&#9733&#9733&#9734&#9734';
   }
   if($rating == 4){
      $stars = '&#9733&#9733&#9733&#9733&#9734';
   }
   if($rating == 5){
      $stars = '&#9733&#9733&#9733&#9733&#9733';
   }
   return $stars;
}

?>