<?php 

if(!empty($_COOKIE['phone']) AND !empty($_COOKIE['password'])){
    $phoneCookie = $_COOKIE['phone'];
    $passwordCookie = $_COOKIE['password'];
    
    $queryStaff = mysqli_query($db, "SELECT * FROM `staff` WHERE `phone` = '$phoneCookie' AND `password` = '$passwordCookie'");
    $resultStaff = mysqli_fetch_array($queryStaff);

    if(empty($resultStaff['id'])){
        header('Location: login');
        exit;
    }
    
    $idStaff = $resultStaff['id'];
}else{
    header('Location: login');
    exit;
}

// $prefix = [
//     'owner' => ['', 'index', 'clientele', 'accounting', 'staff', 'cheques', 'profile'],
//     'manager' => ['', 'index', 'clientele', 'accounting', 'cheques', 'profile'],
//     'seller' => ['clientele', 'accounting', 'cheques', 'profile'],
// ];

// if(!in_array(basename($_SERVER['REQUEST_URI']), $prefix[$resultStaff['role']])){
//     // header('Location: profile');

//     die('Ошибка: Доступ к данной странице запрещен');

// }

$prefix = [
    ''=> ['owner', 'manager'],
    'index'=> ['owner', 'manager'],
    'clientele'=> ['owner', 'manager', 'seller'],
    'accounting'=> ['owner', 'manager', 'seller'],
    'staff'=> ['owner', 'manager'],
    'cheques'=> ['owner', 'manager'],
    'profile'=> ['owner', 'manager', 'seller'],
];

$urlSTR = strtok(basename($_SERVER['REQUEST_URI']), '?');

if(!empty($prefix[$urlSTR])){
    if(in_array($prefix[$urlSTR], $prefix[$urlSTR])){
        echo "OK";
    }
}

// var_dump();
// die;



?>