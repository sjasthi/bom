<?php

    $nav_selected = "REPORTS"; 
    $left_buttons = "YES"; 
    $left_selected = "REPORTSPIECHART"; 

    include("./nav.php");
    global $db;
   
?>
<html>

    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

        <script type="text/javascript">
        
        function createPieChart(pieChart){
            let name = pieChart[0];
            let columnTitle = pieChart[1];

            let queryArray = [[columnTitle, 'Count', {role:'annotation'}]];
            
            switch(name){
                case 'Application':
                    <?php
                    $query = $db->query("SELECT app_status, COUNT(app_status) AS occurrences FROM sbom GROUP BY app_status;");
                    while($query_row = $query->fetch_assoc()) {
                        echo 'queryArray.push(["'.$query_row["app_status"].'", '.$query_row["occurrences"].', "'.$query_row["app_status"].'"]);';
                    }
                    ?>
                    break;
                case 'Component':
                    <?php
                    $query = $db->query("SELECT cmp_status, COUNT(cmp_status) AS occurrences FROM sbom GROUP BY cmp_status;");
                    while($query_row = $query->fetch_assoc()) {
                        echo 'queryArray.push(["'.$query_row["cmp_status"].'", '.$query_row["occurrences"].', "'.$query_row["cmp_status"].'"]);';
                    }
                    ?>
                    break;
                case 'Request':
                    <?php
                    $query = $db->query("SELECT request_status, COUNT(request_status) AS occurrences FROM sbom GROUP BY request_status;");
                    while($query_row = $query->fetch_assoc()) {
                        echo 'queryArray.push(["'.$query_row["request_status"].'", '.$query_row["occurrences"].', "'.$query_row["request_status"].'"]);';
                    }
                    ?>
                    break;
                case 'Request Step':
                    <?php
                    $query = $db->query("SELECT request_step, COUNT(request_step) AS occurrences FROM sbom GROUP BY request_step;");
                    while($query_row = $query->fetch_assoc()) {
                        echo 'queryArray.push(["'.$query_row["request_step"].'", '.$query_row["occurrences"].', "'.$query_row["request_step"].'"]);';
                    }
                    ?>
                    break;
            }

            return queryArray;
        }

        let pieCharts = [['Application', 'Application Status'], ['Component', 'Component Status'], ['Request', 'Request Status'], ['Request Step', 'Request Step']];

        for(let i = 0; i < pieCharts.length; i++){
            pieCharts[i] = createPieChart(pieCharts[i]);
        }
        </script>

        <!-- Google Pie Chart API Code -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawPieCharts);

        function drawPieCharts() {
            pieCharts.forEach(queryArray => drawPieChart(queryArray));
        }

        function drawPieChart(queryArray){
            var data = google.visualization.arrayToDataTable(queryArray);

            let title = queryArray[0][0] + ' Report';

            var options = {
                title: title,
                width: 500,
                height: 500,
            };

            var chart = new google.visualization.PieChart(document.getElementById(title.replace(/ /g, '')));

            google.visualization.events.addListener(chart, 'select', selectHandler);

            chart.draw(data, options);

            function selectHandler(){
                var selectedItem = chart.getSelection()[0];
                if (selectedItem) {
                    var $statusSelection = data.getValue(selectedItem.row, 0);
                    var $reportType = queryArray[0][0].toLowerCase().replace(/ /g, '');
                    document.cookie = encodeURI('status_cookie') + '=' + encodeURI($statusSelection);
                    document.cookie = encodeURI('report_type') + '=' + encodeURI($reportType);
                    location.reload();
                }    
                <?php     
                if(isset($_COOKIE['status_cookie'])){
                    $statusSelection = $_COOKIE['status_cookie'];
                    $reportType = $_COOKIE['report_type'];
                    $statusCookie=true;
                } else{
                    $statusSelection = null;
                    $reportType = null;
                    $statusCookie = false;
                }  
                ?>                 
            }
        }

        
        </script>
        <!-- End Google Pie Chart API Code -->

        
    </head>

    <body>
        <div class="right-content">
            <div class="container">
                <h3 style = "color: #01B0F1;">Reports --> Pie Chart.</h3>
                <h3><img src="images/reports.png" style="max-height: 35px;" /> Pie Chart</h3>
            </div>
        </div>  
        <div class="container">
            <table>
                <tr>
                    <td><div style=" width:400px; height:400px; disply:inline-block;" id="ApplicationStatusReport" style="width: 900px; height: 500px;"></div></td>
                    <td><div style="width:400px; height:400px; disply:inline-block;" id="ComponentStatusReport" style="width: 900px; height: 500px;"></div></td>
                </tr>
                <tr>
                    <td><div style=" width:400px; height:400px; disply:inline-block;" id="RequestStatusReport" style="width: 900px; height: 500px;"></div></td>                   
                    <td><div style=" width:400px; height:400px; disply:inline-block;" id="RequestStepReport" style="width: 900px; height: 500px;"></div></td>
                </tr>
            </table>            
        </div>
    </body>

</html>
        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
