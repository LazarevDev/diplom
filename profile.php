<?php 
require_once('require/db.php');
require_once('require/access.php');
require_once('functions/edit.php');
require_once('functions/position.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="css/profile.css">
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

            <div class="contentWhite contentInfo">
                <div class="contentTitle">
                    <h2>Все продажи</h2>
                </div>

                <??>
            
                <div class="accountingEmpty">
                    <div>
                        <h2>Чек пустой</h2>
                        <p>Добавьте товар</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require_once('require/success.php'); ?>

    <script>
        var phoneInput = document.getElementById('phoneInput');
        var maskOptions = {
            mask: '+{7} 000 000 00-00',
        };
        var mask = IMask(phoneInput, maskOptions);
</script>
</body>
</html>