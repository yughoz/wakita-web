<?php 
$string = "<div class=\"content-wrapper\">
    <section class=\"content\">
        <div class=\"row\">
            <div class=\"col-xs-12\">
                <div class=\"box box-warning box-solid\">
    
                    <div class=\"box-header\">
                        <h3 class=\"box-title\">KELOLA DATA ".  strtoupper($table_name)."</h3>
                    </div>
        
        <div class=\"box-body\">
        <div style=\"padding-bottom: 10px;\">
        
        <button type=\"button\" class=\"btn btn-danger btn-sm\" data-toggle=\"modal\" data-target=\"#myModal\">Tambah Data</button>
        <button type=\"button\" class=\"btn btn-danger btn-sm\" id=\"refersh\">Refersh</button>
        ";

if ($export_excel == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/excel'), '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Export Ms Excel', 'class=\"btn btn-success btn-sm\"'); ?>";
}
if ($export_word == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/word'), '<i class=\"fa fa-file-word-o\" aria-hidden=\"true\"></i> Export Ms Word', 'class=\"btn btn-primary btn-sm\"'); ?>";
}
if ($export_pdf == '1') {
    $string .= "\n\t\t<?php echo anchor(site_url('".$c_url."/pdf'), 'PDF', 'class=\"btn btn-primary\"'); ?>";
}
$string.="</div>
        <div class=\"box-body table-responsive no-padding\">
        <table class=\"table table-bordered table-striped\" id=\"mytable\">
            <thead>
                <tr>
                    <th width=\"30px\">No</th>";
foreach ($non_pk as $row) {
  if (!in_array($row['column_name'], $hiddenfield)) {
    echo $row['column_name'];
    $string .= "\n\t\t    <th>" . label($row['column_name']) . "</th>";
  }
}
$string .= "\n\t\t    <th width=\"200px\">Action</th>
                </tr>
            </thead>";

$column_non_pk = array();
foreach ($non_pk as $row) {
  if (!in_array($row['column_name'], $hiddenfield)) {
    $column_non_pk[] .= "{\"data\": \"".$row['column_name']."\"}";
  }
}
$col_non_pk = implode(',', $column_non_pk);

$string .= "\n\t    
        </table>
        </div>
        </div>
                    </div>
            </div>
            </div>
    </section>
</div>";
$string .="<!-- Modal -->
<div id=\"myModal\" class=\"modal fade\" role=\"dialog\">
  <div class=\"modal-dialog\">
    <!-- Modal content-->
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
        <h4 class=\"modal-title\">INPUT DATA  ".  strtoupper($table_name)."</h4>
      </div>
      <div class=\"modal-body\"> ";
        
$string .= "
            <form action=\"#\" method=\"post\" id=\"form-create_main\" class=\"form-horizontal\">
            
";
foreach ($non_pk as $row) {

  if (!in_array($row['column_name'], $hiddenfield)) {
      if ($row["data_type"] == 'text')
      {
      $string .= " 

          <tr><td width='200'>".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></td><td> <textarea class=\"form-control\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"><?php echo $".$row["column_name"]."; ?></textarea></td></tr>";
      }elseif($row["data_type"]=='email'){
          $string .= "    <tr><td width='200'>".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></td><td><input type=\"email\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" /></td></tr>";    
      }
      elseif($row["data_type"]=='date'){
          $string .= "    <tr><td width='200'>".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></td><td><input type=\"date\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" /></td></tr>";    
          } 
      else
      {
      $string .= "    
      <div class=\"form-group\">
            <label class=\"col-md-4 control-label\" for=\"textinput\">".label($row["column_name"])."</label>  
            <div class=\"col-md-8\">
            <input name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"  type=\"text\" class=\"form-control input-md\">
            <span class=\"text-danger\" id=\"error_".$row["column_name"]."\"></span>
            </div>
          </div>   
     ";
      }

  }
}
$string .= "    <tr><td></td><td><input type=\"hidden\" name=\"".$pk."\" value=\"<?php echo $".$pk."; ?>\" /> ";

$string .= " ";   


$string  .="    </div>
      <div class=\"modal-footer\">
          <div class=\"form-group\">
              <label class=\"col-md-4 control-label\" for=\"button1id\"></label>
              <div class=\"col-md-8\">
                <button id=\"button1id\" name=\"button1id\" class=\"btn btn-info\">Save</button>
                <button id=\"close\" name=\"close\" class=\"btn btn-danger\" data-dismiss=\"modal\">cancel</button>
              </div>
            </div>
            </form>       
      </div>
    </div>

  </div>
</div>";


$string .="<!-- Modal -->
<div id=\"modalEdit\" class=\"modal fade\" role=\"dialog\">
  <div class=\"modal-dialog\">
    <!-- Modal content-->
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
        <h4 class=\"modal-title\">UPDATE DATA ".  strtoupper($table_name)." </h4>
      </div>
      <div class=\"modal-body\"> ";
        
$string .= "
            <form action=\"#\" method=\"post\" id=\"form-update_main\" class=\"form-horizontal\">
            
";
foreach ($non_pk as $row) {
    if (in_array($row['column_name'], $hiddenfield)) {
      $string .= "    
        <div class=\"form-group\">
              <label class=\"col-md-4 control-label\" for=\"textinput\">".label($row["column_name"])."</label>  
              <div class=\"col-md-8\">
              <input name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" readonly='readonly'  type=\"text\" class=\"form-control classUpdate input-md\">
              </div>
            </div>   
       ";
    }
    elseif ($row["data_type"] == 'text')
    {
    $string .= " 

        <tr><td width='200'>".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></td><td> <textarea class=\"form-control\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"><?php echo $".$row["column_name"]."; ?></textarea></td></tr>";
    }elseif($row["data_type"]=='email'){
        $string .= "    <tr><td width='200'>".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></td><td><input type=\"email\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" /></td></tr>";    
    }
    elseif($row["data_type"]=='date'){
        $string .= "    <tr><td width='200'>".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></td><td><input type=\"date\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" /></td></tr>";    
        } 
    else
    {
    $string .= "    
    <div class=\"form-group\">
          <label class=\"col-md-4 control-label\" for=\"textinput\">".label($row["column_name"])."</label>  
          <div class=\"col-md-8\">
          <input name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"  type=\"text\" class=\"form-control classUpdate input-md\">
          <span class=\"text-danger\" id=\"error_update_".$row["column_name"]."\"></span>
          </div>
        </div>   
   ";
    }
}
$string .= "    <tr><td></td><td><input type=\"hidden\" class=\"form-control classUpdate input-md\" id=\"".$pk."\" name=\"".$pk."\" value=\"<?php echo $".$pk."; ?>\" /> ";

$string .= " ";   


$string  .="    </div>
      <div class=\"modal-footer\">
          <div class=\"form-group\">
              <label class=\"col-md-4 control-label\" for=\"button1id\"></label>
              <div class=\"col-md-8\">
                <button id=\"button1id\" name=\"button1id\" class=\"btn btn-info\">Save</button>
                <button id=\"close\" name=\"close\" class=\"btn btn-danger\" data-dismiss=\"modal\">cancel</button>
              </div>
            </div>
            </form>       
      </div>
    </div>

  </div>
</div>";


$string  .="
        <script src=\"<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>\"></script>
        <script src=\"<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>\"></script>
        <script type=\"text/javascript\">
            $(document).ready(function() {
                $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
                {
                    return {
                        \"iStart\": oSettings._iDisplayStart,
                        \"iEnd\": oSettings.fnDisplayEnd(),
                        \"iLength\": oSettings._iDisplayLength,
                        \"iTotal\": oSettings.fnRecordsTotal(),
                        \"iFilteredTotal\": oSettings.fnRecordsDisplay(),
                        \"iPage\": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        \"iTotalPages\": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var t = $(\"#mytable\").dataTable({
                    initComplete: function() {
                        var api = this.api();
                        $('#mytable_filter input')
                                .off('.DT')
                                .on('keyup.DT', function(e) {
                                    if (e.keyCode == 13) {
                                        api.search(this.value).draw();
                            }
                        });
                        $(\"#refersh\").click(function() {
                            api.search(this.value).draw();
                        });
                        $(\"#form-create_main\").on('submit',(function(e) {
                            e.preventDefault();
                            var apis = api
                            $( \"span.text-danger\" ).each(function() {
                              $( this ).html( \"\" );
                            });
                            $.ajax({
                                url: \"<?php echo base_url('".$controller."/create_action') ?>\",
                                type: \"POST\",
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                dataType: \"json\",
                                processData:false,
                                success: function(resp)
                                {
                                    try {
                                        parseData = resp;
                                        console.log(parseData);
                                        if(parseData['code'] == \"success\"){
                                            document.getElementById(\"form-create_main\").reset();
                                            $( \"#refersh\" ).click()
                                            $('#myModal').modal('hide');
                                        } else{
                                            $.each(parseData.form_error, function( index, value ) {
                                                $(\"#error_\"+index).html(value);
                                            });
                                        }
                                    } catch(e) {
                                        console.log(e);
                                    }
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }));

                        $(\"#form-update_main\").on('submit',(function(e) {
                            e.preventDefault();
                            var apis = api
                            $( \"span.text-danger\" ).each(function() {
                              $( this ).html( \"\" );
                            });
                            $.ajax({
                                url: \"<?php echo base_url('".$controller."/update_action') ?>\",
                                type: \"POST\",
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                dataType: \"json\",
                                processData:false,
                                success: function(resp)
                                {
                                    try {
                                        parseData = resp;
                                        console.log(parseData);
                                        if(parseData['code'] == \"success\"){
                                            document.getElementById(\"form-update_main\").reset();
                                            $( \"#refersh\" ).click()
                                            $('#modalEdit').modal('hide');
                                        } else{
                                            $.each(parseData.form_error, function( index, value ) {
                                                $(\"#error_update_\"+index).html(value);
                                            });
                                        }
                                    } catch(e) {
                                        console.log(e);
                                    }
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }));
                    },
                    oLanguage: {
                        sProcessing: \"loading...\"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {\"url\": \"".$c_url."/json\", \"type\": \"POST\"},
                    columns: [
                        {
                            \"data\": \"$pk\",
                            \"orderable\": false
                        },".$col_non_pk.",
                        {
                            \"data\" : \"action\",
                            \"orderable\": false,
                            \"className\" : \"text-center\"
                        }
                    ],
                    order: [[0, 'desc']],
                    rowCallback: function(row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });

        function editModal(id){
          $.get(\"<?php echo base_url('".$controller."/read') ?>/\"+id, function( data ) {
            console.log(data.data)
            $( \".classUpdate\" ).each(function() {
              var idT = $(this).attr('id')
              $( this ).val(data.data[idT]);
              console.log($(this).attr('id'))
            });
            $('#modalEdit').modal('show');
          }, \"json\" );

        }
        function delete_conf(id){
          swal({
              title: \"Are you sure?\",
              // text: \"\",
              icon: \"warning\",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                
                $.ajax({
                      url: \"<?php echo base_url('".$controller."/delete_action') ?>/\"+id,
                      type: \"POST\",
                      data: [],
                      contentType: false,
                      cache: false,
                      dataType: \"json\",
                      processData:false,
                      success: function(resp)
                      {
                          // console.log(resp.message)
                          swal(resp.message, {
                            icon: \"success\",
                          });
                          $( \"#refersh\" ).click()
                          
                      },
                      error: function (data) {
                          console.log('Error:', data);
                      }
                  });
              }
            });
        }
        </script>";


$hasil_view_list = createFile($string, $target."views/" . $c_url . "/" . $v_list_file);

?>