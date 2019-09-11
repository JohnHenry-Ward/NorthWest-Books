<?php

//Instructions:
//Create a file named encryption.php 
//and add the two encryption functions below. 
//
//Include encryption.php in any pages that utilize encryption
//
//There is a small demo at the bottom of this file that
//shows how to implement the functions. 
//
//Password has global scope
$secretPassword = "bellinghamIsTheCityOfSubduedExcitement32324";

// function encrypt($data, $password) {

//    $salt = substr(md5(mt_rand(), true), 8);

//    $key = md5($password . $salt, true);
//    $iv = md5($key . $password . $salt, true);

//    $ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

//    return base64_encode('Salted__' . $salt . $ct);
// }

// function decrypt($data, $password) {
//    $data = base64_decode($data);
//    $salt = substr($data, 8, 8);
//    $ct = substr($data, 16);

//    $key = md5($password . $salt, true);
//    $iv = md5($key . $password . $salt, true);

//    $pt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ct, MCRYPT_MODE_CBC, $iv);

//    //remove whitespace and nulls from end (and cause php concatenation issues)
//    return trim($pt);
// }

//test encryption functions
$demoActive = false; //set this to false when using the functions in your site
if ($demoActive) {
//encrypt
   $custID = 64336;
   echo "custID: $custID <br>";

   //encrypt
   $custIDe = encrypt($custID, $password);

   echo "custIDe: $custIDe <br>";

   //decrypt
   $custID = decrypt($custIDe, $password);
   echo "custID: $custID <br>";
}

//source: http://heiswayi.github.io/php-encryption-decryption-and-password-hashing.html

?>

