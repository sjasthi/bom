<?php

include("./nav.php");
global $db;

if (isset($_POST['new_sDate'])){

    $sDate = $_POST['new_sDate'];
    $eDate = $_POST['new_eDate'];
      
    $sql1 = "UPDATE `preferences` SET `value`= '".$sDate."' WHERE `id` = 'gantt_start'";

    $sql2 = "UPDATE `preferences` SET `value`= '".$eDate."' WHERE `id` = 'gantt_end'";

    

    mysqli_query($db, $sql1);
    mysqli_query($db, $sql2);
    header('location: setup_gantt_preference.php?preferencesUpdated=Success');
}//end if

if(isset($_POST['status_submit'])){
    if (!empty($_POST['status_list'])){
        $status = $_POST['status_list'];
        $result = "'" . implode ( "', '", $status ) . "'";
        $sql1 = "UPDATE `preferences` SET `value`= \"" .$result. "\" WHERE `id` = 'gantt_status'";
        mysqli_query($db, $sql1);
        header('location: setup_gantt_preference.php?preferencesUpdated=Success');
    }else{
    header('location: setup_gantt_preference.php?preferencesUpdated=StatusFail');
    }
}   
if(isset($_POST['type_submit'])){
    if (!empty($_POST['type_list'])){
        $type = $_POST['type_list'];
        $result = "'" . implode ( "', '", $type ) . "'";
        $sql1 = "UPDATE `preferences` SET `value`= \"" .$result. "\" WHERE `id` = 'gantt_type'";
        mysqli_query($db, $sql1);
        header('location: setup_gantt_preference.php?preferencesUpdated=Success');
    }else{
        header('location: setup_gantt_preference.php?preferencesUpdated=TypeFail');   
    }
}
?>
