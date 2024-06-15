<?php 
$db = mysqli_connect('localhost', 'root', '', 'diplom') or die(mysqli_error($db));
mysqli_set_charset($db, 'utf8');
error_reporting(E_ERROR | E_PARSE);
?>