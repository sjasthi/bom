<?php
// set the current page to one of the main buttons
$nav_selected = "ADMIN";
// make the left menu buttons visible; options: YES, NO
$left_buttons = "YES";
// set the left menu button selected; options will change based on the main selection
$left_selected = "ADMIN";
include("./nav.php");
?>

<html>

<head>
<style>
table.center {
    margin-left:auto;
    margin-right:auto;
  }
#list ul {
  display: inline-block;
  text-align: left;
}
</style>
</head>

<body>
  <h2 style = "color: #01B0F1;">Admin --> Import BOM</h2>
  <div id='list'>
    <p>Before importing your file, please make sure your file is a <span style="font-weight: bold;">CSV</span> 
    file with these <span style="font-weight: bold;">15</span> columns and headers:<br></p>
    <ul>
            <li>app_id</li>
            <li>app_name</li>
            <li>app_version</li>
            <li>cmp_id</li>
            <li>cmp_name</li>
    </ul>
    <ul>
            <li>cmp_version</li>
            <li>cmp_type</li>
            <li>app_status</li>
            <li>cmp_status</li>
            <li>request_id</li>
    </ul>
    <ul>
            <li>request_date</li>
            <li>request_status</li>
            <li>request_step</li>
            <li>notes</li>
            <li>requestor</li>
    </ul>
  </div>
    <form enctype="multipart/form-data" method="POST" role="form">
      <input type="file" name="file" id="file" size="150" style="color:black; display: inline-block;">
      <button style="background: #01B0F1; color: white;" type="submit"
      class="btn btn-default" name="submit" value="submit">Import File</button>
    </form>
</body>
</html>

<?php
if(!isset($_SESSION)){
    session_start();
}

$c = 0;

$labels = array('app_id', 'app_name', 'app_version', 'cmp_id', 'cmp_name', 'cmp_version',
'cmp_type', 'app_status', 'cmp_status', 'request_id', 'request_date', 'request_status', 'request_step',
'notes', 'requestor');
$data = array();
$map = array();

if (isset($_POST['submit'])) {
  $chkfile = $_FILES['file']['name'];
  $file = $_FILES['file']['tmp_name'];

  //if user clicks button with no file uploaded
  if(!file_exists($file)) {
    echo "<p style='color: white; background-color: red; font-weight: bold; width: 500px;
    text-align: center; border-radius: 2px;'>NO FILE WAS SELCTED</p>";

  }else {
    $extension = 'csv';
    $file_ext = pathinfo($chkfile);

    //if the uploaded file is not a csv file
    if($file_ext['extension'] !== $extension) {
      echo "<p style='color: white; background-color: red; font-weight: bold; width: 500px;
      text-align: center; border-radius: 2px;'>PLEASE SELECT AN CSV FILE</p>";

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

        if(count($map) < 15) {
          echo "<div style='color: white; background-color: red; font-weight: bold; width: 500px;
          border-radius: 2px; padding: 1rem;'>CSV FILE MUST HAVE 15 COLUMNS";
          echo "<br>Your uploaded CSV file has ".count($map)."/15 column(s) or column labels.";
          echo "<br>Please make sure you have the correct columns and labels in your csv file.</div>";

        }else {
          //delete existing data in table
          $sqlDelete = "DELETE FROM sbom";
          mysqli_query($db, $sqlDelete);

          //insert data into database
          $sqlinsert = $db->prepare('INSERT INTO sbom (app_id, `app_name`, app_version, cmp_id,
            cmp_name, cmp_version, cmp_type, app_status, cmp_status, request_id, request_date,
            request_status, request_step, notes, requestor) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');

          $sqlinsert->bind_param('sssssssssssssss', $app_id, $app_name, $app_version,
            $cmp_id, $cmp_name, $cmp_version, $cmp_type, $app_status, $cmp_status, $request_id,
            $request_date, $request_status, $request_step, $notes, $requestor);

          foreach ($data as $row) {
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
                $request_date = strtotime($request_date);
                $request_date = date('Y/m/d', $request_date);
                $request_status = $row['request_status'];
                $request_step = $row['request_step'];
                $notes = $row['notes'];
                $requestor = $row['requestor'];
                $sqlinsert->execute();
          }
          if(!$sqlinsert->execute()) {
            echo '<p style="background: red; color: white; font-size: 2rem;">'.$db->error.'</p>';
          }else {
            echo "<p style='color: white; background-color: green; font-weight: bold; width: 500px;
            text-align: center; border-radius: 2px;'>IMPORT SUCCESSFUL";
            echo "<br>".count($data)." rows have been successfully imported into the sbom table.</p>";
          }
        }
      }
    }
  }
 ?>
