<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">
    
                    <div class="box-header">
                        <h3 class="box-title">DATA SEND MESSAGE</h3>
                    </div>
        
        <div class="box-body">
        <div style="padding-bottom: 10px;">
        <?php if (checking_akses("send_message_add")): ?>
          <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">Send WA</button>
          <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#sendImgModal">Send File</button>
          
        <?php endif ?>
        <button type="button" class="btn btn-danger btn-sm" id="refersh">Refersh</button>
        </div>
        <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                  <th width="30px">No</th>
                  <!-- <th>Header Id</th> -->
                  <th>Date</th>
                  <th>From</th>
                  <th>Dest</th>
                  <th>Message Id</th>
                  <th>Message Text</th>
                  <th>Status</th>
                  <!-- <th width="200px">Doc</th> -->
                </tr>
            </thead>
	    
        </table>
        </div>
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
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">SEND WA</h4>
      </div>
      <div class="modal-body"> 
            <form action="#" method="post" id="form-create_main" class="form-horizontal">
            
    
      <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">Phone</label>  
            <div class="col-md-8">
            <input name="dest_num" id="dest_num" placeholder="phone"  type="text" class="form-control input-md">
            <span class="text-danger" id="error_dest_num"></span>
            </div>
          </div>   
         
         
      <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">Message Text</label>  
            <div class="col-md-8">
            <input name="message_text" id="message_text" placeholder="Message Text"  type="text" class="form-control input-md">
            <span class="text-danger" id="error_message_text"></span>
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
<!-- Modal -->
<div id="sendImgModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Image</h4>
      </div>
      <div class="modal-body"> 
            <form action="#" method="post" id="form-sendIMG" class="form-horizontal">
            
    
      <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">Phone</label>  
            <div class="col-md-8">
            <input name="dest_num" id="dest_num" placeholder="phone"  type="text" class="form-control input-md">
            <span class="text-danger" id="error_dest_num"></span>
            </div>
          </div>   
         
         
      <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">caption</label>  
            <div class="col-md-8">
            <input name="message_text" id="message_text" placeholder="Message Text"  type="text" class="form-control input-md">
            <span class="text-danger" id="error_message_text"></span>
            </div>
          </div>   

      <!-- Multiple Radios (inline) -->
      <div class="form-group">
        <label class="col-md-4 control-label" for="radios">File Type</label>
        <div class="col-md-4"> 
          <label class="radio-inline" for="radios-0">
            <input type="radio" name="type_file" id="type_image" value="image_file" checked="checked">
            Image
          </label> 
          <label class="radio-inline" for="radios-1">
            <input type="radio" name="type_file" id="type_doc" value="doc_file">
            Document
          </label>
        </div>
      </div>
      <!-- Multiple Radios (inline) -->
      <div class="form-group" style="display: none">
        <label class="col-md-4 control-label" for="radios">Image</label>
        <div class="col-md-4"> 
          <label class="radio-inline" for="radios-0">
            <input type="radio" name="type_image" id="url_image" value="url" >
            URL
          </label> 
          <label class="radio-inline" for="radios-1">
            <input type="radio" name="type_image" id="upload_image" value="image_file" checked="checked">
            FILE
          </label>
        </div>
      </div>

      <div class="form-group" id="urlContainer">
            <label class="col-md-4 control-label" for="textinput">Url</label>  
            <div class="col-md-8">
            <input name="url" id="url" placeholder="Image Url"  type="text" class="form-control input-md">
            <span class="text-danger" id="error_url"></span>
            </div>
          </div>   

          <!-- File Button --> 
      <div class="form-group" id="fileContainer">
        <label class="col-md-4 control-label" for="filebutton">Img Upload</label>
        <div class="col-md-4">
          <input id="images" name="images" class="input-file" type="file">
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

        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#urlContainer").hide();
                $("#fileContainer").show();
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
                                url: "<?php echo base_url('Send_message/create_action') ?>",
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


                        $("#form-sendIMG").on('submit',(function(e) {
                            e.preventDefault();
                            var apis = api
                            $( "span.text-danger" ).each(function() {
                              $( this ).html( "" );
                            });
                            $.ajax({
                                url: "<?php echo base_url('Send_message/create_img_action') ?>",
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
                                            document.getElementById("form-sendIMG").reset();
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

                    },
                    oLanguage: {
                        sProcessing: "loading..."
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {"url": "send_message/json", "type": "POST"},
                    columns: [
                        {
                            "data": "id",
                            "orderable": false
                        },{"data" : "created"},{"data": "from_num"},{"data": "dest_num"},{"data": "message_id"},{"data": "message_text"},{"data": "status"},
                        // {
                        //     "data" : "action",
                        //     "orderable": false,
                        //     "className" : "text-center"
                        // }
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

        $("input[name='type_image']").change(function(){
            if($(this).val()== "url"){
              $("#urlContainer").show();
              $("#fileContainer").hide();

            } else {
              $("#urlContainer").hide();
              $("#fileContainer").show();
            }
            // alert($(this).val())
        });
        function editModal(id){
          $.get("<?php echo base_url('Send_message/read') ?>/"+id, function( data ) {
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
                      url: "<?php echo base_url('Send_message/delete_action') ?>/"+id,
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