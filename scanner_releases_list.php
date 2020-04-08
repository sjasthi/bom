<?php
  $nav_selected = "SCANNER"; 
  $left_buttons = "YES"; 
  $left_selected = "RELEASESLIST"; 
  //include("session.php");
  include("./nav.php");
  global $db;
?>
<?php
  global $count_err;


  function updateScope($db, $newScope)
  {
      $sql = "UPDATE preferences
              SET value = '$newScope'
              WHERE name = 'SYSTEM_BOMS';";
      $result = $db->query($sql);
  }

  /*----------------- SET PREFERENCE COOKIE -----------------*/
  $cookie_name = 'preference';
  $expire = strtotime('+1 year');

  //If user wants to use admin functions of release table then they must be logged in
  if (isset($_POST['saveScope'])) {
    include("session.php");
  }

  //Get selected apps & put into cookie or system scope if the user is logged in as an admin
  if(isset($_POST['save']) && isset($_POST['app'])) {
    $apps = $_POST['app'];

    if(count($apps) > 5) {
      $count_err = "You can't select more than 5 BOMS."; 

    }else {
      echo '<p 
        style="font-size: 2.5rem; 
        text-align: center; 
        background-color: green; 
        color: white;">BOM preferences successfully saved.</p>';
        header("Refresh:1.75");
      $preference = $apps;
      $set_pref = setcookie($cookie_name, json_encode($preference), $expire); 
    }

  }elseif(isset($_POST['save']) && !isset($_POST['app'])) {
    if(!isset($apps)) {
      $count_err = "Please select at least one BOM."; 
    }
  }elseif(isset($_POST['saveScope']) && isset($_POST['app']) && isset($_SESSION['login_user']) && isset($_SESSION['admin'])) {
    $apps = $_POST['app'];

    if(count($apps) > 5) {
      $count_err = "You can't select more than 5 BOMS."; 

    }else {
      echo '<p 
        style="font-size: 2.5rem; 
        text-align: center; 
        background-color: green; 
        color: white;">Default BOM scope successfully set.</p>';
      $newScope = implode(",",$apps);
      updateScope($db, $newScope);  
    }
  } elseif (isset($_POST['saveScope']) && !isset($_POST['app']) && isset($_SESSION['login_user']) && isset($_SESSION['admin'])){
    if(!isset($apps)){
      $count_err = "You have set the system scope to be empty";
      $newScope = '';
      updateScope($db, $newScope);
    }
  } elseif (isset($_POST['saveScope']) && !isset($_SESSION['admin'])){
    echo '<p 
        style="font-size: 2.5rem; 
        text-align: center; 
        background-color: green; 
        color: white;">You must be login in as an administrator to use this function.</p>';
  }
  //if cookie is set, decode cookie into array
  if(isset($_COOKIE[$cookie_name])) {
    $cookie_arr = json_decode($_COOKIE[$cookie_name]);
  }
  
?>

<!-- Display error for preference form -->
<?php echo '<p 
  style="font-size: 2.5rem; 
  text-align: center; 
  background-color: red; 
  color: white;">'.$count_err.'</p>'
?>

  <div class="right-content">
    <div class="container">
      <h3 style = "color: #01B0F1;">Scanner -> System Releases List</h3>
      <h3><img src="images/releases.png" style="max-height: 35px;" />System Releases</h3>

      <!-- Form to set user preference -->
      <form id='app-form' name='app-form' method='post' action=''>
      <table id="info" cellpadding="0" cellspacing="0" border="0"
        class="datatable table table-striped table-bordered datatable-style table-hover"
        width="100%" style="width: 100px;">
        <thead>
          <tr id="table-first-row">
            <th>Select App</th>
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
            <th>Tag</th>
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
              $appName = str_replace(' ', '', $row["name"]);
              $sql2 = "SELECT DISTINCT app_id as appID FROM (select distinct concat(TRIM(app_name), 
                TRIM(app_version)) as name, app_id from sbom ) as subquery where name ='".$appName."' Limit 1;";
              $result2 = $db->query($sql2);

              //if a cookie is set keep the selected apps checkbox checked
              if(isset($_COOKIE[$cookie_name])) {
                if (in_array($row['app_id'], $cookie_arr, true)) {
                  echo "<td><input type='checkbox' name='app[]' value='".$row['app_id']."' checked></td>";
                }else {
                  echo "<td><input type='checkbox' name='app[]' value='".$row['app_id']."'></td>";
                }
              }//if no cookie is set, all checkboxes are unchecked by default 
              else {
                echo "<td><input type='checkbox' name='app[]' value='".$row['app_id']."'></td>";
              }

              echo '<td><a class="btn" href="scanner_sbom_tree_v2.php?id='.$row["app_id"].'">'.$row["app_id"].' </a> </td>';
              echo '<td><a class="btn" href="scanner_sbom_tree_v2.php?id='.$row["app_id"].'">'.$row["id"].' </a> </td>';

              if ($result2->num_rows > 0) {
                while($row2 = $result2->fetch_assoc()) {
                  $id = $row2["appID"];
                }
                echo '<td><a href="scanner_sbom_tree_v2.php?id='.$id.'">'.$row["name"].' </a> </span> </td>';

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
                <td>'.$row["author"].' </span> </td>
                <td>'.$row["tag"].' </span> </td>';
                $result2->close();
            }
          }
        ?>
        </tbody>
          <tfoot>
            <tr>
              <th>Select App</th>
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
              <th>Tag</th>
            </tr>
          </tfoot>
        </table>
        <button type='submit' name='save' value='submit'
        style='background: #01B0F1;
          color: white;
          border: none;
          border-radius: 10px;
          padding: 1rem;
          margin-right: 1rem;'>Set My BOMS</button>

        <button type='submit' name='saveScope' value='submit'
        style='background: #01B0F1;
          color: white;
          border: none;
          border-radius: 10px;
          padding: 1rem;
          margin-right: 1rem;'>Set System BOMS</button>
        </form>
        <!-- End preference form -->                       

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