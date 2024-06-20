<?php 
require_once('require/db.php');
require_once('require/access.php');
require_once('functions/edit.php');
require_once('functions/position.php');

$arrayEdit = [
    'buyer' => null,
    'type' => null,
    'phone' => null,
    'address' => null,
];

if(isset($_GET['delete'])){
    $deleteId = $_GET['delete'];

    $queryDelete = mysqli_query($db, "DELETE FROM `clientele` WHERE `id` = '$deleteId'");
    header('Location: clientele?success=delete');
    exit;
}

if(isset($_GET['edit'])){
    $editID = $_GET['edit'];
    
    $queryEdit = mysqli_query($db, "SELECT * FROM `clientele` WHERE `id` = '$editID'");
    $resultEdit = mysqli_fetch_array($queryEdit);

    $arrayEdit = [
        'buyer' => $resultEdit['buyer'],
        'type' => $resultEdit['type'],
        'phone' => $resultEdit['phone'],
        'address' => $resultEdit['address'],
    ];

    if(isset($_POST['submit'])){
        $buyer = $_POST['buyer'];
        $type = $_POST['type'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $queryEditNew = mysqli_query($db, "UPDATE `clientele` SET 
        `buyer` = '$buyer', 
        `type` = '$type', 
        `phone` = '$phone', 
        `address` = '$address' WHERE `id` = '$editID'");

        header('Location: clientele?success=update');
        exit;
    }
}else{
    if(isset($_POST['submit'])){
        $buyer = $_POST['buyer'];
        $type = $_POST['type'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $queryAdd = "INSERT INTO `clientele` (`buyer`, `type`, `phone`, `address`) VALUES 
        ('$buyer', '$type', '$phone', '$address')";
        $resultAdd = mysqli_query($db, $queryAdd) or die(mysqli_error($db));

        header('Location: clientele?success=upload');
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="css/accounting.css">
    <script src="js/chart.js"></script>
    <script src="js/imask.js"></script>
    <title>Клиенты</title>
</head>
<body>

   <?php require_once('require/panel.php'); ?>

    <div class="container">
        <div class="pageTitle">
            <h1>Клиенты</h1>
        </div>
        
        <div class="content spaceBetween" style="padding: 0;">
            <div>
                <div class="contentWhite contentForm">
                    <div class="contentTitle">
                        <h2>Добавить клиента</h2>
                    </div>

                    <form action="" method="post" class="form">
                        <input type="text" class="input" name="buyer" placeholder="Название компании или ФИО" <?php edit('input', $arrayEdit['buyer']); ?> required>
                        
                        <select name="type" class="select" required>
                            <option value="">Форма организации</option>
                            <option value="individuals" <?php edit('select', $arrayEdit['type'], 'individuals'); ?>>Физ.лицо</option>
                            <option value="llc" <?php edit('select', $arrayEdit['type'], 'llc'); ?>>ООО</option>
                            <option value="ep" <?php edit('select', $arrayEdit['type'], 'ep'); ?>>ИП</option>
                        </select>

                        <input type="text" name="phone" class="input" id="phoneInput" placeholder="Номер тел." <?php edit('input', $arrayEdit['phone']); ?> required>
                        <input type="text" name="address" class="input" id="phoneInput" placeholder="Адрес" <?php edit('input', $arrayEdit['address']); ?> required>
                        
                    
                        <input type="submit" name="submit" class="btn" value="Добавить">
                    </form>
                </div>
            </div>

            <div class="contentWhite contentInfo">
                <div class="contentTitle">
                    <h2>Все клиенты</h2>

                    <a href="export/clientele   " class="btnOutline">Экспорт в excel</a>
                </div>

                <?php $queryСlienteleCheck = mysqli_query($db, "SELECT * FROM `clientele`");
                if(mysqli_num_rows($queryСlienteleCheck) > 0): ?>

                    <div class="tableContent">
                        <table border="1">
                            <tr>
                                <th>Название</th>
                                <th>Тип клиента</th>
                                <th>Телефон</th>
                                <th>Адрес</th>
                                <th>Действия</th>
                            </tr>

                            <?php $queryRow = mysqli_query($db, "SELECT * FROM `clientele` ORDER BY `id` DESC");
                            while($rowRow = mysqli_fetch_array($queryRow)): ?>
                                <tr>
                                    <td><?=$rowRow['buyer']?></td>
                                    <td><?=type($rowRow['type']);?></td>
                                    <td><?=$rowRow['phone']?></td>
                                    <td><?=$rowRow['address']?></td>
                                    <td>
                                        <a href="clientele?edit=<?=$rowRow['id']?>" class="tdAction">
                                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.1736 13.0336L19.9336 18.3136C19.9884 18.7201 19.8648 19.1305 19.5946 19.4391C19.3243 19.7477 18.9338 19.9244 18.5236 19.9236H18.3136L13.0336 19.1636C12.3179 19.0649 11.6541 18.7348 11.1436 18.2236L1.40356 8.47356C-0.491301 6.51167 -0.464202 3.39314 1.46447 1.46447C3.39314 -0.464202 6.51167 -0.491301 8.47356 1.40356L18.2336 11.1436C18.7448 11.6541 19.0749 12.3179 19.1736 13.0336ZM12.2536 17.1636C12.5301 17.4292 12.8773 17.6097 13.2536 17.6836L18.4636 18.3736L17.7336 13.2036C17.6597 12.8273 17.4792 12.4801 17.2136 12.2036L7.46356 2.46356C6.80662 1.78913 5.90506 1.40874 4.96356 1.40874C4.02206 1.40874 3.1205 1.78913 2.46356 2.46356C1.80464 3.11864 1.43414 4.00942 1.43414 4.93856C1.43414 5.8677 1.80464 6.75848 2.46356 7.41356L12.2536 17.1636Z"/>
                                                <path d="M5.43356 4.37356C5.13805 4.0982 4.67755 4.10633 4.39194 4.39194C4.10633 4.67755 4.0982 5.13805 4.37356 5.43356L8.58356 9.64356C8.87638 9.93601 9.35074 9.93601 9.64356 9.64356C9.93602 9.35074 9.93602 8.87638 9.64356 8.58356L5.43356 4.37356Z" />
                                            </svg>
                                        </a>

                                        <a href="clientele?delete=<?=$rowRow['id']?>" class="tdAction">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.1665 12.008L20.762 4.43822C21.0793 4.11791 21.0793 3.599 20.762 3.27868C20.4503 2.95264 19.9355 2.94285 19.6118 3.2568L12.0163 10.8266L4.51839 3.2568C4.36467 3.09288 4.15078 3 3.92702 3C3.70327 3 3.48938 3.09288 3.33566 3.2568C3.0543 3.56628 3.0543 4.04123 3.33566 4.35071L10.8335 11.9096L3.238 19.4685C2.92067 19.7888 2.92067 20.3077 3.238 20.628C3.38907 20.784 3.59685 20.871 3.81309 20.8687C4.03351 20.8867 4.25202 20.8159 4.42074 20.6718L12.0163 13.102L19.6118 20.7593C19.7629 20.9153 19.9707 21.0022 20.1869 21C20.4029 21.001 20.6102 20.9142 20.762 20.7593C21.0793 20.439 21.0793 19.9201 20.762 19.5998L13.1665 12.008Z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="accountingEmpty">
                        <div>
                            <h2>Отчет пустой</h2>
                            <p>Добавьте товар</p>
                        </div>
                    </div>
                <?php endif; ?>
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