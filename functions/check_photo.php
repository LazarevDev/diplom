<?php 

function checkPhoto($nameTable, $id, $photo){
    if(empty($photo)){
        return '<img src="img/holder.jpg" alt="">';
    }

    return '<img src="img/'.$nameTable.'/'.$id.'/'.$photo.'" alt="">';
}
?>