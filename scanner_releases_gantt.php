
<?php

  $nav_selected = "SCANNER"; 
  $left_buttons = "YES"; 
  $left_selected = "RELEASESGANTT"; 
  $null_variable = null;

  include("./nav.php");
  global $db;

  ?> 

<html>
  <head>
      <!--START OF GOOGLE GANTT CHART-->
      
      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      

      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
      google.charts.load('current', {'packages':['gantt']});
      //google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart);

      function daysToMilliseconds(days) {
        return days * 24 * 60 * 60 * 1000;
      }     
      

      //BEGINNING OF drawChart()     
      function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task ID');
        data.addColumn('string', 'Task Name');
        data.addColumn('string', 'Resource');
        data.addColumn('date', 'Start Date');
        data.addColumn('date', 'End Date');
        data.addColumn('number', 'Duration');
        data.addColumn('number', 'Percent Complete');
        data.addColumn('string', 'Dependencies');       

        data.addRows([
          
            <?php
              $sql = "SELECT * from releases ORDER BY open_date ASC;";
              $result = $db->query($sql);
                           
              if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    //Separating date to convert into comma separated date format
                    $start_date = $row['open_date'];
                    $start_date_values = preg_split("/\-/",$start_date);
                    $start_date_with_comma = $start_date_values[0].",".$start_date_values[1].",".$start_date_values[2];

                    $end_date = $row['rtm_date'];
                    $end_date_values = preg_split("/\-/",$end_date);
                    $end_date_with_comma = $end_date_values[0].",".$end_date_values[1].",".$end_date_values[2];
                  
                    echo "['".
                      $row['id'].
                      "','".
                      $row['name'].   
                      "','".                   
                      $row['status'].
                      "',new Date(".$start_date_with_comma."),new Date(".$end_date_with_comma."),".
                      "null".
                      ",".
                      "0".
                      ",".
                      "null".
                      ",".
                      "],";
                  }
              }
            ?>
        ]);

        var options = {
          height:1000,
        };

        var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
      //END OF drawChart()
      

          
    </script>
  <!--END OF GOOGLE GANTT CHART-->
  </head>

  <body>
 
    <div class="right-content">
        <!--<div class="container" id="gantt_div"></div>-->
        <div class="container"></div>

          <h3 style = "color: #01B0F1;">Scanner -> System Releases Gantt</h3>
          <h3><img src="images/gantt.png" style="max-height: 35px;" />Releases Gantt Chart</h3>
          <div id="chart_div" style="max-height:500px; max-width:auto; overflow-x:scroll;"></div>

        
    </div>
  </body>
</html>          

