<?php 
session_start();
require_once('../require/db.php');
require_once('../phpexcel/SimpleXLSXGen.php');

$dateHash = md5(date('d-m-Y-His'));

$exportPeriod = 'Данные о персонале';

$query = mysqli_query($db, "SELECT * FROM `staff` WHERE `role` != 'owner' ORDER BY `id` DESC");

$books = [];
$books[] = [$exportPeriod]; 

$books[] = ['Имя и фамилия', 'Фиксированная заработная плата в месяц', 'Процент от проданного товара', 'Номер тел.', 'Адрес'];

while ($row = mysqli_fetch_assoc($query)) { 
    $books[] = [
        $row['staff_name'],
        number_format($row['total_percentage_product_sales'])." ₽",
        number_format($row['profit'])." ₽"
    ];
}

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('../documents/'.$dateHash.'.xlsx');
$xlsx->downloadAs('Статистика персонала - '.$dateHash.'.xlsx');

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
