<?php
require_once('initialize.php');

function user_exists($email, $hash){
	global $db;

	if ($hash == 'NEW') {
		$sql = "SELECT * FROM users WHERE email='$email'";
	}
	else {
		$sql = "SELECT * FROM users WHERE email='$email' AND hash='$hash' AND active='0'";
	}

	$result = mysqli_query($db, $sql);

	if (DEBUG_MODE == 'ONxx') {
		echo 'DEBUG MODE: ' . dirname(__FILE__).'.user_exists()<br/>';
		echo 'SQL: ' .$sql . '<br/>';
		print_r($result);
		echo '<br/>';
		}

    if($result) {
        return $result;
    } else {
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}
function login_exists($email, $password){
	global $db;

	$sql = "SELECT * FROM users WHERE email='$email' AND hash='$password'";

	$result = mysqli_query($db, $sql);

	if (DEBUG_MODE == 'ONxx') {
		echo 'DEBUG MODE: ' . dirname(__FILE__).'.user_exists()<br/>';
		echo 'SQL: ' .$sql . '<br/>';
		print_r($result);
		echo '<br/>';
		}

    if($result) {
        return $result;
    } else {
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

  function find_all_users() {
    global $db;

    $sql = "select id, first_name, last_name, email, role, active ";
    $sql .= "from users ";
    //$sql .= "ORDER BY position ASC";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }


function find_user_by_id($id) {
    global $db;

    $sql = "select id, first_name, last_name, email, role, active ";
    $sql .= "from users ";
    $sql .= "where id='" . $id . "' ";
    //$sql .= "ORDER BY position ASC";
    //echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;

}

function validate_user($user) {
    $errors = [];

    // menu_name
    if(is_blank($user['menu_name'])) {
        $errors[] = "Name cannot be blank.";
    } elseif(!has_length($user['menu_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
    }

    // position
    // Make sure we are working with an integer
    $postion_int = (int) $user['position'];
    if($postion_int <= 0) {
        $errors[] = "Position must be greater than zero.";
    }
    if($postion_int > 999) {
        $errors[] = "Position must be less than 999.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string) $user['visible'];
    if(!has_inclusion_of($visible_str, ["0","1"])) {
        $errors[] = "Visible must be true or false.";
    }

    return $errors;
}



function insert_user($first_name, $last_name, $email, $password, $hash, $role='USER', $active=0) {
    global $db;


    $sql = "INSERT INTO users ";
    $sql .= " (first_name, last_name, email, role, active, hash, CreatedTime) ";
    $sql .= "VALUES (";
    $sql .= "'" . $first_name . "',";
    $sql .= "'" . $last_name . "',";
    $sql .= "'" . $email . "',";
	$sql .= "'" . $role . "',";
	$sql .= "'" . $active . "',";
	$sql .= "'" . $hash .  "'";
	$sql .= "'0000-00-00 00:00:00'";
    $sql .= ")";

	echo $sql;

    $result = mysqli_query($db, $sql);
	echo '$result is: ';
	print_r($result);
    // For INSERT statements, $result is true/false
    if($result) {
        return true;
    } else {
        // INSERT failed
        echo 'Insert Error: ' . mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function update_user($user) {
    global $db;

    $sql = "UPDATE users SET ";
    $sql .= "username='" . $user['username'] . "', ";
    $sql .= "user_email='" . $user['user_email'] . "', ";
    $sql .= "role='" . $user['role'] . "' ";
    $sql .= "WHERE user_id='" . $user['id'] . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }

}

function activate_user($email) {
    global $db;

    $sql = "UPDATE users SET ";
    $sql .= "active='1'";
    $sql .= "WHERE email='" . $email . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }

}



function delete_user_old($id) {
    global $db;

    $sql = "DELETE FROM users ";
    $sql .= " WHERE users.id='" . $id . "' ";
    $sql .= "LIMIT 1";
    $result = mysqli_query($db, $sql);

    // For DELETE statements, $result is true/false
    if($result) {
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function delete_user($id) {
    global $db;

    if ($id < 0) {return true;}	// Don't let the interface take out any special rows hidden with item_ids < 0

    $sql = "DELETE FROM users";
    $sql .= " WHERE id='" . $id . "'";
    $sql .= " LIMIT 1";

    $result = mysqli_query($db, $sql);


    // For DELETE statements, $result is true/false
    if($result) {
        mysqli_commit($db);
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

function findPreference($pref_id, $table_name, $column_name, $value_pref){
    global $db;
    $pref = null;
    $pref_query= "SELECT value FROM preferences WHERE id = '".$pref_id."';"; 
    $result_pref = $db->query($pref_query); //queries the table based on the prederence id
    if ($result_pref -> num_rows > 0) { // if a preferences is found it outputs the data into the preference variable
        while ($row = $result_pref -> fetch_assoc()) {
            $pref = $row["value"];
              }$result_pref -> close(); //Closing the database results
              //end while
            }//end if
            else { //if the preference is not found it will search the table for the available values
                if ($value_pref == 'first'){ //adjusts the sort setting to put the first value at the bottom of the list
                    $order = 'DESC';
                } else {
                    $order = 'ASC';
                }
                $table_query= "SELECT DISTINCT ".$column_name." FROM ".$table_name." ORDER BY ".$column_name." ".$order.";";
                $result_table = $db->query($table_query); //queries the table for possible values
                if ($result_table -> num_rows > 0) {// output data of each row
                    while ($row2 = $result_table -> fetch_assoc()) {
                        if($value_pref == 'all'){
                            $pref .= $row2[$column_name].', '; //places all of the values into the varable
                        } else {
                            $pref = $row2[$column_name]; //picks up the last value in the list
                        }
                    } $result_table -> close(); //Closing the database results
                    //end while
                } else {
                    $pref = null; //if nothing is found in either the preferences or table, sets the variable to null
                }
            }//end else
            
            return $pref;
        }

?>
