<?php
  $nav_selected = "SCANNER";
  $left_buttons = "YES";
  $left_selected = "SOFTWAREBOM";

  include("./nav.php");
  
  //PDO connection
  $servername = 'localhost';
  $dbname = 'bom';
  $username = 'root';
  $password = '';
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
?>

<?php
  $cookie_name = 'preference';
  global $pref_err;

  function getBoms($db) {
    $sql = "SELECT * from sbom;";
    $result = $db->query($sql);
    
    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        echo '<tr>
          <td>'.$row["row_id"].'</td>
          <td><a class="btn" href="scanner_sbom_tree.php?id='.$row["app_id"].'">'.$row["app_id"].' </a> </td>
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
          <td>'.$row["requestor"].'</td>
          <td>'.$row["color"].'</td>
        </tr>';
      }//end while
    }//end if
    else {
      echo "0 results";
    }//end else
    $result->close();
  }

  if(isset($_POST['get']) && !isset($_COOKIE[$cookie_name])) {
    $pref_err = "You don't have BOMS saved.";
  }
?>
  
<!-- Display error if user retrieves preferences w/o any cookies set-->
<?php echo '<p 
  style="font-size: 2.5rem; 
  text-align: center; 
  background-color: red; 
  color: white;">'.$pref_err.'</p>'
?>
  <div class="right-content">
    <div class="container">
      <h3 style = "color: #01B0F1;">Scanner --> Software BOM </h3>
      <!-- Form to retrieve user preference -->
      <form id='get-form' name='get-form' method='post' action='' style='display: inline;'>
        <button type='submit' name='get' value='submit'
        style='background: #01B0F1;
          color: white;
          border: none;
          border-radius: 10px;
          padding: 1rem;
          margin-right: 1rem;'>Show My BOMS</button>
      </form>
      <form id='getall-form' name='getall-form' method='post' action='' style='display: inline;'>
        <button type='submit' name='getall' value='submit' 
        style='background: #01B0F1;
          color: white;
          border: none;
          border-radius: 10px;
          padding: 1rem;'>Show All BOMS</button>
      </form>

      <h3><img src="images/sbom_list.png" style="max-height: 35px;" />BOM List</h3>
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
            <th>Requestor</th>
            <th>Color</th>
          </tr>
        </thead>
      <tbody>
      <?php
        /*----------------- GET PREFERENCE COOKIE -----------------*/
        //get user preferred BOMS 
        if(isset($_POST['get']) && isset($_COOKIE[$cookie_name])) {
          $prep = rtrim(str_repeat('?,', count(json_decode($_COOKIE[$cookie_name]))), ',');
          $sql = 'SELECT * FROM sbom WHERE app_id IN ('.$prep.')';
          $pref = $pdo->prepare($sql);
          $pref->execute(json_decode($_COOKIE[$cookie_name]));

          while($row = $pref->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>
              <td>'.$row["row_id"].'</td>
              <td><a class="btn" href="scanner_sbom_tree.php?id='.$row["app_id"].'">'.$row["app_id"].' </a> </td>
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
              <td>'.$row["requestor"].'</td>
              <td>'.$row["color"].'</td>
            </tr>';
          }
        }elseif(isset($_POST['get']) && !isset($_COOKIE[$cookie_name])) {
          $pref_err = "You don't have BOMS saved.";
          getBoms($db);
        }//get all BOMS
        elseif(isset($_POST['getall'])) {
          getBoms($db);
        }//if no preference cookie is set show all BOMS
        else { 
          getBoms($db);
        }
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
          <th>Requestor</th>
          <th>Color</th>
        </tr>
      </tfoot>
      </table>
      
 <script type="text/javascript" language="javascript">
    $(document).ready( function () {
    $('#info').DataTable( {
      dom: 'lfrtBip',
      buttons: ['copy', 'excel', 'csv', 'pdf']
    } );

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
