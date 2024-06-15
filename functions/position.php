<?php 
function position($role){
    $arrayRole = [
        'owner' => 'Владелец', 
        'manager' => 'Менеджер', 
        'seller' => 'Продавец',
    ];

    return $arrayRole[$role];
}

function type($type){
    $arrayType = [
        'individuals' => 'Физ.лицо',
        'pec' => 'ИП',
        'llc' => 'ООО',
    ];

    return $arrayType[$type];
}

function prefix($role, $return){

}
?>