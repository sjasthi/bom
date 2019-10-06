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
<div>
  <table id = 'bom_treetable'>
      <caption>
        <a href="#" onclick="$('#bom_treetable').treetable('expandAll'); return false;">Expand all</a>
        <a href="#" onclick="$('#bom_treetable').treetable('collapseAll'); return false;">Collapse all</a>
      </caption>
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
      $sql_parent = "SELECT DISTINCT app_name, app_id, app_version from sbom order by app_name;";
      $result_parent = $db->query($sql_parent);
      if ($result_parent->num_rows > 0) {
        while($row_parent = $result_parent->fetch_assoc()) {
          $app_name = $row_parent["app_name"];
          $app_id = $row_parent["app_id"];
          $app_version = $row_parent["app_version"];
          $p_id = $app_name."-".$app_id."-".$app_version;
          echo "<tr data-tt-id = '".$p_id."'>";
          echo "<td>".$app_name." (".$app_id.") Version: .".$app_version."</td>";
          echo "</tr>";
          //Finds child data
          $sql_child = "SELECT distinct cmp_type from sbom 
                        where app_name = '".$app_name."' 
                        and app_id = '".$app_id."' 
                        and app_version = '".$app_version."'
                        order by cmp_type;";
                        $result_child = $db->query($sql_child);
                        if ($result_child->num_rows > 0) {
                          // output data of child
                          while($row_child = $result_child->fetch_assoc()) {
                            $cmp_type = $row_child["cmp_type"];
                            $c_id=$p_id."-".$cmp_type;
                            echo "<tr data-tt-id = '".$c_id."' data-tt-parent-id='".$p_id."'>";
                            echo "<td>".$cmp_type."</td>";
                            echo "</tr>";
                            //find grandchild data
                            $sql_gchild = "SELECT * from sbom 
                                            where app_name = '".$app_name."' 
                                            and app_id = '".$app_id."' 
                                            and app_version = '".$app_version."' 
                                            and cmp_type = '".$cmp_type."'
                                            order by app_name, cmp_type, cmp_name; ";
                                            $result_gchild = $db->query($sql_gchild);
                                            if ($result_gchild->num_rows > 0) {
                                              // output data of grandchild
                                              while($row_gchild = $result_gchild->fetch_assoc()) {
                                                $cmp_name = $row_gchild["cmp_name"];
                                                $cmp_id = $row_gchild["cmp_id"];
                                                $cmp_version = $row_gchild["cmp_version"];
                                                $cmp_status = $row_gchild["cmp_status"];
                                                $notes = $row_gchild["notes"];
                                                $gc_id=$c_id."-".$cmp_name."-".$cmp_id."-".$cmp_version."-".$cmp_status;
                                                echo "<tr data-tt-id = '".$gc_id."' data-tt-parent-id='".$c_id."'>";
                                                echo "<td>".$cmp_name."</td>";
                                                echo "<td>".$cmp_id."</td>";
                                                echo "<td>".$cmp_version."</td>";
                                                echo "<td>".$cmp_status."</td>";
                                                echo "<td>".$notes."</td>";
                                                echo "</tr>";
                                              } 
                                              $result_gchild -> close();
                                            }
                                          } 
                                          $result_child -> close();
                                        } 
                                      } 
                                      $result_parent->close();
                                    }
                                    else{
                                      echo "<tr data-tt-id = 'No Results'>";
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
          $("#bom_treetable tbody").on("mousedown", "tr", function() {
            $(".selected").not(this).removeClass("selected");
            $(this).toggleClass("selected");
            });  
    </script>
  </div>
</div>
<?php include("./footer.php"); ?>
