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
                                <li><a href="?show=red" id ="showRed" >Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'> </span>Red</a></li>
                                <li><a href="?show=yellow" id = "showRedYellow" > Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'></span>Red and <span class="glyphicon glyphicon-tint" style='color:#ffd966;'></span>Yellow</a></li>
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
            $getYellow = false;
            //finds parent data
            if (isset($_GET['show'])){
              if(($_GET['show']) == "yellow"){
                $getYellow = true;
              }
            } else {
              $getYellow = false;
            }
            if($getYellow){
            $sql = "SELECT DISTINCT app_name, app_id, app_version, app_status, '' as notes, 'parent' as class, concat(app_name,concat(' ', app_id)) as application from sbom
            union SELECT DISTINCT cmp_name as app_name, cmp_id as app_id, cmp_version as app_version, cmp_status as app_status, notes,   'child' as class, concat(app_name,concat(' ', app_id)) as application
            from sbom order by application, class desc, app_name;";
            } else{
              $sql = "SELECT DISTINCT 
              app_name as name, 
              app_id as id, 
              app_version as version, 
              app_status as status, 
              '' as type, 
              '' as notes,  
              'parent' as class, 
              'application' as row_class, 
              'app_name' as name_type, 
              'app_id' as id_type,
              concat(app_id,concat('.',app_name)) as row_id,
              null as parent_id,
              concat(app_id,concat('.',Concat(app_name,'_a'))) as row_order
              from sbom
              union
              SELECT DISTINCT 
              cmp_name as name, 
              cmp_id as id, 
              cmp_version as version, 
              cmp_status as status, 
              cmp_type as type, 
              notes as notes, 
              'child' as class,  
              'component' as row_class,  
              'cmp_name' as name_type, 
              'cmp_id' as id_type,
              concat(app_id,concat('.',concat(app_name,concat('.',concat(cmp_id,concat('.',cmp_name)))))) as row_id,
              concat(app_id,concat('.',app_name)) as parent_id,
              concat(app_id,concat('.',Concat(app_name,concat('_b_',concat(cmp_id,concat('.',concat(cmp_name,'_a'))))))) as row_order
              from sbom
              union
              SELECT DISTINCT 
              'Request ' as name, 
              request_id as id, 
              request_step as version, 
              request_status as status, 
              '' as type, 
              concat('Request Date: ', DATE_FORMAT(request_date, \"%m/%d/%y\")) as notes, 
              'grandchild' as class,  
              'request' as row_class,  
              'request' as name_type, 
              'request_id' as id_type,
              request_id as row_id,
              concat(app_id,concat('.',concat(app_name,concat('.',concat(cmp_id,concat('.',cmp_name)))))) as parent_id,
              concat(app_id,concat('.',Concat(app_name,concat('_b_',concat(cmp_id,concat('.',concat(cmp_name,concat('_b_',request_id)))))))) as row_order
              from sbom
              order by row_order
              ";
      $result = $db->query($sql);
    }
            $result = $db->query($sql);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $name = $row["name"];
                $id = $row["id"];
                $name_type = $row["name_type"];
                $id_type = $row["id_type"];
                $version = $row["version"];
                $status = $row["status"];
                $type = $row["type"];
                $notes = $row["notes"];
                $row_id = $row["row_id"];
                $parent_id = $row["parent_id"];
                $class = $row["class"];
                $row_class = $row["row_class"];

              echo "<tr data-tt-id = '".urlencode($row_id)."' ";
              if($parent_id != null) { echo "data-tt-parent-id= '".urlencode($parent_id)."'"; } 
              echo " class= '".$row_class."'>
                        <td ><div class = 'btn ".$class."'><span class = '".$name_type."' >".$name."</span>
                        <span class = '".$id_type."''>ID: ".$id."</span> &nbsp; &nbsp;</div></td>
                        <td >".$version."</td>
                        <td class='text-capitalize'>".$status."</td>
                        <td class='text-capitalize'>".$type."</td>
                        <td class='text-capitalize'>".$notes."</td>
                        </tr>";
          }
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
        clickableNodeNames: true,
        indent: 50
      };

      $("#bom_treetable").treetable(sbom_params).DataTable(
        {
          searching: false,
          ordering:  false,
          info: false,
          paging: false
        });


      //Function for Color/No Color Button
      $(document).ready(function(){
        $("#color_noColor").click(function(){
          $("#no_color").toggle();
          $("div").toggleClass("bw_parent");          
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
            $(this).find(".component .cmp_id").each(function(){
              if($(this).text().toLowerCase().includes(cmp_idInput)){
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
