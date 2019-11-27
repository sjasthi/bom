<?php

            $getAppId = null;
            $findApp = false;
            if (isset($_GET['id'])){
              $getAppId = $_GET['id'];
              $findApp = true;
              $findAppName = false;
            }
            $getAppName = null;
            $getAppVer = null;
            $findAppName = false;
            if (isset($_GET['name']) && isset($_GET['version']) ){
              $getAppName= $_GET['name'];
              $getAppVer = $_GET['version'];
              $findApp = false;
              $findAppName = true;
            }
            
            if ($findApp) {
              $sql_parent = "SELECT DISTINCT app_name as name, 
                              app_version as version, 
                              app_status as status, 
                              '' as cmp_type, 
                              '' as request_step,
                              '' as request_status,
                              '' as notes, 
                              'parent' as class, 
                              concat(app_name, concat('_', app_version)) as application
                              from sbom  
                              where app_id = '".$getAppId."' 
                              order by name;";
            } else if ($findAppName) {
              $sql_parent = "SELECT DISTINCT app_name as name, 
                              app_version as version, 
                              app_status as status, 
                              '' as cmp_type, 
                              '' as request_step,
                              '' as request_status,
                              '' as notes, 
                              'parent' as class, 
                              concat(app_name, concat('_', app_version)) as application
                              from sbom  
                              where app_name = '".$getAppName."' 
                              and app_version = '".$getAppVer."' 
                              order by name;";
            } else {
              $sql_parent = "SELECT DISTINCT app_name as name, 
                              app_version as version, 
                              app_status as status, 
                              '' as cmp_type, 
                              '' as request_step,
                              '' as request_status,
                              '' as notes, 
                              'parent' as class, 
                              'red' as div_class, 
                              concat(app_name, concat('_', app_version)) as application
                              from sbom
                              where 
                              app_name not in (select distinct cmp_name from sbom)
                            union
                            SELECT DISTINCT app_name as name, 
                              app_version as version, 
                              app_status as status, 
                              '' as cmp_type, 
                              '' as request_step,
                              '' as request_status,
                              '' as notes, 
                              'child' as class, 
                              'yellow' as div_class, 
                              concat(cmp_name, concat('_', cmp_version)) as application
                              from sbom
                              where 
                              app_name in (select distinct cmp_name from sbom)
                              order by application, class desc, name;";
             }
            $result_parent = $db->query($sql_parent);
            $p=1;
            $c=1;
            $gc=1;
            if ($result_parent->num_rows > 0) {
              while($row_parent = $result_parent->fetch_assoc()) {
                $app_name = $row_parent["name"];
                $app_version = $row_parent["version"];
                $class = $row_parent["class"];
                $app_status = $row_parent["status"];
                $cmp_type = $row_parent["cmp_type"];
                $request_step = $row_parent["request_step"];
                $request_status = $row_parent["request_status"];
                $notes = $row_parent["notes"];
                $application = $row_parent["application"];
                $div_class = $row_parent["div_class"];
                $p_id = $p;
                echo "<tbody class= '".$div_class."' id = '".$application."'>
                      <tr data-tt-id = '".$p_id."' >
                      <td class='text-capitalize'> <div class = 'btn ".$class."' ><span class = 'app_name' >".$app_name."</span>&nbsp; &nbsp;&nbsp; &nbsp;</div></td>
                      <td >".$app_version."</td>
                      <td class='text-capitalize'>".$app_status."</td>
                      <td class='text-capitalize'>".$cmp_type."</td>
                      <td class='text-capitalize'>".$request_status."</td>
                      <td class='text-capitalize'>".$request_step."</td>
                      <td >".$notes."</td>
                      </tr>";
                $p++;
                      // output data of child
                      $sql_child = "SELECT DISTINCT cmp_name, 
                                                      cmp_type, 
                                                      cmp_version, 
                                                      request_step,
                                                      cmp_status, 
                                                      request_status,
                                                      notes, 
                                                      'grandchild' as class, 
                                                      concat(cmp_name, concat('_', cmp_version)) as cmp
                                        from sbom
                                        where app_name = '".$app_name."'
                                        and app_version = '".$app_version."'
                                        and app_status = '".$app_status."'
                                        and cmp_name not in (select distinct app_name from sbom)
                                    union
                                    SELECT DISTINCT cmp_name, 
                                                    cmp_type, 
                                                    cmp_version, 
                                                    request_step,
                                                    cmp_status, 
                                                    request_status,
                                                    notes, 
                                                    'child' as class, 
                                                    concat(cmp_name, concat('_', cmp_version)) as cmp
                                    from sbom
                                    where app_name = '".$app_name."'
                                        and app_version = '".$app_version."'
                                        and app_status = '".$app_status."'
                                        and cmp_name in (select distinct app_name from sbom)
                                        order by cmp, class, cmp_name;";
                          $result_child = $db->query($sql_child);
                          if ($result_child->num_rows > 0) {
                            // output data of child
                            while($row_child = $result_child->fetch_assoc()) {
                              $cmp_name = $row_child["cmp_name"];
                              $cmp = $row_child["cmp"];
                              $cmp_version = $row_child["cmp_version"];
                              $cmp_status = $row_child["cmp_status"];
                              $request_step = $row_child["request_step"];
                              $request_status = $row_child["request_status"];
                              $cmp_type = $row_child["cmp_type"];
                              $notes = $row_child["notes"];
                              $c_class = $row_child["class"];
                              $c_id=$p_id."-".$c;
                              echo "
                              <tr data-tt-id = '".$c_id."' data-tt-parent-id='".$p_id."' class = 'component' id = ".$cmp."'>
                                <td class='text-capitalize'> <div class = 'btn ".$c_class."'> <span class = 'cmp_name'>".$cmp_name."</span>&nbsp; &nbsp;&nbsp; &nbsp;</div></td>
                                    <td class = 'cmp_version'>".$cmp_version."</td>
                                    <td class='text-capitalize'>".$cmp_status."</td>
                                    <td class='text-capitalize'>".$cmp_type."</td>
                                    <td class='text-capitalize'>".$request_status."</td>
                                    <td class='text-capitalize'>".$request_step."</td>
                                    <td class='text-capitalize'>".$notes."</td>
                                    </tr>";
                              $c++;
                          // output data of grandchild
                          $sql_gchild = "SELECT DISTINCT  cmp_name,
                                          cmp_type, 
                                          cmp_version, 
                                          request_step,
                                          cmp_status, 
                                          request_status,
                                          notes, 
                                          'grandchild' as class, 
                                          concat(cmp_name, concat('_', cmp_version)) as gcmp
                                        from sbom
                                        where app_name = '".$cmp_name."'
                                        and app_version = '".$cmp_version."'
                                        and app_status = '".$cmp_status."'
                                        ;";
                                      $result_gchild = $db->query($sql_gchild);
                                      if ($result_gchild->num_rows > 0 ) {
                                        // output data of grandchild
                                        while($row_gchild = $result_gchild->fetch_assoc()) {
                                          $gcmp_name = $row_gchild["cmp_name"];
                                          $gcmp = $row_gchild["gcmp"];
                                          $gcmp_version = $row_gchild["cmp_version"];
                                          $gcmp_status = $row_gchild["cmp_status"];
                                          $grequest_step = $row_gchild["request_step"];
                                          $grequest_status = $row_gchild["request_status"];
                                          $gcmp_type = $row_gchild["cmp_type"];
                                          $gnotes = $row_gchild["notes"];
                                          $gc_class = $row_gchild["class"];
                                          $gc_id=$c_id."-".$gc;
                                          echo "
                                                <tr data-tt-id = '".$gc_id."' data-tt-parent-id='".$c_id."' id='".$gcmp."' >
                                                <td class='text-capitalize'> <div class = 'btn ".$gc_class."'> <span class = 'cmp_name'>".$gcmp_name."</span>&nbsp; &nbsp;&nbsp; &nbsp;</div></td>
                                                <td class = 'cmp_version'>".$gcmp_version."</td>
                                                <td class='text-capitalize'>".$gcmp_status."</td>
                                                <td class='text-capitalize'>".$gcmp_type."</td>
                                                <td class='text-capitalize'>".$grequest_status."</td>
                                                <td class='text-capitalize'>".$grequest_step."</td>
                                                <td class='text-capitalize'>".$gnotes."</td>
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
          //if there is no parent, then add a row to reflect no results.
          else {
            echo "<tr data-tt-id = 'No Results'> <td>No Results Found</td><td/><td/><td/><td/><td/><td/> </tr>";
          }
          ?>