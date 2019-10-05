<?php
  $nav_selected = "SCANNER";
  $left_buttons = "YES";
  $left_selected = "SBOMTREE";

  include("./nav.php");
 ?>

<!--Imports-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/css/jquery.treetable.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/css/jquery.treetable.theme.default.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/jquery.treetable.js"></script>

 <div class="right-content">
    <div class="container" id="container">
      <h3 style = "color: #01B0F1;">Scanner --> BOM Tree</h3>

      <script type="text/javascript">
        //We only use php to pull the rows from the sbom table and store them into an array
        let sbomArray = [];

        <?php
        $sql = "SELECT * from sbom";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo "sbomArray.push(", json_encode($row), ");\r\n";
          }
        }else {
          echo "0 results";
        }

        $result->close();
        ?>

        //Build a very nested Map
        //I did this to simulate a tree datastructure w/o actually implementing a tree datastructure (take that, ICS-340)
        let tree = new Map();        
        sbomArray.forEach(row => {
          //If the tree doesn't have the app_name, add it
          if(!tree.has(row['app_name'])){
            tree.set(row['app_name'], new Map());
          }

          //if the tree doesn't have the app_id of an app_name, add it
          if(!tree.get(row['app_name']).has(row['app_id'])){
            tree.get(row['app_name']).set(row['app_id'], new Map());
          }

          //if the tree doesn't have the cmp_name of an app_id of an app_name, add it
          if(!tree.get(row['app_name']).get(row['app_id']).has(row['cmp_name'])){
            tree.get(row['app_name']).get(row['app_id']).set(row['cmp_name'], row);
          }
        });

        //Build a table that the jQuery treetable plugin can understand
        let container = document.getElementById('container');

        let root = document.createElement('table');
        let tbody = document.createElement('tbody');

        root.appendChild(tbody);

        //These three variables keep track of unique id's and parent:child relationships.
        let idCount = 1;
        let app_nameParentId = -1;
        let app_idParentId = -1;


        //Three nested for loops to generate the table and relationships between rows. TC is O(n^2 * log n)..... Gross.

        //Loop over app_name
        tree.forEach((value, key) => {
          let tr = document.createElement('tr');
          tr.setAttribute('data-tt-id', idCount);
          tbody.appendChild(tr);

          let data = document.createElement('td');
          data.innerHTML = key;
          tr.appendChild(data);

          app_nameParentId = idCount++;

          //loop over app_id
          value.forEach((value, key) => {
            tr = document.createElement('tr');
            tr.setAttribute('data-tt-id', idCount);
            tr.setAttribute('data-tt-parent-id', app_nameParentId);
            tbody.appendChild(tr);

            let data = document.createElement('td');
            data.innerHTML = key;
            tr.appendChild(data);

            app_idParentId = idCount++;

            //loop over cmp_name
            value.forEach((value, key) => {
              tr = document.createElement('tr');
              tr.setAttribute('data-tt-id', idCount);
              tr.setAttribute('data-tt-parent-id', app_idParentId);
              tbody.appendChild(tr);

              let data = document.createElement('td');
              //data.innerHTML = Object.entries(value);
              data.innerHTML = value['cmp_name'];
              tr.appendChild(data);
            });
          });
        });

        root.setAttribute('id', 'maintreetable');
        container.appendChild(root);

        //Params for the treetable
        let params = {
          expandable: true,
          clickableNodeNames: true
        };

        //Generate tree table
        $("#maintreetable").treetable(params);
        </script>
    </div>
</div>
<div>
  <table id = 'bom_treetable'>
    <thead >
      <th>Name</th>
      <th>ID</th>
      <th>Version</th>
      <th>Status</th>
      <th>Notes</th>
    </thead>
    <tbody>
      <?php
      //finds parent data
      $sql_parent = "SELECT DISTINCT app_name from sbom;";
      $result_parent = $db->query($sql_parent);
      $p = 1;
      if ($result_parent->num_rows > 0) {
        while($row_parent = $result_parent->fetch_assoc()) {
          $app_name = $row_parent["app_name"];
          echo "<tr data-tt-id = '".$p."'>";
          echo "<td>".$app_name."</td>";
          echo "</tr>";
          //Finds child data
          $sql_child = "SELECT DISTINCT app_id, app_version, app_status from sbom 
                          where app_name = '".$app_name."';";
          $result_child = $db->query($sql_child);
          $c = 1;
          if ($result_child->num_rows > 0) {
            // output data of child
            while($row_child = $result_child->fetch_assoc()) {
              $app_id = $row_child["app_id"];
              $app_version = $row_child["app_version"];
              $app_status = $row_child["app_status"];
              echo "<tr data-tt-id = '".$p."-".$c."' data-tt-parent-id='".$p."'>";
              echo "<td> </td>";
              echo "<td>".$app_id." ";
              echo "<td>".$app_version." ";
              echo "<td>".$app_status;
              echo "</tr>";
            } $c++;
            $result_child -> close();
          } else {
            echo "<tr data-tt-id = '".$p."-".$c."' data-tt-parent-id='".$p."'>";
              echo "<td> </td>";
              echo "<td> </td>";
              echo "<td> </td>";
              echo "</tr>";
          }
          $sql_gchild = "SELECT DISTINCT cmp_type from sbom 
                          where app_name = '".$app_name."' 
                          and app_id = '".$app_id."' 
                          and app_version = '".$app_version."' 
                          and app_status = '".$app_status."';";
          $result_gchild = $db->query($sql_gchild);
          $gc = 1;
          if ($result_gchild->num_rows > 0) {
            // output data of grand child
            while($row_gchild = $result_gchild->fetch_assoc()) {
              $cmp_type = $row_gchild["cmp_type"];
              echo "<tr data-tt-id = '".$p."-".$c."-".$gc."' data-tt-parent-id='".$p."'>";
              echo "<td>".$cmp_type."</td>";
              echo "</tr>";
            } $gc++;
            $result_gchild -> close();
          } else {
            echo "<tr data-tt-id = '".$p."-".$c."-".$gc."' data-tt-parent-id='".$p."'>";
              echo "<td> </td>";
              echo "</tr>";
          }
          $sql_ggchild = "SELECT DISTINCT cmp_name from sbom 
                            where app_name = '".$app_name."' 
                            and app_id = '".$app_id."' 
                            and app_version = '".$app_version."' 
                            and app_status = '".$app_status."'
                            and cmp_type = '".$cmp_type."';";
          $result_ggchild = $db->query($sql_ggchild.";");
          $ggc = 1;
          if ($result_ggchild->num_rows > 0) {
            // output data of great grand child
            while($row_ggchild = $result_ggchild->fetch_assoc()) {
              $cmp_name = $row_ggchild["cmp_name"];
              echo "<tr data-tt-id = '".$p."-".$c."-".$gc,"-".$ggc."' data-tt-parent-id='".$p."'>";
              echo "<td>".$cmp_name."</td>";
              echo "</tr>";
            } $ggc++;
            $result_ggchild -> close();
          } else {
            echo "<tr data-tt-id = '".$p."-".$c."-".$gc,"-".$ggc."' data-tt-parent-id='".$p."'>";
            echo "<td> </td>";
            echo "</tr>";
          }
          $sql_gggchild = "SELECT DISTINCT cmp_id, cmp_version, cmp_status, notes from sbom 
                            where app_name = '".$app_name."' 
                            and app_id = '".$app_id."' 
                            and app_version = '".$app_version."' 
                            and app_status = '".$app_status."'
                            and cmp_type = '".$cmp_type."'
                            and cmp_name = '".$cmp_name."';";
          $result_gggchild = $db->query($sql_gggchild);
          $gggc = 1;
          if ($result_gggchild->num_rows > 0) {
            // output data of great grand child
            while($row_gggchild = $result_gggchild->fetch_assoc()) {
              $cmp_id = $row_gggchild["cmp_id"];
              $cmp_version = $row_gggchild["cmp_version"];
              $cmp_status = $row_gggchild["cmp_status"];
              $notes = $row_gggchild["notes"];
              echo "<tr data-tt-id = '".$p."-".$c."-".$gc."-".$ggc."-".$gggc."' data-tt-parent-id='".$p."'>";
              echo "<td> </td>";
              echo "<td>".$cmp_id."</td>";
              echo "<td>".$cmp_version."</td>";
              echo "<td>".$cmp_status."</td>";
              echo "<td>".$notes."</td>";
              echo "</tr>";
            } $gggc++;
            $result_gggchild -> close();
          } else {
            echo "<tr data-tt-id = '".$p."-".$c."-".$gc."-".$ggc."-".$gggc."' data-tt-parent-id='".$p."'>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "<td> </td>";
            echo "</tr>";
          }
        } 
        $p++;
        $result_parent->close();
      }
      else{
        echo "<tr data-tt-id = '".$p."'>";
        echo "<td>No Results Found</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
  <script>
    //Params for the treetable
    let sbom_params = {
          expandable: true,
          clickableNodeNames: true
        };
  $("#bom_treetable").treetable(sbom_params);
  
  </script>
</div>
<?php include("./footer.php"); ?>
