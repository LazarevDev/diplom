<?php 
$db = mysqli_connect('localhost', 'root', '', 'diplom') or die(mysqli_error($db));
mysqli_set_charset($db, 'utf8');
error_reporting(E_ERROR | E_PARSE);

if(!empty($_COOKIE['phone']) AND !empty($_COOKIE['password'])){
    $phoneCookie = $_COOKIE['phone'];
    $passwordCookie = $_COOKIE['password'];
    
    $queryStaff = mysqli_query($db, "SELECT * FROM `staff` WHERE `phone` = '$phoneCookie' AND `password` = '$passwordCookie'");
    $resultStaff = mysqli_fetch_array($queryStaff);
    
    $idStaff = $resultStaff['id'];
}
?>