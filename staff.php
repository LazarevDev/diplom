<?php 
require_once('require/db.php');
require_once('functions/edit.php');
require_once('functions/photo.php');
require_once('functions/check_photo.php');
$arrayEdit = [
    'photo' => null,
    'name' => null,
    'role' => null,
    'wages' => null,
    'percentage_product_sales' => null,
    'phone' => null,
    'address' => null,
    'password' => null,
];

if(isset($_GET['delete'])){
    $deleteId = $_GET['delete'];

    $queryDelete = mysqli_query($db, "DELETE FROM `staff` WHERE `id` = '$deleteId'");
    header('Location: staff.php?success=delete');
    exit;
}

if(isset($_GET['edit'])){
    $editID = $_GET['edit'];
    
    $queryEdit = mysqli_query($db, "SELECT * FROM `staff` WHERE `id` = '$editID'");
    $resultEdit = mysqli_fetch_array($queryEdit);

    $arrayEdit = [
        'photo' => $resultEdit['photo'],
        'name' => $resultEdit['name'],
        'role' => $resultEdit['role'],
        'wages' => $resultEdit['wages'],
        'percentage_product_sales' => $resultEdit['percentage_product_sales'],
        'phone' => $resultEdit['phone'],
        'address' => $resultEdit['address'],
        'password' => $resultEdit['password']
    ];

    if(isset($_POST['submit'])){
        $photo = $_FILES['photo']['name'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $wages = $_POST['wages'];
        $percentage_product_sales = $_POST['percentage_product_sales'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        if(!empty($_POST['password'])){
            $password = md5($_POST['password']);

            $querySuppliers = mysqli_query($db, "UPDATE `staff` SET `password` = '$password' WHERE `id` = '$editID'");
        }

        $querySuppliers = mysqli_query($db, "UPDATE `staff` SET `name` = '$name', `role` = '$role', `wages` = '$wages', `percentage_product_sales` = '$percentage_product_sales', `phone` = '$phone', `address` = '$address' WHERE `id` = '$editID'");
        
        photo($photo, 'photo','staff', $db, $arrayEdit['photo']);

        header('Location: staff.php?success=update');
        exit;
    }
}else{
    if(isset($_POST['submit'])){
        $photo = $_FILES['photo']['name'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $wages = $_POST['wages'];
        $percentage_product_sales = $_POST['percentage_product_sales'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = md5($_POST['password']);

        $queryAddStaff = "INSERT INTO `staff` (`name`, `role`, `wages`, `percentage_product_sales`, `phone`, `address`, `password`) VALUES 
        ('$name', '$role', '$wages', '$percentage_product_sales', '$phone', '$address', '$password')";
        $resultAddStaff = mysqli_query($db, $queryAddStaff) or die(mysqli_error($db));

        photo($photo, 'photo','staff', $db, $arrayEdit['photo']);

        header('Location: staff.php?success=upload');
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
    <link rel="stylesheet" href="css/staff.css">

    <script src="js/imask.js"></script>
    <script src="js/chart.js"></script>
    <title>Персонал</title>
</head>
<body>
    <?php require_once('require/panel.php'); ?>

    <div class="container">
        <div class="pageTitle">
            <h1>Персонал</h1>
        </div>
        
        <div class="content spaceBetween" style="padding: 0;">
            <div class="contentWhite contentInfo">
                <div class="tableContent" style="margin: 0;">
                    <table border="1">
                        <tr>
                            <th>Фамилия имя</th>
                            <th>Должность</th>
                            <th>Доход</th>
                            <th>ЗП</th>
                            <th>Действия</th>
                        </tr>

                        <?php $queryRow = mysqli_query($db, "SELECT * FROM `staff` ORDER BY `id` DESC");
                        while($row = mysqli_fetch_array($queryRow)): ?>
                            <tr>
                                <td>
                                    <a href="profile/<?=$row['id']?>" class="userTable">
                                        <div class="userTableImg">
                                            <?=checkPhoto('staff', $row['id'], $row['photo']); ?>
                                        </div>

                                        <div class="userTableName">
                                            <h2><?=$row['name']?></h2>
                                            <p><?=$row['phone']?></p>
                                        </div>
                                    </a>
                                </td>
                                <td><?=$row['role']?></td>
                                <td>-</td>
                                <td><?=$row['wages']?>Руб./<?=$row['percentage_product_sales']?>%</td>
                                <td>
                                    <a href="staff?edit=<?=$row['id']?>" class="tdAction">
                                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.1736 13.0336L19.9336 18.3136C19.9884 18.7201 19.8648 19.1305 19.5946 19.4391C19.3243 19.7477 18.9338 19.9244 18.5236 19.9236H18.3136L13.0336 19.1636C12.3179 19.0649 11.6541 18.7348 11.1436 18.2236L1.40356 8.47356C-0.491301 6.51167 -0.464202 3.39314 1.46447 1.46447C3.39314 -0.464202 6.51167 -0.491301 8.47356 1.40356L18.2336 11.1436C18.7448 11.6541 19.0749 12.3179 19.1736 13.0336ZM12.2536 17.1636C12.5301 17.4292 12.8773 17.6097 13.2536 17.6836L18.4636 18.3736L17.7336 13.2036C17.6597 12.8273 17.4792 12.4801 17.2136 12.2036L7.46356 2.46356C6.80662 1.78913 5.90506 1.40874 4.96356 1.40874C4.02206 1.40874 3.1205 1.78913 2.46356 2.46356C1.80464 3.11864 1.43414 4.00942 1.43414 4.93856C1.43414 5.8677 1.80464 6.75848 2.46356 7.41356L12.2536 17.1636Z"/>
                                            <path d="M5.43356 4.37356C5.13805 4.0982 4.67755 4.10633 4.39194 4.39194C4.10633 4.67755 4.0982 5.13805 4.37356 5.43356L8.58356 9.64356C8.87638 9.93601 9.35074 9.93601 9.64356 9.64356C9.93602 9.35074 9.93602 8.87638 9.64356 8.58356L5.43356 4.37356Z" />
                                        </svg>
                                    </a>

                                    <a href="#" onclick="showDeleteConfirmation('staff?delete=<?=$row['id']?>')" class="tdAction">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.1665 12.008L20.762 4.43822C21.0793 4.11791 21.0793 3.599 20.762 3.27868C20.4503 2.95264 19.9355 2.94285 19.6118 3.2568L12.0163 10.8266L4.51839 3.2568C4.36467 3.09288 4.15078 3 3.92702 3C3.70327 3 3.48938 3.09288 3.33566 3.2568C3.0543 3.56628 3.0543 4.04123 3.33566 4.35071L10.8335 11.9096L3.238 19.4685C2.92067 19.7888 2.92067 20.3077 3.238 20.628C3.38907 20.784 3.59685 20.871 3.81309 20.8687C4.03351 20.8867 4.25202 20.8159 4.42074 20.6718L12.0163 13.102L19.6118 20.7593C19.7629 20.9153 19.9707 21.0022 20.1869 21C20.4029 21.001 20.6102 20.9142 20.762 20.7593C21.0793 20.439 21.0793 19.9201 20.762 19.5998L13.1665 12.008Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>

            <div class="contentWhite contentForm">
                <div class="contentTitle">
                    <h2>Добавить сотрудника</h2>
                </div>

                <form action="" method="post" class="form" enctype="multipart/form-data">
                    <div class="formPhoto">
                        <label class="photoCircle" id="photoCircle" for="fileInput" >
                            <?php if(empty($arrayEdit['photo'])): ?>
                                <img id="photoPreview" src="../img/holder.jpg">
                            <?php else: ?>
                                <img id="photoPreview" src="../img/staff/<?=$editID?>/<?=$arrayEdit['photo']?>">
                            <?php endif; ?>
                        </label>
                        <input type="file" id="fileInput" name="photo" accept="image/*">
                    </div>

                    <input type="text" name="name" class="input" placeholder="Имя и фамилия" <?php edit('input', $arrayEdit['name']); ?> required>
                    <select name="role" class="select" required>
                        <option value="">Должность</option>
                        <option value="seller" <?php edit('select', $arrayEdit['role'], 'seller'); ?>>Продавец</option>
                        <option value="manager" <?php edit('select', $arrayEdit['role'], 'manager'); ?>>Менеджер</option>
                    </select>

                    <input type="text" name="wages" class="input" placeholder="Фиксированная заработная плата сотрудника в месяц" <?php edit('input', $arrayEdit['wages']); ?> required>
                    <input type="number" name="percentage_product_sales" class="input" placeholder="Процент от проданного товара" <?php edit('input', $arrayEdit['percentage_product_sales']); ?>>
                    <input type="text" name="phone" class="input" id="phoneInput" placeholder="Номер тел." <?php edit('input', $arrayEdit['phone']); ?> required>
                    <input type="text" name="address" class="input" placeholder="Адрес" id="addressInput" <?php edit('input', $arrayEdit['address']); ?> required>
                    <input type="password" name="password" class="input" placeholder="Пароль" <?php if(!isset($_GET['edit'])){ echo 'required'; } ?>>

                    <input type="submit" name="submit" class="btn" value="Загрузить">
                </form>
            </div>
        </div>
    </div>
    
    <?php require_once('require/success.php'); ?>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('photoPreview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        var phoneInput = document.getElementById('phoneInput');
        var maskOptions = {
            mask: '+{7} 000 000 00-00',
        };
        var mask = IMask(phoneInput, maskOptions);
</script>
</body>
</html>