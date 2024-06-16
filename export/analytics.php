<?php 
session_start();
require_once('../require/db.php');
require_once('../phpexcel/SimpleXLSXGen.php');

$dateHash = md5(date('d-m-Y-His'));

$exportPeriod = 'Выгрузка данных с '.$_SESSION['dateFrom'].' по '.$_SESSION['dateBefore'];

$query = mysqli_query($db, "
    SELECT 
        c.date as sale_date,
        s.name as staff_name,
        SUM(ir.count_product * ir.sale_price) as sum_sale_price,
        SUM(ir.count_product * ir.purchase_price) as sum_purchase_price,
        SUM(c.staff_percentage_product_sales * ir.count_product * ir.sale_price / 100) as total_percentage_product_sales,
        (SELECT IFNULL(SUM(s.wages), 0) FROM staff s) as total_wages,
        (
            IFNULL(SUM(ir.count_product * ir.sale_price), 0) - 
            IFNULL(SUM(c.staff_percentage_product_sales * ir.count_product * ir.sale_price / 100), 0) - 
            IFNULL(SUM(ir.count_product * ir.purchase_price), 0) - 
            IFNULL((SELECT SUM(s.wages) FROM staff s), 0)
        ) as profit
    FROM `cheque` c
    LEFT JOIN `interim_receipt` ir ON c.id = ir.cheque_id 
    LEFT JOIN `staff` s ON s.id = ir.staff_id
    WHERE c.date >= '".$_SESSION['dateFrom']."' AND c.date <= '".$_SESSION['dateBefore']." 23:59:59' AND c.status = 'approved' AND s.role != 'owner'
    GROUP BY c.date, s.name
");

$books = [];
$books[] = [$exportPeriod]; 

$books[] = ['Дата продажи', 'Продавец', 'Сумма продажи', 'Сумма покупки', 'Общий процент продаж продукции', 'Общая зарплата', 'Прибыль'];

while ($row = mysqli_fetch_assoc($query)) { 
    $books[] = [
        $row['sale_date'],
        $row['staff_name'],
        $row['sum_sale_price'],
        $row['sum_purchase_price'],
        $row['total_percentage_product_sales'],
        $row['total_wages'],
        $row['profit']
    ];
}

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('../documents/'.$dateHash.'.xlsx');
$xlsx->downloadAs('Аналитика - '.$dateHash.'.xlsx');

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
