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

if (isset($_POST['new_status1']) or isset($_POST['new_status2']) or isset($_POST['new_status3']) or isset($_POST['new_status4'])){

    $status1 = $_POST['new_status1'];
    $status2 = $_POST['new_status2'];
    $status3 = $_POST['new_status3'];
    $status4 = $_POST['new_status4'];

          
    //$sql1 = "UPDATE `preferences` SET `value`= $status WHERE `name` = 'gantt_status'";

    

    //mysqli_query($db, $sql1);
    //header('location: setup_gantt_preference.php?preferencesUpdated=Success');
}//end if

/*if (isset($_POST['new_type1'])){

    $type = mysqli_real_escape_string($db, $_POST['new_type']);
          
    $sql1 = "UPDATE `preferences` SET `value`= $type WHERE `name` = 'gantt_type'";

    mysqli_query($db, $sql1);
    
    header('location: setup_gantt_preference.php?preferencesUpdated=Success');
}/end if*/
?>
