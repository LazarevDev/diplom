<?php 
require_once('../require/db.php');
if (php_sapi_name() !== 'cli') {
    die("Этот скрипт можно запускать только через терминал.");
}

$arrayTables = [
    "cheque" => " CREATE TABLE `cheque` (
            `id` int NOT NULL AUTO_INCREMENT,
            `buyer` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `buyer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Individuals',
            `phone` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `address` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
            `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `staff_id` int NOT NULL,
            `staff_name` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
            `staff_wages` int NOT NULL,
            `staff_percentage_product_sales` int NOT NULL,
            `staff_phone` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
            `staff_address` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
            `status` varchar(225) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'approved',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    "clientele" => "CREATE TABLE `clientele` (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `buyer` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
                    `type` varchar(225) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'individuals',
                    `phone` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
                    `address` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
    "interim_receipt" => "CREATE TABLE `interim_receipt` (
                        `id` int NOT NULL AUTO_INCREMENT,
                        `name` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
                        `sale_price` int NOT NULL COMMENT 'Цена продажи',
                        `purchase_price` int NOT NULL COMMENT 'Цена покупки',
                        `count_product` int NOT NULL COMMENT 'Кол-во проданного товара',
                        `staff_id` int NOT NULL,
                        `cheque_id` int DEFAULT NULL,
                        PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
            
    "staff" => "CREATE TABLE `staff` (
                `id` int NOT NULL AUTO_INCREMENT,
                `photo` varchar(225) COLLATE utf8mb4_general_ci DEFAULT NULL,
                `name` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
                `role` varchar(225) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'seller',
                `wages` int NOT NULL DEFAULT '10000' COMMENT 'Фиксированная зарплата',
                `percentage_product_sales` int NOT NULL DEFAULT '0' COMMENT 'Процент от продаж',
                `phone` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
                `address` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
                `password` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
];

function deleteFolder($folderPath) {
    // Удаление всех файлов и подпапок внутри указанной директории
    $files = glob($folderPath . '/*');
    foreach ($files as $file) {
        is_dir($file) ? deleteFolder($file) : unlink($file);
    }

    // Удаление самой директории
    rmdir($folderPath);
}

// Пример использования
$folderToDelete = '../img/staff';

if (is_dir($folderToDelete)) {
    deleteFolder($folderToDelete);
    echo "Папка $folderToDelete успешно удалена.\n\n";
} else {
    echo "Папка $folderToDelete не существует.\n\n";
}

sleep(1);

foreach ($arrayTables as $key => $value) {
    $queryAdd = mysqli_query($db, $value);
    echo "Таблица `".$key."` добавлена \n";
    sleep(1);
}

echo "Все таблицы успешно добавлены в ваш проект \n\n";

sleep(1);

$adminPassword = md5('admin');

$resultAddStaff = mysqli_query($db, "INSERT INTO `staff` (`name`, `role`, `wages`, `percentage_product_sales`, `phone`, `address`, `password`) VALUES 
('admin', 'owner', '0', '0', '+7 000 000 00-00', '-', '$adminPassword')") or die(mysqli_error($db));

$queryDesc = mysqli_query($db, "SELECT * FROM `staff` ORDER BY `id` DESC");
$resultDesc = mysqli_fetch_array($queryDesc);

$idDescAdd = $resultDesc['id'];

$structure = '../img/staff/'.$idDescAdd;
if(!mkdir($structure, 0777, true)) {
    die('Не удалось создать директории...');
}

echo "Администратор успешно добавлен \n Логин: +7 000 000 00-00 \n Пароль: admin\n";

?>