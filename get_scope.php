<?php

function getScope ($db){
    $sql = "SELECT * from scope_preferences;";
    $result = $db->query($sql);
    $output = array('NULL'); //Represents the scope not containing any BOMS

    if ($result->num_rows > 0) {
      // output data of each row
      $row = $result->fetch_assoc();
      $prefString = $row["default_scope"];
      if (!empty(trim($prefString))){   
      $output = explode(",", $prefString);
      }
    }

    foreach($output as &$value){
        $value = "%$value%";
    }
    $result->close();
    return $output;
}

?>