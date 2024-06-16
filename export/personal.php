<?php 
session_start();
require_once('../require/db.php');
require_once('../phpexcel/SimpleXLSXGen.php');

$dateHash = md5(date('d-m-Y-His'));

$exportPeriod = 'Выгрузка данных с '.$_SESSION['dateFrom'].' по '.$_SESSION['dateBefore'];

$query = mysqli_query($db, "
    SELECT
    s.id AS staff_id,
    s.name AS staff_name,
    s.photo AS photo,
    s.role AS role,
    
    IFNULL(SUM(CASE WHEN c.status = 'approved' THEN ir.count_product * ir.sale_price ELSE 0 END), 0) AS sum_sale_price,
    IFNULL(SUM(CASE WHEN c.status = 'approved' THEN c.staff_percentage_product_sales * ir.count_product * ir.sale_price / 100 ELSE 0 END), 0) AS total_percentage_product_sales,
    (
        IFNULL(SUM(CASE WHEN c.status = 'approved' THEN ir.count_product * ir.sale_price ELSE 0 END), 0) - 
        IFNULL(SUM(CASE WHEN c.status = 'approved' THEN c.staff_percentage_product_sales * ir.count_product * ir.sale_price / 100 ELSE 0 END), 0) - 
        IFNULL(SUM(CASE WHEN c.status = 'approved' THEN ir.count_product * ir.purchase_price ELSE 0 END), 0) - 
        s.wages
    ) AS profit
        FROM
    staff s
        LEFT JOIN
    interim_receipt ir ON s.id = ir.staff_id
        LEFT JOIN
    cheque c ON c.id = ir.cheque_id AND c.date >= '".$_SESSION['dateFrom']."' AND c.date <= '".$_SESSION['dateBefore']." 23:59:59'
        WHERE s.role != 'owner'
        GROUP BY s.id, s.name
    ORDER BY s.id;
");

$books = [];
$books[] = [$exportPeriod]; 

$books[] = ['Имя', 'Продажи', 'Чистый доход'];

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
