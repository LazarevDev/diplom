<?php 
require_once('require/db.php');

if(isset($_POST['submit'])){    
    $idClient = $_POST['client'];

    $queryClient = mysqli_query($db, "SELECT * FROM `clientele` WHERE `id` = '$idClient'");
    $resultClient = mysqli_fetch_array($queryClient);

    $buyer = $resultClient['buyer'];
    $buyer_type = $resultClient['type'];
    $phone = $resultClient['phone'];
    $address = $resultClient['address'];

    $staff_id = $resultStaff['id'];
    $staff_name = $resultStaff['name'];
    $staff_wages = $resultStaff['wages'];
    $staff_percentage_product_sales = $resultStaff['percentage_product_sales'];
    $staff_phone = $resultStaff['phone'];
    $staff_address = $resultStaff['address'];

    $queryAdd = "INSERT INTO `cheque` (`buyer`, `buyer_type`, `phone`, `address`, `staff_id`, `staff_name`, `staff_wages`, `staff_percentage_product_sales`, `staff_phone`, `staff_address`) VALUES 
    ('$buyer', '$buyer_type', '$phone', '$address', '$staff_id', '$staff_name', '$staff_wages', '$staff_percentage_product_sales', '$staff_phone', '$staff_address')";
    $resultAddStaff = mysqli_query($db, $queryAdd) or die(mysqli_error($db));    

    $queryDescId = mysqli_query($db, "SELECT * FROM `cheque` ORDER BY `id` DESC");
    $resultDescId = mysqli_fetch_array($queryDescId);

    $idDesc = $resultDescId['id'];

    $queryInterimReceiptCheck = mysqli_query($db, "SELECT * FROM `interim_receipt` WHERE `staff_id` = '$idStaff' AND `cheque_id` IS NULL ORDER BY `id` DESC");
    while($rowInterimReceiptCheck = mysqli_fetch_array($queryInterimReceiptCheck)){
        $arrayInterimReceiptCheck[] = $rowInterimReceiptCheck['id'];
    } 

    for ($i=0; $i < count($arrayInterimReceiptCheck); $i++) { 
        $idUpdate = $arrayInterimReceiptCheck[$i];

        $queryUpdate = mysqli_query($db, "UPDATE `interim_receipt` SET 
        `cheque_id` = '$idDesc' WHERE `id` = '$idUpdate'");
    }

    header('Location: accounting?success=upload_check');
}

?>