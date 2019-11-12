-<?php
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
                    <li><a href="#" onclick="$('#bom_treetable').treetable('expandAll'); return false;" id = 'expandAll'><span
                                class="glyphicon glyphicon-chevron-down"></span>Expand All</a></li>
                    <li class="active"><a href="#"
                            onclick="$('#bom_treetable').treetable('collapseAll'); return false;" id = 'collapseAll'><span
                                class="glyphicon glyphicon-chevron-up"></span>Collapse All</a></li>
                                <li><a href="#" id='color_noColor'><span id = 'no_color'>No </span>Color</a></li>
                                <li><a href="?show=red" id ="showRed" >Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'> </span>Red</a></li>
                                <li><a href="?show=redYellow" id = "showRedYellow" > Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'></span>Red and <span class="glyphicon glyphicon-tint" style='color:#ffd966;'></span>Yellow</a></li>
                                <li><div class="input-group">
                                  <input type="text" id="input" class="form-control" placeholder="Where Used" >
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
          $getRedYellow = false;
          $getYellow = false;
            //finds parent data
            if (isset($_GET['show'])){
              if(($_GET['show']) == "redYellow"){
                $getRedYellow = true;
              }
            } else {
              $getRedYellow = false;
            }


            $getAppId = null;
            $findApp = false;
            if (isset($_GET['id'])){
              $getAppId = $_GET['id'];
              $findApp = true;
              echo "<script>$('#expandAll').trigger('click');</script>";
            }

            if($getRedYellow){
            $sql_parent = "SELECT DISTINCT app_name, app_id, app_version, app_status, '' as notes, 'parent' as class, concat(app_name,concat(' ', app_id)) as application from sbom
            union SELECT DISTINCT cmp_name as app_name, cmp_id as app_id, cmp_version as app_version, cmp_status as app_status, notes,   'child' as class, concat(app_name,concat(' ', app_id)) as application
            from sbom order by application, class desc, app_name;";
            } elseif ($findApp) {
              $sql_parent = "SELECT DISTINCT app_name, app_id, app_version, app_status, '' as notes, 'parent' as class from sbom  where app_id = '".$getAppId."' order by app_name;";
            } else{
              $sql_parent = "SELECT DISTINCT app_name, app_id, app_version, app_status, '' as notes, 'parent' as class from sbom  order by app_name;";
            }
            $result_parent = $db->query($sql_parent);
            $p=1;
            $c=1;
            $gc=1;
            if ($result_parent->num_rows > 0) {
              while($row_parent = $result_parent->fetch_assoc()) {
                $app_name = $row_parent["app_name"];
                $app_id = $row_parent["app_id"];
                $app_version = $row_parent["app_version"];
                $class = $row_parent["class"];
                $app_status = $row_parent["app_status"];
                $notes = $row_parent["notes"];
                $p_id = $p;
                echo "<tbody class= 'application' id = '".$app_id."'>
                      <tr data-tt-id = '".$p_id."' >
                      <td class='text-capitalize'> <div id='sashiTestParent' class = 'btn ".$class."' ><span class = 'app_name' >".$app_name."</span>
                      <span class = 'app_id'>ID: ".$app_id."</span> &nbsp; &nbsp;</div></td>
                      <td >".$app_version."</td>
                      <td class='text-capitalize'>".$app_status."</td>
                      <td/>
                      <td >".$notes."</td>
                      </tr>";
                $p++;
                // output data of child
                if($getRedYellow){
                  $sql_child = "SELECT row_id, cmp_name, cmp_id, cmp_type, cmp_version, cmp_status, notes, 'child' as class, concat(cmp_name, concat(' ', cmp_id)) as cmp, 'a' as val from sbom
                  where app_name = '".$app_name."'
                                  and app_id = '".$app_id."'
                                  and app_version = '".$app_version."'
                                  and app_status = '".$app_status."'
                  union
                  SELECT row_id, 'Request ' as cmp_name, request_id as cmp_id, '' as cmp_type, request_step as cmp_version, request_status as cmp_status,
                  concat('Request Date: ', DATE_FORMAT(request_date, \"%m/%d/%y\") ) as notes, 'grandchild' as class, concat(cmp_name, concat(' ', cmp_id)) as cmp, 'z' as val
                  from sbom
                  where cmp_name = '".$app_name."'
                                  and cmp_id = '".$app_id."'
                                  and cmp_version = '".$app_version."'
                                  and cmp_status = '".$app_status."'
                                  order by val, cmp, class, cmp_name;";
              }
                  else{
                  $sql_child = "SELECT row_id, cmp_name, cmp_id, cmp_type, cmp_version, cmp_status, notes, 'child' as class from sbom
                                  where app_name = '".$app_name."'
                                  and app_id = '".$app_id."'
                                  and app_version = '".$app_version."'
                                  and app_status = '".$app_status."' ; ";
                  }
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
                      $row_id = $row_child["row_id"];
                      $c_class = $row_child["class"];
                      $c_id=$p_id."-".$c;
                      echo "
                      <tr data-tt-id = '".$c_id."' data-tt-parent-id='".$p_id."' class = 'component' >
                        <td class='text-capitalize'> <div id='sashiTestChild' class = 'btn ".$c_class."'> <span class = 'cmp_name'>".$cmp_name."</span>
                         <span class = 'cmp_id' >ID: ".$cmp_id."</span>&nbsp; &nbsp; </div></td>
                            <td class = 'cmp_version'>".$cmp_version."</td>
                            <td class='text-capitalize'>".$cmp_status."</td>
                            <td class='text-capitalize'>".$cmp_type."</td>
                            <td class='text-capitalize'>".$notes."</td>
                            </tr>";
                      $c++;
                      // output data of child
                        $sql_gchild = "SELECT request_id, request_step, request_status, DATE_FORMAT(request_date, \"%m/%d/%y\") as request_date from sbom
                                        where app_name = '".$app_name."'
                                        and app_id = '".$app_id."'
                                        and row_id = '".$row_id."'
                                        and app_version = '".$app_version."'
                                        and app_status = '".$app_status."'
                                        and cmp_name = '".$cmp_name."'
                                        and cmp_id = '".$cmp_id."'
                                        and cmp_version = '".$cmp_version."'
                                        and cmp_status = '".$cmp_status."'
                                        ;";
                        $result_gchild = $db->query($sql_gchild);
                        if ($result_gchild->num_rows > 0 ) {
                          // output data of grandchild
                          while($row_gchild = $result_gchild->fetch_assoc()) {

                            $request_id= $row_gchild["request_id"];
                            $request_date= $row_gchild["request_date"];
                            $request_step= $row_gchild["request_step"];
                            $request_status= $row_gchild["request_status"];
                            $gc_id=$c_id."-".$gc;
                            echo "
                                  <tr data-tt-id = '".$gc_id."' data-tt-parent-id='".$c_id."' >
                                  <td > <div id='sashiTestGrandChild' class = 'btn  grandchild'>Request ID: <span class = 'request_id'>".$request_id."</span>&nbsp;&nbsp;</div></td>
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
            echo "<tr data-tt-id = 'No Results'> <td>No Results Found ";
            if ($findApp) {
              echo "for App ID ".$getAppId;
            }
            echo "</td><td></td><td></td><td></td><td></td> </tr>";
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
        clickableNodeNames: true,
        indent: 50
      };

      $("#bom_treetable").treetable(sbom_params).DataTable(
        {
          searching: false,
          ordering:  false,
          info: false,
          scrollY:        '50vh',
          scrollCollapse: true,
          paging:         false
        });


      //Function for Color/No Color Button
      $(document).ready(function(){
        $("#color_noColor").click(function(){
          $("#no_color").toggle();
          $("div .parent").toggleClass("bw_parent");
          $("div .child").toggleClass("bw_child");
          $("div .grandchild").toggleClass("bw_grandchild");
        });
      });


      $(document).ready(function() {
        //input search for where used
        $('#input').on('keyup', function() {
          let input = $(this).val().toLowerCase();
          let cmp_nameInput = '', cmp_idInput = '';

          //Checks to see if the search terms are delineated, if yes, split input into cmp_nameInput and cmp_idInput
          //Feel free to add more delimiters to this array exxcept backslash ( \ ). I'm nearly 100% sure it'll break something, somewhere.
          let delimiterArray = [';', ':', ',', '|', '/'];

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

            let sucessfulMatch = false;
            //Check if any component name in the current application matches cmp_nameInput
            $(this).find(".component").each(function(){
              let nameMatch = false, idMatch = false;

              if($(this).find(".cmp_name").text().toLowerCase().includes(cmp_nameInput)){
                nameMatch = true;
              }

              if($(this).find(".cmp_id").text().toLowerCase().includes(cmp_idInput)){
                idMatch = true;
              }

              //Outer: if there was a sucessful match, don't bother searching more
              // 1: if (both search terms are used) and (both search terms aren't found)
              // 2: if (cmp_name is used) and (cmp_name isn't found)
              // 3: if (cmp_id is used) and (cmp_id isn't found)
              // 4: else, search successful, mark flag so we don't overwrite the show()
              if(!sucessfulMatch){
                if((cmp_nameInput != '' && cmp_idInput != '') && (!nameMatch || !idMatch)){
                  $(this).parent().hide();
                }else if((cmp_nameInput != '') && (!nameMatch)){
                  $(this).parent().hide();
                }else if((cmp_idInput != '') && (!idMatch)){
                  $(this).parent().hide();
                }else{
                  $(this).parent().show();
                  sucessfulMatch = true;
                }
              }
            });
          });
        });
      });
    </script>
