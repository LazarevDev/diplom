<?php
require_once('require/db.php');

if(isset($_POST['submit'])){
    $phone = $_POST['phone'];
    $password = md5($_POST['password']);

    $query = mysqli_query($db, "SELECT * FROM `staff` WHERE `phone` = '$phone' and `password` = '$password'");
    $result = mysqli_fetch_array($query);

    if(!empty($result['phone'])){
        setcookie('phone', $phone);
        setcookie('password', $password);

        header('Location: profile');
        exit;
    }else{
        header('Location: login.php?success=error_auth');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/login.css">    
    <script src="js/imask.js"></script>
    <title>Авторизация</title>
</head>
<body>
    <section class="login">
        <div class="loginForm">
            <form action="" method="post" class="formLogin">
                <h2>Авторизация</h2>
                <input type="text" name="phone" id="phoneInput" placeholder="Введите номер телефона" required><br>
                <input type="password" name="password" id="phoneInput" placeholder="Введите пароль" required><br>
                <input type="submit" class="submitLogin" name="submit" value="Войти">
            </form>
        </div>
    </section>
    <script>
        var phoneInput = document.getElementById('phoneInput');
        var maskOptions = {
            mask: '+{7} 000 000 00-00',
        };
        var mask = IMask(phoneInput, maskOptions);
    </script>

    <?php require_once('require/success.php'); ?>
</body>
</html>