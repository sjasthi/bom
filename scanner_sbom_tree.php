<?php
  $nav_selected = "SCANNER";
  $left_buttons = "YES";
  $left_selected = "SBOMTREE";

  include("./nav.php");
 ?>

<!--Imports-->
<link rel="stylesheet" href="tree_style.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/css/jquery.treetable.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treetable/3.2.0/css/jquery.treetable.theme.default.css" />


<div class="right-content">
    <div class="container" id="container">
        <h3 style="color: #01B0F1;">Scanner --> BOM Tree</h3>
        <nav class="navbar">
            <div class="container-fluid">
                <ul class="nav navbar-nav" style='font-size: 18px;'>
                    <li><a href="#" onclick="$('#bom_treetable').treetable('expandAll'); return false;"><span
                                class="glyphicon glyphicon-chevron-down"></span>Expand All</a></li>
                    <li class="active"><a href="#"
                            onclick="$('#bom_treetable').treetable('collapseAll'); return false;"><span
                                class="glyphicon glyphicon-chevron-up"></span>Collapse All</a></li>
                                <!--Place Holder for future iteration
                                <li><a href="#">Color</a></li>
                    <li><a href="#">No Color</a></li>
                    <li><a href="#" onclick="$('#bom_treetable').treetable('collapseAll'); $('.child').toggle(); $('.grandchild').toggle();">Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'> </span>Red</a>
                    </li>
                    <li><a href="#" onclick="$('#bom_treetable').treetable('expandAll'); $('.grandchild').toggle();">Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'></span>Red and
                            <span class="glyphicon glyphicon-tint" style='color:#ffd966;'></span>Yellow</a></li>
                    
                    -->
                   
                        <div class="input-group">
                            <input type="text" id="input" class="form-control" placeholder="Where Used">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </div>
                        </div>
                   

                </ul>
            </div>
        </nav>
     <div class="table-responsive">
       <div class="h4">
         
         
       
        <table id = "bom_treetable" class = "table table-hover">
          <thead class = 'h4'>
            <th >Name</th>
            <th>Version</th>
            <th>Status</th>
            <th>Type</th>
            <th>Notes</th>
          </thead>
          <tbody>
            <?php
            //finds parent data
            $sql_parent = "SELECT DISTINCT app_name, app_id, app_version, app_status from sbom order by app_name;";
            $result_parent = $db->query($sql_parent);
            $p=1;
            $c=1;
            $gc=1;
            if ($result_parent->num_rows > 0) {
              while($row_parent = $result_parent->fetch_assoc()) {
                $app_name = $row_parent["app_name"];
                $app_id = $row_parent["app_id"];
                $app_version = $row_parent["app_version"];
                $app_status = $row_parent["app_status"];
                $p_id = $p;
                echo "<div id = 'parent'>
                      <tr data-tt-id = '".$p_id."' >
                      <td class='text-capitalize'> <button type='button' class = 'parent' > ".$app_name."
                      <br/>Application ID: ".$app_id."</button></td> 
                      <td >".$app_version."</td>
                      <td class='text-capitalize'>".$app_status."</td>
                      <td/>
                      <td/>
                      </tr>";
                $p++;
                // output data of child
                  $sql_child = "SELECT cmp_name, cmp_id, cmp_type, cmp_version, cmp_status, notes from sbom 
                                  where app_name = '".$app_name."' 
                                  and app_id = '".$app_id."' 
                                  and app_version = '".$app_version."' 
                                  and app_status = '".$app_status."' ; ";
                  $result_child = $db->query($sql_child);
                  if ($result_child->num_rows > 0) {
                    // output data of child
                    while($row_child = $result_child->fetch_assoc()) {
                      $cmp_name = $row_child["cmp_name"];
                      $cmp_id = $row_child["cmp_id"];
                      $cmp_version = $row_child["cmp_version"];
                      $cmp_status = $row_child["cmp_status"];
                      $cmp_type = $row_child["cmp_type"];
                      $notes = $row_child["notes"];
                      $c_id=$p_id."-".$c;
                      echo "<div id = 'child'>
                      <tr data-tt-id = '".$c_id."' data-tt-parent-id='".$p_id."' >
                        <td class='text-capitalize'> &nbsp; &nbsp; &nbsp; &nbsp; <button type='button'  class = 'child'> ".$cmp_name."
                         <br/>Component ID: ".$cmp_id."</button></td>
                            <td >".$cmp_version."</td> 
                            <td class='text-capitalize'>".$cmp_status."</td> 
                            <td class='text-capitalize'>".$cmp_type."</td> 
                            <td class='text-capitalize'>".$notes."</td> 
                            </tr>";
                      $c++;
                      // output data of child
                        $sql_gchild = "SELECT request_id, request_step, request_status, DATE_FORMAT(request_date, \"%m/%d/%y\") as request_date from sbom  
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
                          while($row_gchild = $result_gchild->fetch_assoc()) {
                            $request_id= $row_gchild["request_id"];
                            $request_date= $row_gchild["request_date"];
                            $request_step= $row_gchild["request_step"];
                            $request_status= $row_gchild["request_status"];
                            $gc_id=$c_id."-".$gc;
                            echo "<div id = 'grandchild'>
                                  <tr data-tt-id = '".$gc_id."' data-tt-parent-id='".$c_id."' > 
                                  <td > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<button class = 'grandchild' style = 'cursor: none;'>Request ID: ".$request_id."</button></td> 
                                 <td class='text-capitalize'>".$request_step."</td>
                                  <td class='text-capitalize'>".$request_status."</td>
                                  <td/>
                                  <td>Request Date: ".$request_date."</td>
                                  </tr>
                                  </div>
                                  </div>
                                  </div>";
                            $gc++;
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
            echo "<tr data-tt-id = 'No Results'> <td>No Results Found</td><td></td><td></td><td></td><td></td> </tr>";
          }
          ?>
          </tbody>
        </table>
        </div>
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

        $("#input").keyup(function () {
    var value = this.value.toLowerCase().trim();

    $("table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            var id = $(this).text().toLowerCase().trim();
            var not_found = (id.indexOf(value) == -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;
        });
    });
});
        </script>
