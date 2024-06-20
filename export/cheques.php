<?php 
session_start();
require_once('../require/db.php');
require_once('../phpexcel/SimpleXLSXGen.php');

$dateHash = md5(date('d-m-Y-His'));

$exportPeriod = 'Все отчеты';

$query = mysqli_query($db, "SELECT *, (SELECT SUM(sale_price * count_product) as sumPrice FROM `interim_receipt` WHERE `cheque_id` = cheque.id) as `sale_price` FROM `cheque` ORDER BY id DESC");

$books = [];
$books[] = [$exportPeriod]; 



$books[] = ['ID', 'Название компании', 'Дата', 'Цена отчета', 'Статус', 
'Название организации/покупателя', 'Тип', 'Телефон', 'Адрес', 'Информация о продавце', 'Телефон', 'Адрес'];

while ($row = mysqli_fetch_assoc($query)) { 
    $books[] = [
        $row['id'],
        $row['buyer'],
        $row['date'],
        number_format($row['sale_price']),
        $row['status'] == 'approved' ? 'Оплачен' : 'Отменен',
        $row['buyer'],
        $row['buyer_type'],
        $row['phone'],
        $row['address'],
        $row['staff_name'],
        $row['staff_phone'],
        $row['staff_address']
    ];
} 

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('../documents/'.$dateHash.'.xlsx');
$xlsx->downloadAs('Статистика персонала - '.$dateHash.'.xlsx');

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
