<?php
  $nav_selected = "SCANNER";
  $left_buttons = "YES";
  $left_selected = "SBOMTREE";

  include("./nav.php");
 ?>

<link href="jQueryTreeTable/css/jquery.treetable.css" rel="stylesheet" type="text/css" />
<link href="jQueryTreeTable/css/jquery.treetable.theme.default.css" rel="stylesheet" type="text/css" />
<script src="jQueryTreeTable/jquery.treetable.js"></script>

 <div class="right-content">
    <div class="container" id="container">
      <h3 style = "color: #01B0F1;">Scanner --> BOM Tree</h3>

      <script type="text/javascript">
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

        let container = document.getElementById('container');

        let root = document.createElement('table');
        let tbody = document.createElement('tbody');

        root.appendChild(tbody);

        let idCount = 1;
        let app_nameParentId = -1;
        let app_idParentId = -1;

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

        container.appendChild(root);
        root.setAttribute('id', 'maintreetable');

        let params = {
          expandable: true,
          clickableNodeNames: true
        };

        $("#maintreetable").treetable(params);
      </script>
    </div>
</div>

<?php include("./footer.php"); ?>
