<?php 
session_start();
require_once('../require/db.php');
require_once('../phpexcel/SimpleXLSXGen.php');

require_once('../functions/position.php');

$dateHash = md5(date('d-m-Y-His'));

$query = mysqli_query($db, "SELECT * FROM `clientele` ORDER BY `id` DESC");

$books = [];

$books[] = ['Название', 'Тип клиента', 'Телефон', 'Адрес'];

while ($row = mysqli_fetch_assoc($query)) { 
    $books[] = [
        $row['buyer'],
        type($row['type']),
        $row['phone'],
        $row['address']
    ];
}

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('../documents/'.$dateHash.'.xlsx');
$xlsx->downloadAs('Все клиенты - '.$dateHash.'.xlsx');

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
