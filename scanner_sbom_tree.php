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
                                <li><a href="#" id='color_noColor'><span id = 'no_color'>No </span>Color</a></li>
                                <li><a href="#" id ="showRed">Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'> </span>Red</a></li>
                                <li><a href="#" id = "showRedYellow"> Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'></span>Red and <span class="glyphicon glyphicon-tint" style='color:#ffd966;'></span>Yellow</a></li>
                                <li><div class="input-group">
                                  <input type="text" id="input" class="form-control" placeholder="Search..." >
                                  <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit"> <!--Makes the user feel better, otherwise no use.-->
                                    <i class="glyphicon glyphicon-search"></i>
                                  </button>
                                </div>
                              </div>
                            </li>
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
                echo "<tbody class= 'application' id = '".$app_id."'>
                      <tr data-tt-id = '".$p_id."' >
                      <td class='text-capitalize'> <button type='button' class = 'btn parent' > <span class = 'app_name' >".$app_name."</span>
                      <span class = 'id_br' ><br/>ID: ".$app_id."</span></button></td>
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
                      echo "
                      <tr data-tt-id = '".$c_id."' data-tt-parent-id='".$p_id."' class = 'component' >
                        <td class='text-capitalize'> &nbsp; &nbsp; &nbsp; &nbsp; <button type='button'  class = 'btn child'> <span class = 'cmp_name'>".$cmp_name."</span>
                         <span class = 'id_br' ><br/> ID: ".$cmp_id."</span></button></td>
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
                            echo "
                                  <tr data-tt-id = '".$gc_id."' data-tt-parent-id='".$c_id."' >
                                  <td > &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<button class = 'btn  grandchild'>Request ID: <span class = 'id_br' id = '".$request_id."'><br/> ".$request_id."</span></button></td>
                                 <td class='text-capitalize'>".$request_step."</td>
                                  <td class='text-capitalize'>".$request_status."</td>
                                  <td/>
                                  <td>Request Date: ".$request_date."</td>
                                  </tr>";
                            $gc++;
                          }
                          $result_gchild -> close();
                    }
                  }
                  $result_child -> close();
                } echo "</tbody>";
              }
            $result_parent->close();
          }
          else{
            echo "<tr data-tt-id = 'No Results'> <td>No Results Found</td><td></td><td></td><td></td><td></td> </tr>";
          }
          ?>
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

      //Function for Color/No Color Button
      $(document).ready(function(){
        $("#color_noColor").click(function(){
          $("#no_color").toggle();
        });
      });

      
      $(document).ready(function() {
        //input search for where used
        $('#input').on('keyup', function() {
          let input = $(this).val().toLowerCase();
          let cmp_nameInput = '', cmp_idInput = '';

          //Checks to see if the search terms are delineated, if yes, split input into cmp_nameInput and cmp_idInput
          //Feel free to add more delimiters to this array exxcept backslash ( \ ). I'm nearly 100% sure it'll break something, somewhere.
          let delimiterArray = [';', ':', ',', '|', '/', ' '];

          let usingDelimiter = delimiterArray.some(function(delimiter){
            if(input.includes(delimiter)){
              [cmp_nameInput, cmp_idInput] = input.split(delimiter, 2);
              return true;
            }
          });
          
          //if we're not using a delimiter, assume input is only for component name 
          if(!usingDelimiter){cmp_nameInput = input;}

          //Loops over each application
          $('#bom_treetable tbody').each(function() {

            let nameMatch = false, idMatch = false;

            //Check if any component name in the current application matches cmp_nameInput
            $(this).find(".component .cmp_name").each(function(){
              if($(this).text().toLowerCase().includes(cmp_nameInput)){
                nameMatch = true;
              }
            });

            //Check if any component id in the current application matches cmp_idInput
            $(this).find(".component").each(function(){
              if($(this).find("td:eq(1)").text().toLowerCase().includes(cmp_idInput)){
                idMatch = true;
              }
            });

            // 1: if (both search terms are used) and (both search terms aren't found)
            // 2: if (cmp_name is used) and (cmp_name isn't found)
            // 3: if (cmp_id is used) and (cmp_id isn't found)
            // 4: else ( search successful :) )
            if((cmp_nameInput != '' && cmp_idInput != '') && (!nameMatch || !idMatch)){
              $(this).hide();
            }else if((cmp_nameInput != '') && (!nameMatch)){
              $(this).hide();
            }else if((cmp_idInput != '') && (!idMatch)){
              $(this).hide();
            }else{
              $(this).show();
            }

          });
        });
      });
    </script>
`