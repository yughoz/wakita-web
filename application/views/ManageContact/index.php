<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">
    
                    <div class="box-header">
                        <h3 class="box-title">Manage Contact</h3>
                    </div>
        
                    <div class="box-body">
                        <div style="padding-bottom: 10px;">
                        
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">Add Manual Contact</button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal2">Upload CSV</button>
                            <button type="button" class="btn btn-danger btn-sm" id="refersh">Refersh</button>
                        </div>

                        <table class="table table-bordered table-striped" id="mytable">
                            <thead>
                                <tr>
                                    <th width="30px">No</th>
                                    <th>Phone Number</th>
                                    <th>Name From WA</th>
                                    <th>Replace Name</th>
                                    <th>Hotline Number</th>
                                    <th>Created By</th>
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
                <h4 class="modal-title">INPUT CONTACT</h4>
            </div>

            <div class="modal-body"> 
                
                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Phone Number</label>  
                    <div class="col-md-8">
                    <input name="phone" id="phone" placeholder="Phone Number"  type="text" class="form-control input-md">
                    <span class="text-danger" id="error_phone"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Replace Name</label>  
                    <div class="col-md-8">
                    <input name="name" id="name" placeholder="Name Wa"  type="text" class="form-control input-md">
                    <span class="text-danger" id="error_name"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">Hotline</label>  
                    <div class="col-md-8">
                    
                    <input name="hotline" id="hotline" placeholder="Hotline Number"  type="text" class="form-control input-md">
                    <span class="text-danger" id="error_hotline"></span>
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
        <h4 class="modal-title">UPDATE CONTACT </h4>
      </div>
      <div class="modal-body"> 
            <form action="#" method="post" id="form-update_main" class="form-horizontal">
            <input type="hidden" class="form-control classUpdate input-md" id="id" name="id">
    
        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Phone Number</label>  
          <div class="col-md-8">
          <input name="phone" id="phone" placeholder="Phone Number"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_phone"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Name From WA</label>  
          <div class="col-md-8">
          <input name="name_wa" id="name_wa" placeholder="Name From WA"  type="text" readonly='readonly' class="form-control classUpdate input-md">
          <span class="text-danger" id="error_name_wa"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Replace Name</label>  
          <div class="col-md-8">
          <input name="name" id="name_replace" placeholder="Replace Name"  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_name_replace"></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="textinput">Hotline Number</label>  
          <div class="col-md-8">
          <input name="hotline" id="group_hotline" placeholder="Hotline Number" readonly='readonly'  type="text" class="form-control classUpdate input-md">
          <span class="text-danger" id="error_group_hotline"></span>
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
                                url: "<?php echo base_url('ManageContact/create_action') ?>",
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
                                url: "<?php echo base_url('ManageContact/update_action') ?>",
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
                    ajax: {"url": '<?php echo base_url("ManageContact/json") ?>', "type": "POST"},
                    columns: [
                        {
                            "data": "id",
                            "orderable": false
                        },
                        {"data": "phone"},
                        {"data": "name_wa"},
                        {"data": "name_replace"},
                        {"data": "group_hotline"},
                        {"data": "createdby"},
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
          $.get("<?php echo base_url('ManageContact/read') ?>/"+id, function( data ) {
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
                      url: "<?php echo base_url('ManageContact/delete_action') ?>/"+id,
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