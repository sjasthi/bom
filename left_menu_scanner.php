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
</body>

