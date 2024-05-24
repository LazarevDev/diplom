<?php 
function photo($param, $nameParam, $structureLink, $db, $arrayEdit = null){
    if(isset($_GET['edit'])){
        $editID = $_GET['edit'];

        if(!empty($param)){
            // Удаляем прошлую картинку если она есть

            if(!empty($arrayEdit)){
                $structure = 'img/'.$structureLink.'/'.$editID;
                unlink($structure.'/'.$arrayEdit);
            }

            // Сохраняем новую

            $target = "img/".$structureLink."/".$editID."/".basename($param);

            if(move_uploaded_file($_FILES[$nameParam]['tmp_name'], $target)) {
                $queryCover = mysqli_query($db, "UPDATE `".$structureLink."` SET  
                `".$nameParam."` = '$param' WHERE `id` = '$editID'");
            }else{
                $msg = "Не удалось сохранить картинку";
                $param = "0";
            }
        }else{
            $msg = "Не удалось сохранить картинку";
            $param = "0";
        }

    }elseif(isset($_GET['delete'])){
        $deleteID = $_GET['delete'];

        $queryDeleteIdCover = mysqli_query($db, "SELECT * FROM `".$structureLink."` WHERE `id` = '$deleteID'");
        $resultDeleteIdCover = mysqli_fetch_array($queryDeleteIdCover);
    
        $coverDelete = $resultDeleteIdCover[$nameParam];
    
        $structure = 'img/'.$structureLink.'/'.$deleteID;
        unlink($structure.'/'.$coverDelete);
        rmdir($structure);
        
    }else{
        $queryDesc = mysqli_query($db, "SELECT * FROM `".$structureLink."` ORDER BY `id` DESC");
        $resultDesc = mysqli_fetch_array($queryDesc);
    
        $idDescAdd = $resultDesc['id'];
    
        // создание структуры папок
        $target = "img/".$structureLink."/".$idDescAdd."/".basename($param);
        $structure = 'img/'.$structureLink.'/'.$idDescAdd;
    
        if(!mkdir($structure, 0777, true)) {
            die('Не удалось создать директории...');
        }
    
        // Добавляем фото в папку
    
        if(!empty($param)){
            if(move_uploaded_file($_FILES[$nameParam]['tmp_name'], $target)) {
                $queryCover = mysqli_query($db, "UPDATE `".$structureLink."` SET  
                `".$nameParam."` = '$param' WHERE `id` = '$idDescAdd'");
            }else{
                $msg = "Не удалось сохранить изображение";
                $param = "0";
            }
        }else{
            $msg = "Изображение не найдено";
            $param = "0";
        }
    }
}
?>