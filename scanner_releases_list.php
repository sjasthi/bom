<?php

  $nav_selected = "SCANNER"; 
  $left_buttons = "YES"; 
  $left_selected = "RELEASESLIST"; 

  include("./nav.php");
  global $db;

  ?>


<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Scanner -> System Releases List</h3>

        <h3><img src="images/releases.png" style="max-height: 35px;" />System Releases</h3>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                <th>Application ID</th>
                <th>Release ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Open Date</th>
                        <th>Dependency Date</th>
                        <th>Content Date</th>
                        <th>RTM Date(s)</th>
                        <th>Manager</th>
                        <th>Author</th>
                </tr>
              </thead>



              <tbody>

              <?php

$sql = "SELECT * from releases ORDER BY rtm_date ASC;";
$result = $db->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        $appName = $row["name"];
                        list($name,$ver) = explode(" ", $appName);
                        
                          $sql2 = "SELECT DISTINCT app_id as appID FROM (select distinct concat(app_name, concat(' ', app_version)) as name, app_id from sbom ) as subquery where name ='".$appName."' Limit 1;";
                                $result2 = $db->query($sql2);

                                echo "<td>".$row["app_id"]."</td>";
                                echo '<td>'.$row["id"].'</td>';

                                if ($result2->num_rows > 0) {
                                  while($row2 = $result2->fetch_assoc()) {
                                    $id = $row2["appID"];
                                  }
                                  echo '<td><a href="scanner_sbom_tree.php?id='.$id.'">'.$appName.' </a> </span> </td>';
                                }//end if
                                else {
                                  echo '<td>'.$row["name"].' </span> </td>';
                                }//end else

                                echo '<td>'.$row["type"].'</td>
                                <td>'.$row["status"].'</td>
                                <td>'.$row["open_date"].' </span> </td>
                                <td>'.$row["dependency_date"].'</td>
                                <td>'.$row["freeze_date"].'</td>
                                <td>'.$row["rtm_date"].' </span> </td>
                                <td>'.$row["manager"].' </span> </td>
                                <td>'.$row["author"].' </span> </td>';
      
                                  $result2->close();
                                }
                              }
                              ?>

              </tbody>
              <tfoot>
                <tr>
                <th>Application ID</th>
                <th>Release ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Open Date</th>
                        <th>Dependency Date</th>
                        <th>Content Date</th>
                        <th>RTM Date(s)</th>
                        <th>Manager</th>
                        <th>Author</th>
                </tr>
              </tfoot>
        </table>


        <script type="text/javascript" language="javascript">
    $(document).ready( function () {
        
        $('#info').DataTable( {
            dom: 'lfrtBip',
            buttons: [
                'copy', 'excel', 'csv', 'pdf'
            ] }
        );

        $('#info thead tr').clone(true).appendTo( '#info thead' );
        $('#info thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        } );
    
        var table = $('#info').DataTable( {
            orderCellsTop: true,
            fixedHeader: true,
            retrieve: true
        } );
        
    } );

</script>

        

 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

  <?php include("./footer.php"); ?>
