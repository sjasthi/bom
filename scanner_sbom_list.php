<?php
  $nav_selected = "SCANNER";
  $left_buttons = "YES";
  $left_selected = "SOFTWAREBOM";

  include("./nav.php");
  
 ?>

 <div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Scanner --> Software BOM </h3>

        <h3><img src="images/releases.png" style="max-height: 35px;" />System Releases</h3>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                        <th>Row ID</th>
                        <th>App ID</th>
                        <th>App Name</th>
                        <th>App Version</th>
                        <th>CMP ID</th>
                        <th>CMP Name</th>
                        <th>CMP Version</th>
                        <th>CMP Type</th>
                        <th>App Status</th>
                        <th>CMP Status</th>
                        <th>Request ID</th>
                        <th>Request Date</th>
                        <th>Request Status</th>
                        <th>Request Step</th>                        
                        <th>Notes</th>
                </tr>
              </thead>
              <tbody>

              <?php

$sql = "SELECT * from sbom;";
$result = $db->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>'.$row["row_id"].'</td>
                                <td>'.$row["app_id"].' </span> </td>
                                <td>'.$row["app_name"].'</td>
                                <td>'.$row["app_version"].'</td>
                                <td>'.$row["cmp_id"].' </span> </td>
                                <td>'.$row["cmp_name"].'</td>
                                <td>'.$row["cmp_version"].'</td>
                                <td>'.$row["cmp_type"].' </span> </td>
                                <td>'.$row["app_status"].' </span> </td>
                                <td>'.$row["cmp_status"].' </span> </td>
                                <td>'.$row["request_id"].'</td>
                                <td>'.$row["request_date"].'</td>
                                <td>'.$row["request_status"].'</td>
                                <td>'.$row["request_step"].'</td>
                                <td>'.$row["notes"].' </span> </td>
                            </tr>';
                    }//end while
                }//end if
                else {
                    echo "0 results";
                }//end else

                 $result->close();
                ?>

              </tbody>
              <tfoot>
                <tr>
                        <th>Row ID</th>
                        <th>App ID</th>
                        <th>App Name</th>
                        <th>App Version</th>
                        <th>CMP ID</th>
                        <th>CMP Name</th>
                        <th>CMP Version</th>
                        <th>CMP Type</th>
                        <th>App Status</th>
                        <th>CMP Status</th>
                        <th>Request ID</th>
                        <th>Request Date</th>
                        <th>Request Status</th>
                        <th>Request Step</th> 
                        <th>Notes</th>
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
