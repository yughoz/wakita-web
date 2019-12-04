<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">
    
                    <div class="box-header">
                        <h3 class="box-title">List Chat</h3>
                    </div>
        
        <div class="box-body">
        
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="30px">No</th>
                    <th>Hotline</th>
                    <th>Phone Number</th>
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="#" method="post" id="form-create_main" class="form-horizontal">
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">INPUT DATA  MILIS</h4>
            </div>

            <div class="modal-body"> 
                

                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Name</label>  
                    <div class="col-md-8">
                    <input name="name" id="name" placeholder="Name"  type="text" class="form-control input-md">
                    <span class="text-danger" id="error_name"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Device ID</label>  
                    <div class="col-md-8">
                    <input name="device_id" id="device_id" placeholder="device_id"  type="text" class="form-control classUpdate input-md">
                    <span class="text-danger" id="error_update_device_id"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Device Name</label>  
                    <div class="col-md-8">
                    <input name="device_name" id="device_name" placeholder="device_name"  type="text" class="form-control classUpdate input-md">
                    <span class="text-danger" id="error_update_device_name"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Domain API</label>  
                    <div class="col-md-8">
                    <input name="domain_api" id="domain_api" placeholder="domain_api"  type="text" class="form-control classUpdate input-md">
                    <span class="text-danger" id="error_update_domain_api"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Token</label>  
                    <div class="col-md-8">
                    <input name="token" id="token" placeholder="token"  type="text" class="form-control classUpdate input-md">
                    <span class="text-danger" id="error_update_token"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Package Name</label>  
                    <div class="col-md-8">
                    <?php echo cmb_dinamis('package_id', 'tbl_package', 'package_name', 'package_id') ?>
                    <span class="text-danger" id="error_update_token"></span>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Phone Number</label>  
                    <div class="col-md-8">
                    <input name="phone_number" id="phone_number" placeholder="phone_number"  type="text" class="form-control classUpdate input-md">
                    <span class="text-danger" id="error_update_phone_number"></span>
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
            </div>

            </form>
        </div>
    </div>
</div><!-- Modal -->


<div id="modalEdit" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">UPDATE DATA MILIS </h4>
      </div>
      <div class="modal-body"> 
            <form action="#" method="post" id="form-update_main" class="form-horizontal">
            
    
        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Name</label>  
          <div class="col-md-8">
          <input name="name" id="name" placeholder="Name"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_name"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Device ID</label>  
          <div class="col-md-8">
          <input name="device_id" id="device_id" placeholder="device_id"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_device_id"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Device Name</label>  
          <div class="col-md-8">
          <input name="device_name" id="device_name" placeholder="device_name"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_device_name"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Domain API</label>  
          <div class="col-md-8">
          <input name="domain_api" id="domain_api" placeholder="domain_api"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_domain_api"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Token</label>  
          <div class="col-md-8">
          <input name="token" id="token" placeholder="token"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_token"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Phone Number</label>  
          <div class="col-md-8">
          <input name="phone_number" id="phone_number" placeholder="phone_number"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_phone_number"></span>
          </div>
        </div>
       
        <div class="form-group">
              <label class="col-md-4 control-label" for="textinput">Created</label>  
              <div class="col-md-8">
              <input name="created" id="created" placeholder="Created" readonly='readonly'  type="text" class="form-control classUpdate input-md">
              </div>
            </div>   
           
        <div class="form-group">
              <label class="col-md-4 control-label" for="textinput">Createdby</label>  
              <div class="col-md-8">
              <input name="createdby" id="createdby" placeholder="Createdby" readonly='readonly'  type="text" class="form-control classUpdate input-md">
              </div>
            </div>   
           
        <div class="form-group">
              <label class="col-md-4 control-label" for="textinput">Updated</label>  
              <div class="col-md-8">
              <input name="updated" id="updated" placeholder="Updated" readonly='readonly'  type="text" class="form-control classUpdate input-md">
              </div>
            </div>   
           
        <div class="form-group">
              <label class="col-md-4 control-label" for="textinput">Updatedby</label>  
              <div class="col-md-8">
              <input name="updatedby" id="updatedby" placeholder="Updatedby" readonly='readonly'  type="text" class="form-control classUpdate input-md">
              </div>
            </div>   
           <tr><td></td><td><input type="hidden" class="form-control classUpdate input-md" id="id" name="id" value="<?php echo $id; ?>" />      </div>
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
                        
                    },
                    oLanguage: {
                        sProcessing: "loading..."
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {"url": '<?php echo base_url("ManageChat/list_hotline_json") ?>', "type": "GET"},
                    columns: [
                        {
                            "data": "id",
                            "orderable": false
                        },
                        {"data": "name"},
                        {"data": "phone_number"},
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