<?php

    $nav_selected = "REPORTS"; 
    $left_buttons = "YES"; 
    $left_selected = "APPLICATIONSTATUSCHART"; 

    include("./nav.php");
    global $db;
   
?>
<html>

    <head>
    </head>

    <body>
        <div class="right-content">
            <div class="container">

                <h3 style = "color: #01B0F1;">Reports --> Application Status Chart.</h3>
                <h3><img src="images/reports.png" style="max-height: 35px;" /> Application Status Chart</h3>

            </div>
        </div>  
    
        <h3>File for this page: reports_<span style="color:red">application</span>_status_chart.php</h3>

    </body>

</html>
        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
