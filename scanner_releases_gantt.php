<?php
    $nav_selected = "SCANNER"; 
    $left_buttons = "YES"; 
    $left_selected = "RELEASESGANTT"; 

    include("./nav.php");
    global $db;
?>

<div class="right-content">
    <div class="container">

        <h3 style = "color: #01B0F1;">Scanner -> System Releases Gantt</h3>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            class Release{
                constructor(id, name, type, status, open_date, dependency_date, freeze_date, rtm_date, manager, author, app_id){
                    this.id = id;
                    this.name = name;
                    this.tpe = type;
                    this.status = status;
                    this.open_date = open_date;
                    this.dependency_date = dependency_date;
                    this.freeze_date = freeze_date;
                    this.rtm_date = rtm_date;
                    this.manager = manager;
                    this.author = author;
                    this.app_id = app_id;
                }

                getDataTableRow(){
                    var percentComplete = 0;
                    switch(this.status){
                        case "Draft": percentComplete = 25; break;
                        case "Active": percentComplete = 50; break;
                        case "Completed": percentComplete = 75; break;
                        case "Released": percentComplete = 100; break;
                    }
                    return [this.id, this.name, this.open_date, this.rtm_date, null, percentComplete, null];
                }
            }

            google.charts.load('current', {'packages':['gantt']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();

                var rawData = [];

                data.addColumn('string', 'Task ID');
                data.addColumn('string', 'Task Name');
                data.addColumn('date', 'Start Date');
                data.addColumn('date', 'End Date');
                data.addColumn('number', 'Duration');
                data.addColumn('number', 'Percent Complete');
                data.addColumn('string', 'Dependencies');

                <?php
                    $sql = "SELECT * from releases";
                    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $open = explode('-', $row["open_date"]);
                            $dependency = explode('-', $row["dependency_date"]);
                            $freeze = explode('-', $row["freeze_date"]);
                            $rtm = explode('-', $row["rtm_date"]);

                            echo "rawData.push(new Release(";
                            echo "'{$row["id"]}',";
                            echo "'{$row["name"]}',";
                            echo "'{$row["type"]}',";
                            echo "'{$row["status"]}',";
                            echo "new Date({$open[0]},{$open[1]}-1,{$open[2]}),";
                            echo "new Date({$dependency[0]},{$dependency[1]}-1,{$dependency[2]}),";
                            echo "new Date({$freeze[0]},{$freeze[1]}-1,{$freeze[2]}),";
                            echo "new Date({$rtm[0]},{$rtm[1]}-1,{$rtm[2]}),";
                            echo "'{$row["manager"]}',";
                            echo "'{$row["author"]}',";
                            echo "'{$row["app_id"]}',";
                            echo "));\r\n";
                        }
                    }else {
                        echo "0 results";
                    }
                    $result->close();
                ?>

                rawData.sort((a, b) => (a.open_date > b.open_date) ? 1 : -1);

                rawData.forEach(function (element){data.addRow(element.getDataTableRow())});

                var trackHeight = 30;

                var options = {
                    height: data.getNumberOfRows() * trackHeight + 50,
                    width: 1600,
                    gantt: {
                        trackHeight: trackHeight
                    }
                };

                var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

                chart.draw(data, options);
            }
        </script>

        <div id="chart_div"></div>
        <p>TODO: 4.11, 4.12?, 4.13, 4.14, 4.15</p>
    </div>
</div>

<style>
    tfoot {
        display: table-header-group;
    }
    #chart_div{
        overflow-x: scroll;
        overflow-y: hidden;
    }
</style>

<?php
include("./footer.php");
?>
