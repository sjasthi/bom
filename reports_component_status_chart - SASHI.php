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


       

        <!--Google pie Chart Code
        -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['Component Status', 'Counts'],

                    //Pulling data from database
                    <?php
                        $sql = "SELECT cmp_status, COUNT(cmp_status) AS counts FROM sbom GROUP BY cmp_status;";
                        $result = $db->query($sql);

                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                            echo "['".
                            $row['cmp_status'].
                            "',".
                            $row['counts'].
                            "],";
                            }
                        }
                    ?>
                    ]);

                var options = {
                    title: 'Component Status Pie Chart',
                    width: 900,
                    height: 500,
                };


                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                google.visualization.events.addListener(chart, 'select', selectHandler);
                
                function selectHandler(){
                    var selectedItem = chart.getSelection()[0];
                    if (selectedItem) {
                    var $cmpStatusSelection = data.getValue(selectedItem.row, 0);   
                    document.cookie = escape('cmp_status_cookie') + '=' + escape($cmpStatusSelection); 
                    location.reload();
                    }    
                    <?php     
                    if(isset($_COOKIE['cmp_status_cookie'])){
                        $cmpStatusSelection = $_COOKIE['cmp_status_cookie'];    
                        $cmpStatusCookie=true;
                    } else{
                        $cmpStatusSelection = null;
                        $cmpStatusCookie=false;
                    }  
                    ?>                 
                }
                chart.draw(data, options);
            }            
        </script>
        


        <!-- Google bar chart -->
        
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {packages: ['corechart', 'bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data2 = google.visualization.arrayToDataTable([
                    ['Component Status', 'Counts'],
               
                    <?php
                            $sql = "SELECT cmp_status, COUNT(cmp_status) AS counts FROM sbom GROUP BY cmp_status;";
                            $result = $db->query($sql);

                            if($result->num_rows > 0){
                                while($row = $result->fetch_assoc()){
                                echo "['".
                                $row['cmp_status'].
                                "',".
                                $row['counts'].
                                "],";
                                }
                            }
                        ?>

                ]);

                var options2 = {
                    title: 'Component Report',
                    width: 900,
                    height: 500,
                    hAxis: {
                        title: 'Counts',
                        minValue: 0
                    },
                    vAxis: {
                        title: 'Component Status'
                    },
                    bars: 'horizontal'
                };

                var chart2 = new google.visualization.BarChart(document.getElementById('barchart'));
                google.visualization.events.addListener(chart2, 'select', selectHandler);
                chart2.draw(data2, options2);
                function selectHandler(){
                    var selectedItem = chart2.getSelection()[0];
                   
                        
                    if (selectedItem) {
                    var cmpStatusSelection = data2.getValue(selectedItem.row, 0);   
                    document.cookie = escape('cmp_status_cookie') + '=' + escape(cmpStatusSelection); 
                    location.reload();
                    
                    }    
                    <?php     
                    if(isset($_COOKIE['cmp_status_cookie'])){
                        $cmpStatusSelection = $_COOKIE['cmp_status_cookie'];    
                        $cmpStatusCookie=true;
                    } else{
                        $cmpStatusSelection = null;
                        $cmpStatusCookie=false;
                    }                 
                                         
                    ?>                 
                }
            }
        </script>
    -->
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
                <div id="collapse-pie-chart" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div id="piechart" style="width: 900px; height: 500px;"></div>
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
                        <div id="barchart" style="width: 900px; height: 500px;"></div>
                       
                    </div>
                </div>
                </div>           
                
            </div> 
           <h3> <?php echo "value = " . $cmpStatusSelection; ?> </h3>
        </div>
        

    </body>

</html>
        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
