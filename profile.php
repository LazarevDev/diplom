<?php 
require_once('require/db.php');
require_once('require/access.php');
require_once('functions/edit.php');
require_once('functions/position.php');

if(isset($_GET['id'])){
    $idProfile = $_GET['id'];
}else{
    $idProfile = $resultStaff['id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/cheques.css">

    <script src="js/chart.js"></script>
    <script src="js/imask.js"></script>
    <title>Профиль</title>
</head>
<body>

   <?php require_once('require/panel.php'); ?>

    <div class="container">
        <div class="pageTitle">
            <h1>Профиль</h1>
        </div>
        
        <div class="content spaceBetween" style="padding: 0;">
            <div>
                <div class="contentWhite contentForm">
                    <div class="profileContent">
                        <div class="profileImg">
                            <?=checkPhoto('staff', $resultStaff['id'], $resultStaff['photo'])?>
                        </div>

                        <div class="profileText">
                            <h2><?=$resultStaff['name']?></h2>
                            <p>Должность: <?=position($resultStaff['role'])?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contentWhite contentInfo">
                <div class="contentTitle">
                    <h2>Все продажи</h2>
                </div>

                <div class="tableContent" style="margin: 0;">
                    <table border="1">
                        <tr>
                            <th>№-чека</th>
                            <th>Название компании</th>
                            <th>Дата</th>
                            <th>Цена чека</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>

                        <?php $queryRow = mysqli_query($db, "SELECT *, (SELECT SUM(sale_price * count_product) as sumPrice FROM `interim_receipt` WHERE `cheque_id` = cheque.id) as `sale_price` FROM `cheque` WHERE `staff_id` = '$idProfile' ORDER BY id DESC");
                        while($row = mysqli_fetch_array($queryRow)): ?>
                            <tr>
                                <td>№<?=$row['id']?></td>
                                <td><?=$row['buyer']?></td>
                                <td><?=$row['date']?></td>
                                <td><?=number_format($row['sale_price'])?> Руб.</td>
                                <td>
                                    <span id="status-<?=$row['id']?>" class="status <?=$row['status'] == 'approved' ? 'statusApproved' : 'statusCancelled'?>">
                                        <?=$row['status'] == 'approved' ? 'Оплачен' : 'Отменен'?>
                                    </span>
                                </td>
                                <td>
                                    <p class="more" onclick="toggleDetails('details-<?=$row['id']?>')">Подробнее</p>
                                </td>
                            </tr>

                            <tr id="details-<?=$row['id']?>" class="detailsRow">
                                <td class="detailsContentTr" colspan="6">
                                    <div class="detailsContent">
                                        <h3>Дополнительная информация о чеке: №<?=$row['id']?></h3>

                                        <div class="detailContentBlocks">
                                            <div class="detailsInfoContainer">
                                                <div class="detailBlock">
                                                    <h2>Информация о клиенте</h2>

                                                    <p>Название организации/покупателя: <?=$row['buyer'];?> </p>
                                                    <p>Тип: <?=$row['buyer_type'];?> </p>
                                                    <p>Телефон: <?=$row['phone'];?> </p>
                                                    <p>Адрес: <?=$row['address'];?> </p>
                                                </div>

                                                <div class="detailBlock">
                                                    <h2>Информация о продавце</h2>

                                                    <p>Имя: <?=$row['staff_name'];?> </p>
                                                    <p>Телефон: <?=$row['staff_phone'];?> </p>
                                                    <p>Адрес: <?=$row['staff_address'];?> </p>
                                                </div>
                                            </div>

                                            <div class="detailsInfoPrice">
                                                <!-- <div class="detailsInfoPriceBlock">
                                                    <p>Название</p>
                                                    <p>Цена</p>
                                                </div> -->
                                            </div>
                                        </div>                                  
                                
                                        <select onchange="updateStatus(<?=$row['id']?>, this.value)">
                                            <option value="approved" <?=$row['status'] == 'approved' ? 'selected' : ''?>>Оплачен</option>
                                            <option value="cancelled" <?=$row['status'] == 'cancelled' ? 'selected' : ''?>>Отменен</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <?php require_once('require/success.php'); ?>

    <script src="js/toggleDetails.js"></script>

    <script>
        var phoneInput = document.getElementById('phoneInput');
        var maskOptions = {
            mask: '+{7} 000 000 00-00',
        };
        var mask = IMask(phoneInput, maskOptions);
</script>
</body>
</html>