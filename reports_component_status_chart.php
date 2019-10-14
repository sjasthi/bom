<?php

    $nav_selected = "REPORTS"; 
    $left_buttons = "YES"; 
    $left_selected = "COMPONENTSTATUSCHART"; 

    include("./nav.php");
    global $db;
   
?>
<html>

    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    
    </head>

    <body>
        <div class="right-content">
            <div class="container">

                <h3 style = "color: #01B0F1;">Reports --> Component Status Chart.</h3>
                <h3><img src="images/reports.png" style="max-height: 35px;" /> Component Status Chart</h3>

            </div>
        </div>  

        <br>
        <div class="container">

            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-pie-chart">Pie Chart</a>
                    </h4>
                </div>
                <div id="collapse-pie-chart" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <h3>Display pie-chart here!</h3>
                    </div>
                </div>
                </div>

                <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-bar-chart">Bar Chart</a>
                    </h4>
                </div>
                <div id="collapse-bar-chart" class="panel-collapse collapse">
                    <div class="panel-body">
                        <h3>Display bar-chart here!</h3>
                    </div>
                </div>
                </div>               
                
            </div> 
        </div>

        <h3> &nbsp; &nbsp; &nbsp; &nbsp; File for this page: reports_<span style="color:red">component</span>_status_chart.php</h3>
    </body>

</html>
        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
