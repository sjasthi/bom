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
            <img src="./images/sbom_list.png" alt="oops">
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

            <form class="form-horizontal" action="" method="post"
                  name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <img src="./images/file.png" alt="oops">
                <div class="input-row">
                    <input style="color: transparent" type="file" name="file"
                           id="file" accept=".csv">
                    <button type="submit" id="submit" name="import"
                            class="btn-submit">Import File
                    </button>
                    <br/>

                </div>

            </form>

        </div>

    </div>
    <form enctype="multipart/form-data" method="POST" role="form">
        <div class="form_group">
            <input type="file" name="file" id="file" size="150">
            <button type="submit" class="btn btn-default" name="submit" value="submit">Upload</button>

    </form>

</body>
</html>

<?php
require_once('calculate_color.php');

if (isset($_POST['submit'])) {
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $db = 'bom';

    $conn = mysqli_connect($host, $user, $password) or die('Could not connect to server' .msqli_error($conn));

    mysqli_select_db($conn, $db) or die('Could not connect to database' .msqli_error($conn));

    $file = $_FILES['file']['tmp_name'];
    $handle = fopen($file, "r");

    $c = 0;

    $sqlDelete = "DELETE FROM sbom";
    mysqli_query($conn, $sqlDelete);

    while (($csvdata = fgetcsv($handle,1000,",") ) !==FALSE ) {
        $row_id = $csvdata[0];
        $app_id = $csvdata[1];
        $app_name = $csvdata[2];
        $app_version = $csvdata[3];
        $cmp_id = $csvdata[4];
        $cmp_name = $csvdata[5];
        $cmp_version = $csvdata[6];
        $cmp_type = $csvdata[7];
        $app_status = $csvdata[8];
        $cmp_status = $csvdata[9];
        $request_id = $csvdata[10];
        $request_date = $csvdata[11];
        $request_status = $csvdata[12];
        $request_step = $csvdata[13];
        $notes = $csvdata[14];
        $requestor = $csvdata[15];
        $color = $csvdata[16];

        $sql = "INSERT into SBOM (row_id,
								  app_id,
								  app_name,
								  app_version,
								  cmp_id,
								  cmp_name,
								  cmp_version,
								  cmp_type,
								  app_status,
								  cmp_status,
								  request_id,
								  request_date,
								  request_status,
								  request_step,
								  notes,
								  requestor,
								  color) values (
											'$row_id',
											'$app_id',
											'$app_name',
											'$app_version',
											'$cmp_id',
											'$cmp_name',
											'$cmp_version',
											'$cmp_type',
											'$app_status',
											'$cmp_status',
											'$request_id',
											'$request_date',
											'$request_status',
											'$request_step',
											'$notes',
											'$requestor',
											'$color')";
        $query = mysqli_query($conn, $sql);
        colorize($app_id, $cmp_id);
        header("Refresh:0.05");
    }

    if($query) {
        echo "CSV data Updated Sucessfully.";
    } else {
        echo "An Error Occured.";
    }
}

?>