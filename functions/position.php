<?php 
function position($role){
    $arrayRole = [
        'owner' => 'Владелец', 
        'manager' => 'Менеджер', 
        'seller' => 'Продавец',
    ];

    return $arrayRole[$role];
}
?>