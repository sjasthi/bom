<!DOCTYPE html>
<html>

<head>
    <script src="jquery-3.2.1.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#frmCSVImport").on("submit", function () {

                $("#response").attr("class", "");
                $("#response").html("");
                var fileType = ".csv";
                var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
                if (!regex.test($("#file").val().toLowerCase())) {
                    $("#response").addClass("error");
                    $("#response").addClass("display-block");
                    $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
                    return false;
                }
                return true;
            });
        });
    </script>
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

    <div id="response" class="<?php if (!empty($type)) {
        echo $type . " display-block";
    } ?>"><?php if (!empty($message)) {
            echo $message;
        } ?></div>
    <div class="menu-left-current-page">
        <div class="row">

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
</body>
<?php
$conn = mysqli_connect("localhost", "root", "", "bom");

if (isset($_POST["import"])) {

    $fileName = $_FILES["file"]["tmp_name"];

    $sqlDelete = "DELETE FROM sbom";
    mysqli_query($conn, $sqlDelete);

    if ($_FILES["file"]["size"] > 0) {

        $file = fopen($fileName, "r");

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sqlInsert = "INSERT into sbom (row_id,
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
											requester,
											color)
                   values ('" . $column[0] . "',
                           '" . $column[1] . "',
                           '" . $column[2] . "',
                           '" . $column[3] . "',
                           '" . $column[5] . "',
						   '" . $column[6] . "',
						   '" . $column[7] . "',
						   '" . $column[8] . "',
						   '" . $column[9] . "',
						   '" . $column[10] . "',
						   '" . $column[11] . "',
						   '" . $column[12] . "',
						   '" . $column[13] . "',
						   '" . $column[14] . "',
						   '" . $column[15] . "',
						   '" . $column[16] . "'
                           )";
            $result = mysqli_query($conn, $sqlInsert);

            if (!empty($result)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message = "Problem in Importing CSV Data";
            }
        }
    }
}
