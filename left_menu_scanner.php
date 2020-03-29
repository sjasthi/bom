<!DOCTYPE html>
<html>
<head>

</head>
<body>

<div id="menu-left">
    <a href="scanner_releases_list.php">
        <div <?php if ($left_selected == "RELEASESLIST") {
            echo 'class="menu-left-current-page"';
        } ?>>
            <img src="./images/releases.png">
            <br/>Releases List<br/></div>
    </a>
    <a href="scanner_releases_gantt.php">
        <div <?php if ($left_selected == "GANTT") {
            echo 'class="menu-left-current-page"';
        } ?>>
            <img src="./images/gantt.png">
            <br/>Releases Gantt<br/></div>
    </a>
    <a href="scanner_sbom_list.php">
        <div <?php if ($left_selected == "SBOMLIST") {
            echo 'class="menu-left-current-page"';
        } ?>>
            <img src="./images/sbom_list.png">
            <br/>BOM List<br/></div>
    </a>

    <a href="scanner_sbom_tree.php">
        <div <?php if ($left_selected == "SBOMTREE") {
            echo 'class="menu-left-current-page"';
        } ?>>
            <img src="./images/sbom_tree.png">
            <br/>BOM Tree<br/></div>
    </a>

    <a href="scanner_out_of_sync_bom_list.php">
        <div <?php if ($left_selected == "OUTOFSYNCBOMLIST") {
            echo 'class="menu-left-current-page"';
        } ?>>
            <img src="./images/sbom_list.png" alt="Import CSV">
            <br/>Out of Sync <br> BOM List<br/></div>
    </a>

    <a href="scanner_bom_backup.php">
        <div <?php if ($left_selected == "BOMBACKUP") {
            echo 'class="menu-left-current-page"';
        } ?>>
            <img src="./images/bom_backup.png">
            <br/>BOM Backup<br/></div>
    </a>
        <a href="scanner_bom_compare.php">
            <div <?php if ($left_selected == "BOMCOMPARE") {
                echo 'class="menu-left-current-page"';
            } ?>>
                <img src="./images/bom_compare.png">
                <br/>BOM Compare<br/></div>
        </a>
  
            <form class="form-horizontal" action="" method="post" name="frmCSVImport" 
            id="frmCSVImport" enctype="multipart/form-data">
                <img align="left" src="./images/upload-icon.png" alt="Import File">
            </form>
        </div>
    </div>
    
    <p style='color: #1B5BA0; font-weight: bold;'>Import File</p>
    <form enctype="multipart/form-data" method="POST" role="form">
        <input type="file" name="file" id="file" size="150">
        <button style='background: #01B0F1; color: white;' type="submit"
        class="btn btn-default" name="submit" value="submit">Import File</button>
    </form>
</body>
</html>

<?php
require_once('calculate_color.php');
$c = 0;

$labels = array('row_id', 'app_id', 'app_name', 'app_version', 'cmp_id', 'cmp_name', 'cmp_version',
'cmp_type', 'app_status', 'cmp_status', 'request_id', 'request_date', 'request_status', 'request_step',
'request_notes', 'notes', 'requestor', 'color');
$data = array();
$map = array();

if (isset($_POST['submit'])) {
  $host = 'localhost';
  $user = 'root';
  $password = '12345';
  $myDB = 'bom';
  $conn = mysqli_connect($host, $user) or die('Could not connect to server' .msqli_error($conn));
  mysqli_select_db($conn, $myDB) or die('Could not connect to database' .msqli_error($conn));
  
  $file = $_FILES['file']['tmp_name'];

  if(!file_exists($file)) {
    echo "<p style='color: white; background-color: red; font-weight: bold; width: 100px;
    text-align: center; border-radius: 2px;'>NO FILE WAS SELCTED</p>";
  
  }else {
    $handle = fopen($file, "r");
    if(FALSE !== $handle) {
        $row = fgetcsv($handle, 1000, ',');
        
        //get column labels
        foreach($labels AS $label) {
          $index = array_search(strtolower($label), array_map('strtolower', $row));
          if(FALSE !== $index) {
            $map[$index] = $label;
          }
        }
    
        //get data
        while($data1 = fgetcsv($handle, 1000, ',')) {
          $row = array();
          foreach($map as $index => $field) {
            $row[$field] = $data1[$index];
          }
            $data[] = $row;
          }
        }
    fclose($handle);


//delete existing data in table
$sqlDelete = "DELETE FROM sbom";
mysqli_query($conn, $sqlDelete);

//insert data into database
$sqlinsert = $conn->prepare('INSERT INTO sbom (row_id, app_id, app_name, app_version, cmp_id,
  cmp_name, cmp_version, cmp_type, app_status, cmp_status, request_id, request_date,
  request_status, request_step, notes, requestor, color) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');

$sqlinsert->bind_param('issssssssssssssss', $row_id, $app_id, $app_name, $app_version,
$cmp_id, $cmp_name, $cmp_version, $cmp_type, $app_status, $cmp_status, $request_id,
$request_date, $request_status, $request_step, $notes, $requestor, $color);

foreach ($data as $row)
{
    $row_id = $row['row_id'];
    $app_id = $row['app_id'];
    $app_name = $row['app_name'];
    $app_version = $row['app_version'];
    $cmp_id = $row['cmp_id'];
    $cmp_name = $row['cmp_name'];
    $cmp_version = $row['cmp_version'];
    $cmp_type = $row['cmp_type'];
    $app_status = $row['app_status'];
    $cmp_status = $row['cmp_status'];
    $request_id = $row['request_id'];
    $request_date = $row['request_date'];
    $request_status = $row['request_status'];
    $request_step = $row['request_step'];
    $notes = $row['notes'];
    $requestor = $row['requestor'];
    $color = $row['color'];
    $sqlinsert->execute();
    colorize($app_id, $cmp_id);
}

if($sqlinsert) {
    echo "CSV data Updated Sucessfully.";
} else {
  echo $conn->error;
}
  }
}
 ?>