<?php 
session_start();

require_once('require/db.php');
require_once('require/access.php');
require_once('functions/check_photo.php');
require_once('functions/position.php');

// $dateFrom - от
// $dateBefore  - до

if(empty($_SESSION['dateFrom'])){
    $_SESSION['dateFrom'] = date('Y-m-d', strtotime('-1 month'));
    $dateFrom = $_SESSION['dateFrom'];
}

if(empty($_SESSION['dateBefore'])){
    $_SESSION['dateBefore'] = date('Y-m-d');
    $dateBefore = $_SESSION['dateBefore'];
}

if(isset($_POST['submit'])){
    $dateFromInput = $_POST['dateFrom'];
    $dateBeforeInput = $_POST['dateBefore'];

    $_SESSION['dateFrom'] = $dateFromInput;
    $_SESSION['dateBefore'] = $dateBeforeInput;

    header('Location: index');
}


$queryTurnover = mysqli_query($db, "SELECT 
    SUM(interim_receipt.count_product * interim_receipt.sale_price) as `sum_sale_price`,
    SUM(interim_receipt.count_product * interim_receipt.purchase_price) as `sum_purchase_price`,
    SUM(cheque.staff_percentage_product_sales * interim_receipt.count_product * interim_receipt.sale_price / 100) as `total_percentage_product_sales`,
    (SELECT IFNULL(SUM(wages), 0) FROM staff) as total_wages,
    (
        IFNULL(SUM(interim_receipt.count_product * interim_receipt.sale_price), 0) - 
        IFNULL(SUM(cheque.staff_percentage_product_sales * interim_receipt.count_product * interim_receipt.sale_price / 100), 0) - 
        IFNULL(SUM(interim_receipt.count_product * interim_receipt.purchase_price), 0) - 
        IFNULL((SELECT SUM(wages) FROM staff), 0)
    ) as profit

    FROM `cheque`
    LEFT JOIN `interim_receipt` ON cheque.id = interim_receipt.cheque_id 
    LEFT JOIN `staff` ON staff.id = interim_receipt.staff_id
    WHERE cheque.date >= DATE('".$_SESSION['dateFrom']."') AND  cheque.date <= DATE('".$_SESSION['dateBefore']."') AND cheque.status = 'approved'" );

$resultTurnover = mysqli_fetch_array($queryTurnover);

$queryStaffWages = mysqli_query($db, "SELECT
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
                cheque c ON c.id = ir.cheque_id AND c.date >= DATE('".date('Y-m')."-01 00:00:00')
                    GROUP BY s.id, s.name
                    ORDER BY s.id;
");
while($rowStaffWages = mysqli_fetch_array($queryStaffWages)){
    $staffArray[] = [
            'staff_id' => $rowStaffWages['staff_id'],
            'photo' => $rowStaffWages['photo'],
            'name' => $rowStaffWages['staff_name'],
            'role' => $rowStaffWages['role'],

            'total_percentage_product_sales' => $rowStaffWages['total_percentage_product_sales'],
            'profit' => $rowStaffWages['profit'],
        ];

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="js/chart.js"></script>

    <title>Главная</title>
</head>
<body>

    <?php require_once('require/panel.php'); ?>

    <div class="container">
        <div class="pageTitle">
            <h1>Аналитика</h1>
        
            <?php echo $_SESSION['dateFrom']." ".$_SESSION['dateBefore']; ?>
        
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="modalContentHead">
                    <h2>Выбрать дату продаж</h2>
                    <span class="close">&times;</span>
                </div>

                <form class="modalContentForm" method="post">

                    <div class="modalInputLabel">
                        <label for="fromDate">От:</label>
                        <input type="date" id="fromDate" name="dateFrom" required>
                    </div>

                    <div class="modalInputLabel">
                        <label for="toDate">До:</label>
                        <input type="date" id="toDate" name="dateBefore" required>
                    </div>
                  
                    <input type="submit" class="btn" name="submit" value="Применить">
                </form>
            </div>
        </div>


        <div class="content contentWhite">
            <div class="contentTitle">
                <h2>Статистика продаж за месяц</h2>
            </div>

            <div class="spaceBetween salesContainer">
                <div class="saleData">
                    <div class="saleDataTitle">
                        <h2>Оборот</h2>
                        <p><?=number_format($resultTurnover['sum_sale_price'])?> Руб.</p>
                    </div>
                    
                    <ul>
                        <li>Расходы на товар: <?=number_format($resultTurnover['sum_purchase_price'])?> Руб.</li>
                        <li>Расходы на сотрудников: <?php echo number_format($resultTurnover['total_percentage_product_sales'] + $resultTurnover['total_wages']); ?> Руб.</li>
                        <li>Чистая прибыль: <?=number_format($resultTurnover['profit'])?> Руб.</li>
                        
                        <a href="javascript:void(0);" id="openModalBtn" class="btn">Выбрать дату продаж</a>
                    </ul>
                </div>
                <div class="doughnutChart">
                    <canvas id="myDoughnutChart"></canvas>
                </div>
            </div>
          
            <div class="contentTitle" style="margin-top: 60px;">
                <h2>Статистика сотрудников за месяц </h2>
            </div>
            <div class="spaceBetween barChartContainer">
                <div class="barChart">
                    <canvas id="myBarChart"></canvas>
                </div>

                <div class="barTable">
                    <table border="1">
                        <tr>
                            <th>Имя</th>
                            <th>Продажи</th>
                            <th>Чистый доход</th>
                        </tr>

                        <?php foreach ($staffArray as $value) { ?>
                            <tr>
                                <td>
                                <a href="profile/<?=$value['staff_id']?>" class="userTable">
                                        <div class="userTableImg">
                                            <?=checkPhoto('staff', $value['staff_id'], $value['photo']); ?>
                                        </div>

                                        <div class="userTableName">
                                            <h2><?=$value['name']?></h2>
                                            <p><?=position($value['role'])?></p>
                                        </div>
                                    </a>
                                </td>
                                <td><?=$value['total_percentage_product_sales']?></td>
                                <td><?=$value['profit']?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Преобразуем PHP массив в JSON строку
        const staffArray = <?=json_encode($staffArray)?>;

        // Извлекаем только имена сотрудников для использования в гистограмме
        const staffNames = staffArray.map(staff => staff.name);
        const staffProfit = staffArray.map(staff => staff.profit);

        const centerTextPlugin = {
            id: 'centerText',
            afterDatasetsDraw(chart, args, options) {
                if (chart.config.type === 'doughnut') {
                    const { ctx, chartArea: { width, height } } = chart;
                    ctx.save();
                    ctx.font = 'bold 20px Roboto';
                    ctx.fillStyle = '#FF8A00';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('Оборот', width / 2, height / 2 - 10);
                    ctx.font = 'bold 16px Roboto';
                    ctx.fillText('<?=number_format($resultTurnover['sum_sale_price'])?>', width / 2, height / 2 + 10);
                    ctx.restore();
                }
            }
        };
        
        Chart.register(centerTextPlugin);
        
        // Пончиковый график
        const ctxDoughnut = document.getElementById('myDoughnutChart').getContext('2d');
        const dataDoughnut = {
            labels: [
                'Расходы на товар',
                'Расходы на сотрудников',
                'Чистая прибыль'
            ],
            datasets: [{
                label: 'Данные',
                data: [
                    <?=$resultTurnover['sum_purchase_price']?>, 
                    <?php echo $resultTurnover['total_percentage_product_sales'] + $resultTurnover['total_wages']; ?>, 
                    <?=$resultTurnover['profit']?>
                ],
                backgroundColor: [
                    '#e66f00',
                    '#e68200',
                    '#FF8A00',
                ],
                borderWidth: 1
            }]
        };
        const configDoughnut = {
            type: 'doughnut',
            data: dataDoughnut,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        display: false,
                    },
                    tooltip: {
                        enabled: true
                    },
                    centerText: {
                        display: true
                    }
                }
            }
        };
        const myDoughnutChart = new Chart(ctxDoughnut, configDoughnut);

        // Гистограмма
        const ctxBar = document.getElementById('myBarChart').getContext('2d');
        const dataBar = {
            labels: staffNames,
            datasets: [{
                label: 'Цена',
                data: staffProfit,  // Данные, соответствующие сотрудникам
                backgroundColor: '#FF8A00',
                borderWidth: 1
            }]
        };
        const configBar = {
            type: 'bar',
            data: dataBar,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        };
        const myBarChart = new Chart(ctxBar, configBar);
    </script>

    <script src="js/modal.js"></script>

</body>
</html>