<?php

  $nav_selected = "SETUP"; 
  $left_buttons = "YES"; 
  $left_selected = "GANTTPREFERENCE"; 

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
                        Date From:
                        <br>Date To:<br>
                    </div>
                </div>
                </div>
                <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse-release-date">Release Date</a>
                    </h4>
                </div>
                <div id="collapse-release-date" class="panel-collapse collapse">
                    <div class="panel-body">
                        Release Date: 
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
                        Type:
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
