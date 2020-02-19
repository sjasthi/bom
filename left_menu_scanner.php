<!DOCTYPE html>
<html>

<head>
    <script src="jquery-3.2.1.min.js"></script>

    <style>
        body {
            font-family: Arial;
            width: 550px;
        }

        .outer-scontainer {
            background: #F0F0F0;
            border: #e0dfdf 1px solid;
            padding: 20px;
            border-radius: 2px;
        }

        .input-row {
            margin-top: 0px;
            margin-bottom: 20px;
        }

        .btn-submit {
            background: #333;
            border: #1d1d1d 1px solid;
            color: #f0f0f0;
            font-size: 0.9em;
            width: 100px;
            border-radius: 2px;
            cursor: pointer;
        }

        .outer-scontainer table {
            border-collapse: collapse;
            width: 100%;
        }

        .outer-scontainer th {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .outer-scontainer td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        #response {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 2px;
            display: none;
        }

        .success {
            background: #c7efd9;
            border: #bbe2cd 1px solid;
        }

        .error {
            background: #fbcfcf;
            border: #f3c6c7 1px solid;
        }

        div#response.display-block {
            display: block;
        }
    </style>
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
            <img src="./images/sbom_list.png">
            <br/>Out of Sync <br> BOM List<br/></div>
    </a>

    <a href="scanner_bom_backup.php">
        <div <?php if ($left_selected == "BOMBACKUP") {
            echo 'class="menu-left-current-page"';
        } ?>>
            <img src="./images/bom_backup.png">
            <br/>BOM Backup<br/></div>
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
                <div class="input-row">
                    <input type="file" name="file"
                           id="file" accept=".csv">
                    <button type="submit" id="submit" name="import"
                            class="btn-submit">Import File
                    </button>
                    <br/>

                </div>

            </form>

        </div>

        <a href="scanner_bom_compare.php">
            <div <?php if ($left_selected == "BOMCOMPARE") {
                echo 'class="menu-left-current-page"';
            } ?>>
                <img src="./images/bom_compare.png">
                <br/>BOM Compare<br/></div>
        </a>

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
            $sqlInsert = "INSERT into sbom (userId,userName,password,firstName,lastName)
                   values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "')";
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
