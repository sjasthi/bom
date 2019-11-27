-<?php
  
  $nav_selected = "SCANNER";
  $left_buttons = "YES";
  $left_selected = "SBOMTREE";
  include("./nav.php");
 ?>

<div class="right-content">
    <div class="container" id="container">
        <h3 style="color: #01B0F1;">Scanner --> BOM Tree</h3>
        <nav class="navbar">
            <div class="container-fluid">
                <ul class="nav navbar-nav" style='font-size: 18px;'>
                    <li><a href="#" onclick="expandAll();" id = 'expandAll'><span
                                class="glyphicon glyphicon-chevron-down"></span>Expand All</a></li>
                    <li class="active"><a href="#"
                            onclick="collapseAll();"><span
                                class="glyphicon glyphicon-chevron-up"></span>Collapse All</a></li>
                                <li><a href="#" id='color_noColor'><span id = 'no_color'>No </span>Color</a></li>
                                <li><a href="#" id ="showYellow" >Show <span class="glyphicon glyphicon-tint" style='color:#ffd966;'> </span>Yellow</a></li>
                                <li><a href="#" id ="showRed" >Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'> </span>Red</a></li>
                                <li><a href="#" id = "showRedYellow" > Show <span class="glyphicon glyphicon-tint" style='color:#ff6666;'></span>Red and <span class="glyphicon glyphicon-tint" style='color:#ffd966;'></span>Yellow</a></li>
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
                        <div >
                          <h4 id="loading-text">Loading...</h4>
                          <div class="h4" id="responsive-wrapper" style="opacity: 0.0;">
                            <table id = "bom_treetable" class = "table">
                              <thead class = 'h4'>
                                <th >Name</th>
                                <th>Version</th>
                                <th>Status</th>
                                <th>CMP Type</th>
                                <th>Request Status</th>
                                <th>Request Step</th>
                                <th>Notes</th>
                              </thead>
                              <?php include("./tree.php"); ?>
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
        indent: 25
      };
      $("#bom_treetable").treetable(sbom_params).DataTable(
        {
          searching: false,
          columnDefs: [
            { width: "35%", targets: 0 }
            ],
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
        $(document).ready(function(){
        //click getRed to hide the yellows and show the reds
        $("#showRed").click(function(){
          $("div .yellow").hide();
          $("div .red").show();
        });
      });
        $(document).ready(function(){
        //click getYellow to hide the reds and show the yellows
        $("#showYellow").click(function(){
          $("div .yellow").show();
          $("div .red").hide();
        });
      });
        $(document).ready(function(){
        //click getRedYellow to show everything
        $("#showRedYellow").click(function(){
          $("div .yellow").show();
          $("div .red").show();
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
              if($(this).find(".cmp_version").text().toLowerCase().includes(cmp_idInput)){
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
      document.onreadystatechange = function () {
        if (document.readyState === 'complete') {
          $('#loading-text').hide();
          $("#responsive-wrapper").css('opacity', '100.0');
        }
      }
      let expandAll = function(){
        $("#bom_treetable tbody tr.leaf").each((index, item) => {
          setTimeout(() => {
            $("#bom_treetable").treetable("reveal", $(item).attr("data-tt-id"))
          }, 0);
        });
      }
      let collapseAll = function(){
        let highestTimeoutId = setTimeout(";");
        for (let i = 0 ; i < highestTimeoutId ; i++) {
            clearTimeout(i); 
        }
        $('#bom_treetable').treetable('collapseAll');
      }
      <?php 
      if ($findApp || $findAppName) {
        echo "$( \"#expandAll\" ).trigger( \"click\" );";
      }
      
      ?>
      </script>