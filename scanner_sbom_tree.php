<?php
  $nav_selected = "SCANNER";
  $left_buttons = "YES";
  $left_selected = "SBOMTREE";

  include("./nav.php");
 ?>

<!--Imports-->
<link rel="stylesheet" href="tree_style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/css/jquery.treetable.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/css/jquery.treetable.theme.default.css">

<div class="right-content">
    <div class="container" id="container">
      <h3 style = "color: #01B0F1;">Scanner --> BOM Tree</h3>
<div>
  <table id = "bom_treetable" class = "treetable">
      <caption>
        <a href="#" onclick="$('#bom_treetable').treetable('expandAll'); return false;">Expand all</a>
        <a href="#" onclick="$('#bom_treetable').treetable('collapseAll'); return false;">Collapse all</a>
      </caption>
      <thead id='bom_header'>
      <th></th>
        <th>ID</th>
        <th>Version</th>
        <th>Status</th>
        <th>Notes</th>
      </thead>
      <tbody>
      <?php
      //finds parent data
      $sql_parent = "SELECT DISTINCT app_name from sbom order by app_name;";
      $result_parent = $db->query($sql_parent);
      $p=1;
      $pt=1;
      $c=1;
      $ct=1;
      $gc=1;
      $gct=1;
      
      if ($result_parent->num_rows > 0) {
        while($row_parent = $result_parent->fetch_assoc()) {
          $app_name = $row_parent["app_name"];
          $p_id = $app_name ."-".$p;
          echo "<tr data-tt-id = '".$p_id."' >";
          echo "<td id = 'parent'>".$app_name."</td>";
          echo "</tr>";
          $p++;
          
          //Finds parent table data
            $sql_parent_table = "SELECT DISTINCT app_name, app_id, app_version, app_status from sbom where app_name = '".$app_name."' order by app_name;";
            $result_parent_table = $db->query($sql_parent_table);
            
            if ($result_parent_table->num_rows > 0) {
              while($row_parent_table = $result_parent_table->fetch_assoc()) {
                $app_name = $row_parent_table["app_name"];
                $app_id = $row_parent_table["app_id"];
                $app_version = $row_parent_table["app_version"];
                $app_status = $row_parent_table["app_status"];
                $pt_id=$p_id."-".$pt;
                echo "<tr data-tt-id = '".$pt_id."'  data-tt-parent-id='".$p_id."' id = 'parent_table'>";
                echo "<td id = 'parent_table'>".$app_name."</td>";
                echo "<td id = 'parent_table'>".$app_id."</td><td id = 'parent_table'>".$app_version."</td><td id = 'parent_table'>".$app_status."</td>";
                echo "</tr>";
                $pt++;
                
                // output data of child
                $sql_child = "SELECT distinct cmp_name from sbom 
                        where app_name = '".$app_name."' 
                        and app_id = '".$app_id."' 
                        and app_version = '".$app_version."'
                        and app_status = '".$app_status."'
                        order by cmp_name;";
                        $result_child = $db->query($sql_child);
                        
                        if ($result_child->num_rows > 0) {
                          // output data of child
                          while($row_child = $result_child->fetch_assoc()) {
                            $cmp_name = $row_child["cmp_name"];
                            $c_id=$p_id."-".$cmp_name."-".$c;
                            echo "<tr data-tt-id = '".$c_id."' data-tt-parent-id='".$pt_id."' >";
                            echo "<td id = 'child'>".$cmp_name."</td>";
                            echo "</tr>";
                            $c++;
                            //find child table data
                            $sql_child_table = "SELECT cmp_name, cmp_id, cmp_type, cmp_version, cmp_status, notes from sbom 
                                            where app_name = '".$app_name."' 
                                            and app_id = '".$app_id."' 
                                            and app_version = '".$app_version."' 
                                            and app_status = '".$app_status."'
                                            and cmp_name = '".$cmp_name."'
                                            ; ";
                                            $result_child_table= $db->query($sql_child_table);
                                            
                                            if ($result_child_table->num_rows > 0) {
                                              // output data of child table
                                              while($row_child_table = $result_child_table->fetch_assoc()) {
                                                $cmp_name = $row_child_table["cmp_name"];
                                                $cmp_id = $row_child_table["cmp_id"];
                                                $cmp_version = $row_child_table["cmp_version"];
                                                $cmp_status = $row_child_table["cmp_status"];
                                                $notes = $row_child_table["notes"];
                                                $ct_id = $c_id."-".$ct;
                                                echo "<tr data-tt-id = '".$ct_id."' data-tt-parent-id='".$c_id."' id = 'child_table'>";
                                                echo "<td id = 'child_table'>".$cmp_name."</td>";
                                                echo "<td id = 'child_table'>".$cmp_id."</td>";
                                                echo "<td id = 'child_table'>".$cmp_version."</td>";
                                                echo "<td id = 'child_table'>".$cmp_status."</td>";
                                                echo "<td id = 'child_table'>".$notes."</td>";
                                                echo "</tr>";
                                                $ct++;
                                                
                                              } 
                                                              // output data of child
                $sql_gchild = "SELECT distinct request_date from sbom 
                where app_name = '".$app_name."' 
                and app_id = '".$app_id."' 
                and app_version = '".$app_version."'
                and app_status = '".$app_status."'
                and cmp_name = '".$cmp_name."' 
                and cmp_id = '".$cmp_id."' 
                and cmp_version = '".$cmp_version."'
                and cmp_status = '".$cmp_status."';";
                $result_gchild = $db->query($sql_gchild);
                
                if ($result_gchild->num_rows > 0) {
                  // output data of child
                  while($row_gchild = $result_gchild->fetch_assoc()) {
                    $request_date= $row_gchild["request_date"];
                    $gc_id=$request_date."-".$ct_id."-".$gc;
                    echo "<tr data-tt-id = '".$gc_id."' data-tt-parent-id='".$ct_id."'>";
                    echo "<td id = 'grandchild'>Request Date ".$request_date."</td>";
                    echo "</tr>";
                    $gc++;
                    //find child table data
                    $sql_gchild_table = "SELECT request_id, request_step, request_status, request_date from sbom 
                                    where app_name = '".$app_name."' 
                                    and app_id = '".$app_id."' 
                                    and app_version = '".$app_version."' 
                                    and app_status = '".$app_status."'
                                    and cmp_name = '".$cmp_name."'
                                    and cmp_id = '".$cmp_id."' 
                                    and cmp_version = '".$cmp_version."'
                                    and cmp_status = '".$cmp_status."'
                                    and request_date = '".$request_date."' 
                                    ; ";
                                    $result_gchild_table= $db->query($sql_gchild_table);
                                    
                                    if ($result_gchild_table->num_rows > 0) {
                                      // output data of child table
                                      while($row_gchild_table = $result_gchild_table->fetch_assoc()) {
                                        $request_step = $row_gchild_table["request_step"];
                                        $request_status = $row_gchild_table["request_status"];
                                        $request_id = $row_gchild_table["request_id"];
                                        $gct_id = $gc_id."-".$gct;
                                        echo "<tr data-tt-id = '".$gct_id."' data-tt-parent-id='".$gc_id."' id = 'grandchild_table'>";
                                        echo "<td id = 'grandchild_table'>Request Date ".$request_date."</td>";
                                        echo "<td id = 'grandchild_table'>".$request_id."</td>";
                                        echo "<td id = 'grandchild_table'>".$request_step."</td>";
                                        echo "<td id = 'grandchild_table'>".$request_status."</td>";
                                        echo "</tr>";
                                        $gct++;
                                      } 
                                      $result_gchild_table -> close();
                                    }
                                  } 
                                  $result_gchild -> close();
                                }
                                }
                                $result_child_table -> close();
                              }
                            } 
                            $result_child -> close();
                          }
                        } 
                        $result_parent_table -> close();
                      } 
                    
                    $result_parent->close();
                  }
                  else{
                                  echo "<tr data-tt-id = 'No Results'>";
                                  echo "<td>No Results Found</td><td></td><td></td><td></td><td></td>";
                                  echo "</tr>";
                                }
          ?>
        </tbody>
      </table>

  </div>
</div>
<?php include("./footer.php"); ?>
<script>
  //Params for the treetable
  let sbom_params = {
    expandable: true,
    clickableNodeNames: true
    };
    $("#bom_treetable").treetable(sbom_params);
    /*
    $("#bom_treetable tbody").on("mousedown", "tr", function() {
      $(".selected").not(this).removeClass("selected");
      $(this).toggleClass("selected");
      }); 
      */
      </script>
