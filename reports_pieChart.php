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

        let queryArray_ApplicationReport = [['Application Status', 'Count', {role:'annotation'}]];
        let queryArray_ComponentReport = [['Component Status', 'Count', {role:'annotation'}]];
        let queryArray_RequestReport = [['Request Status', 'Count', {role:'annotation'}]];
        let queryArray_RequestStepReport = [['Request Step', 'Count', {role:'annotation'}]];

        <?php
        $query_ApplicationReport = $db->query("SELECT app_status, COUNT(app_status) AS occurrences FROM sbom GROUP BY app_status;");
        $query_ComponentReport = $db->query("SELECT cmp_status, COUNT(cmp_status) AS occurrences FROM sbom GROUP BY cmp_status;");
        $query_RequestReport = $db->query("SELECT request_status, COUNT(request_status) AS occurrences FROM sbom GROUP BY request_status;");
        $query_RequestStepReport = $db->query("SELECT request_step, COUNT(request_step) AS occurrences FROM sbom GROUP BY request_step;");

        while($query_row = $query_ApplicationReport->fetch_assoc()) {
            echo 'queryArray_ApplicationReport.push(["'.$query_row["app_status"].'", '.$query_row["occurrences"].', "'.$query_row["app_status"].'"]);';
        }        
        while($query_row = $query_ComponentReport->fetch_assoc()) {
            echo 'queryArray_ComponentReport.push(["'.$query_row["cmp_status"].'", '.$query_row["occurrences"].', "'.$query_row["cmp_status"].'"]);';
        }
        while($query_row = $query_RequestReport->fetch_assoc()) {
            echo 'queryArray_RequestReport.push(["'.$query_row["request_status"].'", '.$query_row["occurrences"].', "'.$query_row["request_status"].'"]);';
        }
        while($query_row = $query_RequestStepReport->fetch_assoc()) {
            echo 'queryArray_RequestStepReport.push(["'.$query_row["request_step"].'", '.$query_row["occurrences"].', "'.$query_row["request_step"].'"]);';
        }
        ?>
        </script>

        <!-- Google Pie Chart API Code -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawPieChart);

        function drawPieChart() {
            var data_ApplicationReport = google.visualization.arrayToDataTable(queryArray_ApplicationReport);
            var data_ComponentReport = google.visualization.arrayToDataTable(queryArray_ComponentReport);
            var data_RequestReport = google.visualization.arrayToDataTable(queryArray_RequestReport);
            var data_RequestStepReport = google.visualization.arrayToDataTable(queryArray_RequestStepReport);

            var options_ApplicationReport = {
            title: 'Application Report',
            width: 500,
            height: 500,
            };

            var options_ComponentReport = {
            title: 'Component Report',
            width: 500,
            height: 500,
            };

            var options_RequestReport = {
            title: 'Request Report',
            width: 500,
            height: 500,
            };

            var options_RequesStepReport = {
            title: 'Request Step Report',
            width: 500,
            height: 500,
            };


            var chart_application_report = new google.visualization.PieChart(document.getElementById('pie_chart_application_report'));
            var chart_component_report = new google.visualization.PieChart(document.getElementById('pie_chart_component_report'));
            var chart_request_report = new google.visualization.PieChart(document.getElementById('pie_chart_request_report'));
            var chart_requestStep_report = new google.visualization.PieChart(document.getElementById('pie_chart_request_step_report'));


            chart_application_report.draw(data_ApplicationReport, options_ApplicationReport);
            chart_component_report.draw(data_ComponentReport,options_ComponentReport);
            chart_request_report.draw(data_RequestReport,options_RequestReport);
            chart_requestStep_report.draw(data_RequestStepReport,options_RequesStepReport);
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
                    <td><div style=" width:400px; height:400px; disply:inline-block;" id="pie_chart_application_report" style="width: 900px; height: 500px;"></div></td>
                    <td><div style="width:400px; height:400px; disply:inline-block;" id="pie_chart_component_report" style="width: 900px; height: 500px;"></div></td>
                </tr>
                <tr>
                    <td><div style=" width:400px; height:400px; disply:inline-block;" id="pie_chart_request_report" style="width: 900px; height: 500px;"></div></td>                   
                    <td><div style=" width:400px; height:400px; disply:inline-block;" id="pie_chart_request_step_report" style="width: 900px; height: 500px;"></div></td>
                </tr>
            </table>            
        </div>

        <h3> &nbsp; &nbsp; &nbsp; &nbsp; File for this page: reports_<span style="color:red">application</span>_status_chart.php</h3>
    </body>

</html>
        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
