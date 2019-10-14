<style type="text/css">
  .selectize-control.selectizer .selectize-dropdown [data-selectable] {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            /*height: 60px;*/
            position: relative;
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
            padding: 10px 10px 10px 10px;
        }
  .transaction-price {
  position: absolute;
  right: 26px;
  top: 33px;
  font-size: 80px;
  line-height: 1;
  font-weight: bold;
  font-family: "arial";
}
.transaction-btn {
  /*text-align: right;
  margin-top: -50px;*/
  /*margin-bottom: 12px;*/
}

.grand-total-payment {
  text-align: right;
  line-height: 1;
  font-size: 20px;
}
  .grand-total-payment .grand-total-payment-price {
    padding-left: 20px;
    font-size: 50px;
    font-family: "arial";
    font-weight: bold;
  }

.total-change {
  font-size: 15px;
  line-height: 1;
  float: left;
  margin-top: 24px;
}
  .total-change .total-change-price {
    padding-left: 5px;
    font-size: 20px;
    font-family: "arial";
    font-weight: bold;
  }

.transaction-group-code-div {
  position: absolute;
  right: 32px;
  top: 8px;
}
  .transaction-group-code-div input {
    text-align: right;
    text-align: right;
    border: none;
    font-weight: bold;
  }
</style>
<div class="content-wrapper">

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">
    
                    <div class="box-header">
                        <h3 class="box-title">MANAGE HOTLINE MEMBER</h3>
                    </div>
        
        <div class="box-body">
        <div style="padding-bottom: 10px;">
        
        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#addMemberModal">Add Data</button>
        <button type="button" class="btn btn-danger btn-sm" id="refersh" name="refersh">Refersh</button>
        </div>
        
        <div class="alert alert-info alert-dismissible">
          <h4> <?php echo $hotlineData->device_name ?></h4>
          <h4> <?php echo $hotlineData->device_id ?></h4>
          <?php if ($hotlineData->wa_status == "connected"): ?>
          <h4><span class="label label-success"> <?php echo $hotlineData->wa_status ?></span></h4>
          
          <?php else: ?>
          <h4><span class="label label-danger"> <?php echo $hotlineData->wa_status ?></span></h4>

          <?php endif ?>
          
        </div>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="30px">No</th>
                    <!-- <th>Hotline Name</th> -->
                    <th>User</th>
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
<div id="addMemberModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Member To Hotline</h4>
      </div>
      <div class="modal-body"> 
            <form action="#" method="post" id="form-create_main" class="form-horizontal">
            
    
      <button style="display: none;" type="button" class="btn btn-danger btn-sm" id="refershUser" name="refersh">Refersh</button>
      <table class="table table-bordered table-striped" id="tableUser">
            <thead>
                <tr>
                    <th width="30px">No</th>
        <th>Full Name</th>
            <th>Phone</th>
        <th width="200px">Action</th>
                </tr>
            </thead>
      
        </table>

         </div>
      <div class="modal-footer">
          <div class="form-group">
              <label class="col-md-4 control-label" for="button1id"></label>
              <div class="col-md-8">
                <button id="close" name="close" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
            </form>       
      </div>
    </div>

  </div>
</div>

        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/selectize/dist/css/selectize.bootstrap2.css">
        <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/selectize/dist/js/standalone/selectize.js"></script>
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

                    },
                    oLanguage: {
                        sProcessing: "loading..."
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {"url": '<?php echo base_url("ManageHotlineMember/json/").$member ?>', "type": "POST"},
                    columns: [
                        {
                            "data": "id",
                            "orderable": false
                        },{"data": "username"},
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
        var tUserr = $("#tableUser").dataTable({
                    initComplete: function() {
                        var api = this.api();
                        $('#tableUser_filter input')
                                .off('.DT')
                                .on('keyup.DT', function(e) {
                                    if (e.keyCode == 13) {
                                        api.search(this.value).draw();
                            }
                        });
                        $("#refershUser").click(function() {
                            api.search(this.value).draw();
                        });
                    },
                    oLanguage: {
                        sProcessing: "loading..."
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {"url": '<?php echo base_url("ManageHotlineMember/user/").$member ?>', "type": "POST"},
                    columns: [
                        {
                            "data": "id_users",
                            "orderable": false
                        },{"data": "full_name"},{"data": "phone"},
                        {
                            "data" : "selecting",
                            "orderable": false,
                            "className" : "text-center"
                        }
                    ],
                    order: [[0, 'desc']],
                    // rowCallback: function(row, data, iDisplayIndex) {
                    //     var info = this.fnPagingInfo();
                    //     var page = info.iPage;
                    //     var length = info.iLength;
                    //     var index = page * length + (iDisplayIndex + 1);
                    //     $('td:eq(0)', row).html(index);
                    // }
                });

        $('#productSelect').selectize({
                  valueField: 'barcode',
                  labelField: 'name',
                  searchField: 'name',
                  options: [],
                  create: false,
                  render: {
                      option: function(item, escape) {

                          return '<div>' +
                              '<span class="title">' +
                                  '<span class="name">' + escape(item.name) + '</span>' +
                              '</span>' +
                          '</div>';
                      }
                  },
                  load: function(query, callback) {
                      if (!query.length) return callback();
                      $.ajax({
                          url: '<?php echo base_url() ?>transaction/getSelect',
                          type: 'GET',
                          dataType: 'json',
                          data: {
                              p_name: query,
                          },
                          error: function() {
                              callback();
                          },
                          success: function(res) {
                              // console.log(res.data[0].name);
                              // callback(res.data[0].name);
                              callback(res.data);
                          }
                      });
                  }
              });

        function selectingFunc(id){
          $.get("<?php echo base_url('ManageHotlineMember/add_member/').$member ?>/"+id, function( data ) {
            console.log(data.data)
            $( ".classUpdate" ).each(function() {
              var idT = $(this).attr('id')
              $( this ).val(data.data[idT]);
              console.log($(this).attr('id'))
            });
            $('#addMemberModal').modal('show');
            $( "#refersh" ).click()
            $( "#refershUser" ).click()
          }, "json" );

        }
        function delete_conf(id){
          console.log(id)
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
                      url: "<?php echo base_url('ManageHotlineMember/delete_action') ?>/"+id,
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
                          $( "#refershUser" ).click()
                          
                      },
                      error: function (data) {
                          console.log('Error:', data);
                      }
                  });
              }
            });
        }
        </script>