<?php
function edit($form, $editInput, $editInputTwo = null){
    if(isset($_GET['edit'])){
        if(!empty($editInput)){
            if($form == 'input'){
                echo "value='".$editInput."'";
            }elseif($form == 'textarea' OR $form == 'cover'){
                echo $editInput;
            }elseif($form == 'submit'){
                echo "Изменить";
            }elseif($form == 'select'){
                if($editInput == $editInputTwo){
                    echo "selected";
                }
            }
        }
    }
}
?>