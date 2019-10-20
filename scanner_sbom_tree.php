<?php
  $nav_selected = "SCANNER";
  $left_buttons = "YES";
  $left_selected = "SBOMTREE";

  include("./nav.php");
 ?>

<!--Imports-->
<link rel="stylesheet" href="tree_style.css" />
<!--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/css/jquery.treetable.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/css/jquery.treetable.theme.default.css" />
-->

<div class="right-content">
   <div class="container" id="container">
     <h3 style = "color: #01B0F1;">Scanner --> BOM Tree</h3>
     <div class="table-responsive">
       <div class="h2">
         <a href="#" onclick="$('#bom_treetable').treetable('expandAll'); return false;">Expand All</a>
         <a href="#" onclick="$('#bom_treetable').treetable('collapseAll'); return false;">Collapse All</a>
        </div>
        <table id = "bom_treetable" class = "table table-striped table-hover">
          <thead id = 'parent'>
            <th >Application Name</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
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
                $p_id = $p;
                echo "<tr data-tt-id = '".$p_id."' id = '".$app_name."' class = 'parent'>
                      <td > <button type='button' class='btn btn-danger' id = 'parent' > ".$app_name."</button></td>
                      <td/>
                      <td/>
                      <td/>
                      <td/>
                      </tr>";
                $p++;
                //Finds parent table data
                $sql_parent_table = "SELECT DISTINCT app_name, app_id, app_version, app_status from sbom where app_name = '".$app_name."' order by app_name;";
                $result_parent_table = $db->query($sql_parent_table);
                if ($result_parent_table->num_rows > 0) {
                  $pt_id=$p_id."-".$pt;
                  echo "<tr data-tt-id = '".$pt_id."'  data-tt-parent-id='".$p_id."' id = 'parent' class = 'parent'> 
                          <th scope='col'>".$app_name." ID</th> 
                          <th scope='col'>".$app_name." Version</th> 
                          <th scope='col'>".$app_name." Status</th>
                          <th/>
                          <th/>
                        </tr>";
                  while($row_parent_table = $result_parent_table->fetch_assoc()) {
                    $app_name = $row_parent_table["app_name"];
                    $app_id = $row_parent_table["app_id"];
                    $app_version = $row_parent_table["app_version"];
                    $app_status = $row_parent_table["app_status"];
                    $pt_id=$p_id."-".$pt;
                    echo "<tr data-tt-id = '".$pt_id."'  data-tt-parent-id='".$p_id."' class='parent' id = '".$app_name."'> 
                          <td> <button type='button' class='btn btn-success' id = 'parent' >".$app_id."</button></td> 
                          <td class = 'h4'>".$app_version."</td>
                          <td class = 'h4'>".$app_status."</td>
                          <td/>
                          <td/>
                          </tr>";
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
                      $c_id=$pt_id."-".$c;
                      echo "<tr data-tt-id = '".$c_id."' data-tt-parent-id='".$pt_id."'  id = 'child' class = 'child'> 
                            <th>".$app_name." Component Name</th>
                            <th/>
                            <th/>
                            <th/>
                            <th/>
                            </tr>";
                      while($row_child = $result_child->fetch_assoc()) {
                        $cmp_name = $row_child["cmp_name"];
                        $c_id=$pt_id."-".$c;
                        echo "<tr data-tt-id = '".$c_id."' data-tt-parent-id='".$pt_id."' id = '".$app_name."-".$cmp_name."' class = 'child'>
                              <td> <button type='button' class='btn btn-warning' id = 'child' >".$cmp_name."</button></td>
                              <td/>
                              <td/>
                              <td/>
                              <td/>
                              </tr>";
                        $c++;
                        //find child table data
                        $sql_child_table = "SELECT cmp_name, cmp_id, cmp_type, cmp_version, cmp_status, notes from sbom 
                                            where app_name = '".$app_name."' 
                                            and app_id = '".$app_id."' 
                                            and app_version = '".$app_version."' 
                                            and app_status = '".$app_status."' 
                                            and cmp_name = '".$cmp_name."'; ";
                        $result_child_table= $db->query($sql_child_table);
                        if ($result_child_table->num_rows > 0) {
                          // output data of child table
                          $ct_id = $c_id."-".$ct;
                          echo "<tr data-tt-id = '".$ct_id."' data-tt-parent-id='".$c_id."' id = 'child' class = 'child'> 
                                <th scope='col'>".$cmp_name." ID</th> 
                                <th scope='col'>".$cmp_name." Version</th> 
                                <th scope='col'>".$cmp_name." Status</th> 
                                <th scope='col'>".$cmp_name." Type</th> 
                                <th scope='col'>Notes</th>
                                </tr>";
                          while($row_child_table = $result_child_table->fetch_assoc()) {
                            $cmp_name = $row_child_table["cmp_name"];
                            $cmp_id = $row_child_table["cmp_id"];
                            $cmp_version = $row_child_table["cmp_version"];
                            $cmp_status = $row_child_table["cmp_status"];
                            $cmp_type = $row_child_table["cmp_type"];
                            $notes = $row_child_table["notes"];
                            $ct_id = $c_id."-".$ct;
                            echo "<tr data-tt-id = '".$ct_id."' data-tt-parent-id='".$c_id."' class = 'child' id = '".$app_name."-".$cmp_name."'> 
                                  <td> <button type='button' class='btn btn-warning' id = 'child'>".$cmp_id."</button></td>
                                  <td class = 'h4'>".$cmp_version."</td> 
                                  <td class = 'h4'>".$cmp_status."</td> 
                                  <td class = 'h4'>".$cmp_type."</td> 
                                  <td class = 'h4'>".$notes."</td> 
                                  </tr>";
                            $ct++;
                          } 
                          // output data of child
                          $sql_gchild = "SELECT distinct request_id from sbom  
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
                            // output data of grandchild
                            $gc_id=$ct_id."-".$gc;
                            echo "<tr data-tt-id = '".$gc_id."' data-tt-parent-id='".$ct_id."' id = 'grandchild' class = 'grandchild'> 
                                  <th>".$cmp_name." Request ID</th>
                                  <th/>
                                  <th/>
                                  <th/>
                                  <th/>
                                  </tr>";
                            while($row_gchild = $result_gchild->fetch_assoc()) {
                              $request_id= $row_gchild["request_id"];
                              $gc_id=$ct_id."-".$gc;
                              echo "<tr data-tt-id = '".$gc_id."' data-tt-parent-id='".$ct_id."' id = '".$app_name."-".$cmp_name."-".$request_id."' class = 'grandchild'> 
                                    <td><button type='button' class='btn btn-success' id = 'grandchild'>".$request_id."</td> 
                                    <td/>
                                    <td/>
                                    <td/>
                                    <td/>
                                    </tr>";
                              $gc++;
                              //find child table data
                              $sql_gchild_table = "SELECT request_id, request_step, request_status, DATE_FORMAT(request_date, \"%m/%d/%y\") as request_date from sbom 
                                                    where app_name = '".$app_name."' 
                                                    and app_id = '".$app_id."' 
                                                    and app_version = '".$app_version."' 
                                                    and app_status = '".$app_status."' 
                                                    and cmp_name = '".$cmp_name."' 
                                                    and cmp_id = '".$cmp_id."' 
                                                    and cmp_version = '".$cmp_version."' 
                                                    and cmp_status = '".$cmp_status."' 
                                                    and request_id = '".$request_id."' ; ";
                              $result_gchild_table= $db->query($sql_gchild_table);
                              if ($result_gchild_table->num_rows > 0) {
                                      // output data of grandchild table
                                      $gct_id = $gc_id."-".$gct;
                                      echo "<tr data-tt-id = '".$gct_id."' data-tt-parent-id='".$gc_id."' id = 'grandchild' class = 'grandchild'> 
                                            <th scope='col'>".$request_id." Date</th> 
                                            <th scope='col'>".$request_id." Step</th>
                                            <th scope='col'>".$request_id." Status</th>
                                            <th/>
                                            <th/>
                                            </tr>";
                                      while($row_gchild_table = $result_gchild_table->fetch_assoc()) {
                                        $request_step = $row_gchild_table["request_step"];
                                        $request_status = $row_gchild_table["request_status"];
                                        $request_date= $row_gchild_table["request_date"];
                                        $gct_id = $gc_id."-".$gct;
                                        echo "<tr data-tt-id = '".$gct_id."' data-tt-parent-id='".$gc_id."' class='grandchild' id = '".$app_name."-".$cmp_name."-".$request_id."'> 
                                              <td class = 'h4'>".$request_date."</td> 
                                              <td class = 'h4'>".$request_step."</td>
                                              <td class = 'h4'>".$request_status."</td>
                                              <td/>
                                              <td/>
                                              </tr>";
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
                    echo "<tr data-tt-id = 'No Results'> <td>No Results Found</td><td></td><td></td><td></td><td></td> </tr>";
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
                </script>
