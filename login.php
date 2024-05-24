<?php
require_once('require/db.php');

if(isset($_POST['submit'])){
    $login = $_POST['login'];
    $password = $_POST['password'];

    $query = mysqli_query($db, "SELECT * FROM `staff` WHERE `login` = '$login' and `password` = '$password'");
    $result = mysqli_fetch_array($query);

    if(!empty($result)){
        setcookie('login', $login);
        setcookie('password', $password);

        header('Location: index.php');
        exit;
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
    <title>Авторизация</title>
</head>
<body>
    <section class="login">
        <div class="loginForm">
            <form action="" method="post" class="formLogin">
                <h2>Авторизация</h2>
                <input type="text" name="login" placeholder="Введите логин"><br>
                <input type="password" name="password" placeholder="Введите пароль"><br>
                <input type="submit" class="submitLogin" name="submit" value="Войти">
            </form>
        </div>
    </section>
</body>
</html>