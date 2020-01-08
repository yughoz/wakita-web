<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">
    
                    <div class="box-header">
                        <h3 class="box-title">Manage User</h3>
                    </div>
        
        <div class="box-body">
        <div style="padding-bottom: 10px;">
        <?php echo anchor(site_url('ManageUser/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Add Data', 'class="btn btn-danger btn-sm"'); ?>
        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#trialModal">Create User Trial</button>
            
        </div>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="30px">No</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Nama Level</th>
                    <th width="200px">Action</th>
                </tr>
            </thead>
	    
        </table>
        </div>
                    </div>
            </div>
            </div>
    </section>
</div>

<div id="trialModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Trial User</h4>
      </div>
      <div class="modal-body"> 
            <form action="#" method="post" id="form_create_trial" class="form-horizontal">
            
    
      <div class="form-group">
        <label class="col-md-4 control-label" for="textinput">Counter</label>  
        <div class="col-md-8">
        <input name="counter" id="counter" placeholder="Counter"  type="number" class="form-control input-md" value="1"  min="1" max="10">
        <span class="text-danger" id="error_name"></span>
        </div>
      </div>   
      <div class="form-group">
        <label class="col-md-4 control-label" for="textinput">Prefix Email</label>  
        <div class="col-md-8">
        <input name="prefix" id="prefix"   type="text" class="form-control input-md" value="wakita">
        <span class="text-danger" id="error_name"></span>
        </div>
      </div>   
      <div class="form-group">
        <label class="col-md-4 control-label" for="textinput">User level</label>  
        <div class="col-md-8">
         <?php echo cmb_dinamis('id_user_level', 'tbl_user_level', 'nama_level', 'id_user_level', '' ,'DESC') ?>
        <span class="text-danger" id="error_name"></span>
        </div>
      </div>   
      <div class="form-group">
        <label class="col-md-4 control-label" for="textinput">Hotline </label>  
        <div class="col-md-8">
         <select name="group_number" class="form-control">
            <?php foreach ($hotline as $key => $value): ?>
                <option value="<?php echo $value->phone_number ?>"><?php echo $value->device_name ?> (<?php echo $value->phone_number ?>)</option>
            <?php endforeach ?>
        </select>        
        <span class="text-danger" id="error_name"></span>
        </div>        
      </div>   
        <div class="row ">
            <div class="col-md-10" id="rowData">
                

                <!-- <div class="form-group col-md-10">
                  <label>Text Disabled</label><br>
                    <Text class="" >12admin@admin.com</Text>   
                </div>

                <div class="form-group col-md-10">
                  <label>Text Disabled</label><br>
                    <Text class="" >12admin@admin.com</Text>  
                </div> -->
                
            </div>
        </div>
       
        </div>
      <div class="modal-footer">
          <div class="form-group">
              <label class="col-md-4 control-label" for="button1id"></label>
              <div class="col-md-8">
                <button id="button1id" name="button1id" class="btn btn-info">Save</button>
                <button id="close" name="close" class="btn btn-danger" data-dismiss="modal">cancel</button>
              </div>
            </div>
            </form>       
      </div>
    </div>

  </div>
</div>
<!-- Modal -->

        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var t = $("#mytable").dataTable({
                    initComplete: function() {
                        var api = this.api();
                        $('#mytable_filter input')
                                .off('.DT')
                                .on('keyup.DT', function(e) {
                                    if (e.keyCode == 13) {
                                        api.search(this.value).draw();
                            }
                        });


                        $("#form_create_trial").on('submit',(function(e) {
                            e.preventDefault();
                            var apis = api
                            $( "span.text-danger" ).each(function() {
                              $( this ).html( "" );
                            });
                            $.ajax({
                                url: "<?php echo base_url('ManageUser/create_trial_action') ?>",
                                type: "POST",
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                dataType: "json",
                                processData:false,
                                success: function(resp)
                                {
                                    try {
                                        parseData = resp;
                                        html = "";
                                        $.each(parseData.data, function( index, value ) {
                                            html += '<div class="form-group col-md-10">';
                                            html += '   <label>'+value.email+'</label><br>';
                                            html += '   <Text class="" >'+value.password+'</Text>';   
                                            html += '</div>';
                                          // alert( index + ": " + value );
                                        });

                                        $("#rowData").html(html);
                                        console.log(parseData);
                                        
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
                        sProcessing: "loading..."
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {"url": "ManageUser/json", "type": "POST"},
                    columns: [
                        {
                            "data": "id_users",
                            "orderable": false
                        },{"data": "full_name"},{"data": "email"},{"data": "phone"},{"data": "nama_level"},
                        {
                            "data" : "action",
                            "orderable": false,
                            "className" : "text-center"
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
        </script>