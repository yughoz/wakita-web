<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">
    
                    <div class="box-header">
                        <h3 class="box-title">KELOLA DATA HOTLINE</h3>
                    </div>
        
        <div class="box-body">
        <div style="padding-bottom: 10px;">
        
        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">Tambah Data</button>
        <button type="button" class="btn btn-danger btn-sm" id="refersh">Refersh</button>
        </div>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="30px">No</th>
		    <th>Customer Phone</th>
		    <th>Message</th>
		    <th>Flag Status</th>
		    <th width="200px">Action</th>
                </tr>
            </thead>
	    
        </table>
        </div>
                    </div>
            </div>
            </div>
    </section>
</div><!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">INPUT DATA  HOTLINE</h4>
      </div>
      <div class="modal-body"> 
            <form action="#" method="post" id="form-create_main" class="form-horizontal">
            
    
      <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">Customer Phone</label>  
            <div class="col-md-8">
            <input name="customer_phone" id="customer_phone" placeholder="Customer Phone"  type="text" class="form-control input-md">
            <span class="text-danger" id="error_customer_phone"></span>
            </div>
          </div>   
         
      <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">Message</label>  
            <div class="col-md-8">
            <input name="message" id="message" placeholder="Message"  type="text" class="form-control input-md">
            <span class="text-danger" id="error_message"></span>
            </div>
          </div>   
         
      <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">Flag Status</label>  
            <div class="col-md-8">
            <input name="flag_status" id="flag_status" placeholder="Flag Status"  type="text" class="form-control input-md">
            <span class="text-danger" id="error_flag_status"></span>
            </div>
          </div>   
         <tr><td></td><td><input type="hidden" name="id" value="<?php echo $id; ?>" />      </div>
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
</div><!-- Modal -->
<div id="modalEdit" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">UPDATE DATA HOTLINE </h4>
      </div>
      <div class="modal-body"> 
            <form action="#" method="post" id="form-update_main" class="form-horizontal">
            
    
    <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Customer Phone</label>  
          <div class="col-md-8">
          <input name="customer_phone" id="customer_phone" placeholder="Customer Phone"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_customer_phone"></span>
          </div>
        </div>   
       
    <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Message</label>  
          <div class="col-md-8">
          <input name="message" id="message" placeholder="Message"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_message"></span>
          </div>
        </div>   
       
    <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Flag Status</label>  
          <div class="col-md-8">
          <input name="flag_status" id="flag_status" placeholder="Flag Status"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_update_flag_status"></span>
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
                        $("#refersh").click(function() {
                            api.search(this.value).draw();
                        });
                        $("#form-create_main").on('submit',(function(e) {
                            e.preventDefault();
                            var apis = api
                            $( "span.text-danger" ).each(function() {
                              $( this ).html( "" );
                            });
                            $.ajax({
                                url: "<?php echo base_url('Hotline/create_action') ?>",
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
                                        console.log(parseData);
                                        if(parseData['code'] == "success"){
                                            document.getElementById("form-create_main").reset();
                                            $( "#refersh" ).click()
                                            $('#myModal').modal('hide');
                                        } else{
                                            $.each(parseData.form_error, function( index, value ) {
                                                $("#error_"+index).html(value);
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

                        $("#form-update_main").on('submit',(function(e) {
                            e.preventDefault();
                            var apis = api
                            $( "span.text-danger" ).each(function() {
                              $( this ).html( "" );
                            });
                            $.ajax({
                                url: "<?php echo base_url('Hotline/update_action') ?>",
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
                                        console.log(parseData);
                                        if(parseData['code'] == "success"){
                                            document.getElementById("form-update_main").reset();
                                            $( "#refersh" ).click()
                                            $('#modalEdit').modal('hide');
                                        } else{
                                            $.each(parseData.form_error, function( index, value ) {
                                                $("#error_update_"+index).html(value);
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
                        sProcessing: "loading..."
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {"url": "hotline/json", "type": "POST"},
                    columns: [
                        {
                            "data": "id",
                            "orderable": false
                        },{"data": "customer_phone"},{"data": "message"},{"data": "flag_status"},
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

        function editModal(id){
          $.get("<?php echo base_url('Hotline/read') ?>/"+id, function( data ) {
            console.log(data.data)
            $( ".classUpdate" ).each(function() {
              var idT = $(this).attr('id')
              $( this ).val(data.data[idT]);
              console.log($(this).attr('id'))
            });
            $('#modalEdit').modal('show');
          }, "json" );

        }
        function delete_conf(id){
          swal({
              title: "Are you sure?",
              // text: "",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                
                $.ajax({
                      url: "<?php echo base_url('Hotline/delete_action') ?>/"+id,
                      type: "POST",
                      data: [],
                      contentType: false,
                      cache: false,
                      dataType: "json",
                      processData:false,
                      success: function(resp)
                      {
                          // console.log(resp.message)
                          swal(resp.message, {
                            icon: "success",
                          });
                          $( "#refersh" ).click()
                          
                      },
                      error: function (data) {
                          console.log('Error:', data);
                      }
                  });
              }
            });
        }
        </script>