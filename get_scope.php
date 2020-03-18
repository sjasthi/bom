<?php

function getScope ($db){
    $sql = "SELECT * from scope_preferences;";
    $result = $db->query($sql);
    $output = array();

    if ($result->num_rows > 0) {
      // output data of each row
      $row = $result->fetch_assoc();
      $prefString = $row["default_scope"];
      $output = preg_split("/\,/", $prefString);
    }

    foreach($output as &$value){
        $value = "%$value%";
    }
    $result->close();
    return $output;
}

?>