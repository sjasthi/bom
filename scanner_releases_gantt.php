<?php
    $nav_selected = "SCANNER"; 
    $left_buttons = "YES"; 
    $left_selected = "RELEASESGANTT"; 

    include("./nav.php");
    global $db;
?>

<div class="right-content">
    <div class="container">

        <h3 style = "color: #01B0F1;">Scanner -> System Releases Gantt</h3><br>

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
                    return [this.id, this.name, this.open_date, this.rtm_date, null, 100, null];
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
                    //pulls the from aka start date from the preferences table
                    $pref_fdate= "SELECT value FROM preferences WHERE id = 'gantt_start';";
                    $result_fdate = $db->query($pref_fdate);
                    if ($result_fdate -> num_rows > 0) {
                        // output data of each row
                        while ($row = $result_fdate -> fetch_assoc()) {
                            $fDate = $row["value"];
                              }//end while
                            }//end if
                            else {
                                $fDate = 'now()';
                            }//end else
                            $result_fdate -> close(); //Closing the database results

                    //pulls the to aka end date from the preferences table
                    $pref_tdate= "SELECT * FROM preferences WHERE id = 'gantt_end';";
                    $result_tdate = $db->query($pref_tdate);
                    if ($result_tdate -> num_rows > 0) {
                        // output data of each row
                        while ($row = $result_tdate -> fetch_assoc()) {
                            $tDate = $row["value"];
                              }//end while
                            }//end if
                            else {
                                $tDate = 'now()';
                            }//end else
                            $result_tdate -> close(); //Closing the database results

                    //pulls the from status from the preference table
                    $pref_status= "SELECT * FROM preferences WHERE id = 'gantt_status';";
                    $result_status = $db->query($pref_status);
                    if ($result_status-> num_rows > 0) {
                        while ($row = $result_status -> fetch_assoc()) {
                        $status_list = $row["value"];
                          }//end while
                        }//end if
                        else {
                            $status_list  = 'Active';
                        }//end else
                        $result_status -> close(); //Closing the database results

                    //pulls the from types from the preference table
                    $pref_type= "SELECT * FROM preferences WHERE id = 'gantt_type';";
                    $result_type = $db->query($pref_type);
                    if ($result_type -> num_rows > 0) {
                        while ($row = $result_type  -> fetch_assoc()) {
                        $type_list = $row["value"];
                          }//end while
                        }//end if
                        else {
                            $type_list  = 'Async';
                        }//end else
                        $result_type -> close(); //Closing the database results

                    $sql = "SELECT * FROM `releases` WHERE `type` IN ($type_list) AND `status` IN ($status_list) AND `open_date` >= '$fDate' AND `rtm_date` <= '$tDate'";
                    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {

                            //Changing this boolean value fufills requirement A3&A4.13
                            $useDependencyDateInsteadOfOpenDate = false;

                            $open =  $useDependencyDateInsteadOfOpenDate ? explode('-', $row["dependency_date"]) : explode('-', $row["open_date"]);
                            $dependency = $useDependencyDateInsteadOfOpenDate ? explode('-', $row["open_date"]) : explode('-', $row["dependency_date"]);
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
                    width: 1200,
                    gantt: {
                        trackHeight: trackHeight,
                        percentEnabled: false
                    }
                };

                var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

                chart.draw(data, options);
            }
        </script>

        <div id="chart_div"></div>
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
