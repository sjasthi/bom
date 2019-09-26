<?php

    $nav_selected = "SETUP"; 
    $left_buttons = "YES"; 
    $left_selected = "GANTTPREFERENCE"; 

    include("./nav.php");
    global $db;

    $statusSet = $db->query("SELECT DISTINCT `status` FROM releases ORDER BY `status` ASC");
    $typeSet = $db->query("SELECT DISTINCT `type` FROM releases ORDER BY `type` ASC"); 

    $typeResults = $db->query("SELECT `value` FROM `preferences` WHERE `id` = 'gantt_type'");
    $statusResults = $db->query("SELECT `value` FROM `preferences` WHERE `id` = 'gantt_status'"); 
    $sdateResults = $db->query("SELECT `value` FROM `preferences` WHERE `id` = 'gantt_start'");
    $edateResults = $db->query("SELECT `value` FROM `preferences` WHERE `id` = 'gantt_end'");
    
    if(mysqli_num_rows($typeResults)>0){
        while($row = mysqli_fetch_assoc($typeResults)){
            $a[] = $row;
        }
    }
    $types = $a[0]['value'];
    
    if(mysqli_num_rows($statusResults)>0){
        while($row = mysqli_fetch_assoc($statusResults)){
            $b[] = $row;
        }
    }
    $status = $b[0]['value'];

    if(mysqli_num_rows($sdateResults)>0){
        while($row = mysqli_fetch_assoc($sdateResults)){
            $c[] = $row;
        }
    }
    $sDate = $c[0]['value'];
    
    if(mysqli_num_rows($edateResults)>0){
        while($row = mysqli_fetch_assoc($edateResults)){
            $d[] = $row;
        }
    }
    $eDate = $d[0]['value'];
    
?>
<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    </head>

    <body>
    <?php
        if(isset($_GET['preferencesUpdated'])){
            if($_GET["preferencesUpdated"] == "Success"){
                echo "<br><h3 align=center style='color:green'>Success! The Preferences have been updated!</h3>";
            }
        }
    ?>
        <div class="right-content">
            <div class="container">

                <h3 style = "color: #01B0F1;">Setup --> Gantt Preferences</h3>
                <h3><img src="images/gantt.png" style="max-height: 35px;" /> Gantt Preferences</h3>

            </div>
        </div>  
        
        <div class="container">
            <h3>Set preferences on how to display the Gantt chart below:</h3>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-date-range">Date Range</a>
                    </h4>
                </div>
                <div id="collapse-date-range" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <form action="modifyThePreferences.php" method="POST">
                            <table style="width:500px">
                                <tr>
                                    <th style="width:200px"></th>
                                    <th>Current Value</th> 
                                    <th>Update Value</th>
                                </tr>
                                <tr>
                                    <td style="width:200px">Set Start Date:</td>
                                    <td><input disabled type="date" maxlength="12" size="15" value="<?php echo $sDate; ?>" title="Current value"></td> 
                                    <td><input required type="date" name="new_sDate" maxlength="12" size="15" title="Enter a start date"></td>
                                </tr>
                                <tr>
                                    <td style="width200px">Set End Date:</td>
                                    <td><input disabled type="date" maxlength="12" size="15" value="<?php echo $eDate; ?>" title="Current value"></td> 
                                    <td><input required type="date" name="new_eDate" maxlength="12" size="15" title="Enter a end date"></td>
                                </tr>
                            </table><br>
                            <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify Dates</button>
                        </form>
                    </div>
                </div>
                </div>
                <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-release-date">Release Status</a>
                    </h4>
                </div>
                <div id="collapse-release-date" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form action="modifyThePreferences.php" method="POST">
                            <table style="width:5500px">
                                <tr>
                                    <th style="width:200px"></th>
                                    <th>Statuses</th> 
                                </tr>
                                <tr>
                                    <td style="width:200px">Current Status Range:</td>
                                    <td><input disabled type="string" maxlength="250" size="50" value="<?php echo $status; ?>" title="Current status"></td> 
                                </tr>
                                <tr>
                                    <td style="width200px">Select Status Range:</td>
                                    <td>
                                        <?php $a=1; while($rows = $statusSet->fetch_assoc()){
                                                $status=$rows['status']; 
                                        echo"<input type='checkbox' name='new_status$a' Value='$status'>&nbsp; $status &nbsp;&nbsp;"; $a++;}?></td>
                                </tr>
                            </table><br>
                            <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify Status</button>
                        </form>
                    </div>
                </div>
                </div>
                <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-type">Type</a>
                    </h4>
                </div>
                <div id="collapse-type" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form action="modifyThePreferences.php" method="POST">
                            <table style="width:5500px">
                                    <tr>
                                        <th style="width:200px"></th>
                                        <th>Types</th> 
                                    </tr>
                                    <tr>
                                        <td style="width:200px">Current Type Range:</td>
                                        <td><input disabled type="string" maxlength="250" size="50" value="<?php echo $types; ?>" title="Current status"></td> 
                                    </tr>
                                    <tr>
                                        <td style="width200px">Select Type Range:</td>
                                        <td style="width300px">
                                            <?php $a=1; while($rows = $typeSet->fetch_assoc()){
                                                    $type=$rows['type']; 
                                            echo"<input type='checkbox' name='new_type$a' Value=''$type''>&nbsp; $type &nbsp;&nbsp;"; $a++;}?></td>
                                    </tr>
                                </table><br>
                            <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify Type</button>
                        </form>
                    </div>
                </div>
                </div>
            </div> 
        </div>

    </body>

</html>
        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
