<style>
table.reza-fat tr th{
    padding:0 5px;
}
.active_chat{ background:#ebebeb;}
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">

            <div class="col-lg-4">
                <div class="box box-success">
                    <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-comments-o"></i>
                    <h3 class="box-title">Chat</h3>
                    </div>
                    <!-- <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"> -->
                        <div class="box-body chat" id="showdata" style="">
                            
                        </div>
                        <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 184.911px;"></div>
                        <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                    <!-- </div> -->
                </div>
            </div>

            <div class="col-lg-8">
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                        <h3 id='customer_number' class="box-title">Customer Number</h3>

                        <div class="box-tools pull-right">
                            <span data-toggle="tooltip" title="" class="badge bg-light-blue" data-original-title="3 New Messages">3</span>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" id="private" class="btn btn-box-tool switch" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Private Message">
                            <i class="fa fa-comments"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" >
                        <!-- Conversations are loaded here -->
                        <div class="direct-chat-messages"  id="container_msg">
                            
                        </div>
                        <!--/.direct-chat-messages-->

                        <!-- Contacts are loaded here -->
                        <div class="direct-chat-contacts">
                            <div class="direct-chat-messages" id="container_msg_private">
                               
                            </div>
                            <!--/.direct-chat-messages-->
                        </div>
                        <!-- /.direct-chat-pane -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                    <form action="#" id="sendMsgForm">
                        <input type="hidden"  id="noPhoneFrom"  placeholder="Search" value="<?php echo $hotline ?>">
                        <input type="hidden"  id="noPhone"  placeholder="Search" >
                        <input type="hidden"  id="type"  value="text" >
                        <div class="input-group">
                        <input type="text" id="message" placeholder="Type Message ..." class="form-control">
                        <div class="input-group-btn">
                            <input type="file" size="60" id="profile-img" class="btn btn-success"><i class="fa fa-plus"></i>
                        </div>
                        
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-flat">Send</button>
                            </span>
                        </div>
                    </form>
                    </div>
                    <!-- /.box-footer-->
                </div>
            </div>
        </div>
    </section>
</div>


<script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
<script type="text/javascript">
    var status_message=0;
    var DataMsg = [];
    $( document ).ready(function() {
        
    // $("#selectKeys").val(<?php //echo $user_number['numberArr'] ?>)
    // $("#selectKeys" ).change(function() {
    //   // alert($( "#selectKeys").val() );
    //   $.get( "<?php //echo base_url();?>Login/changeKeysNum/"+$("#selectKeys").val(), function( data ) {
    //       // $( ".result" ).html( data );
    //         location.reload();
    //         var parsed_data = JSON.parse(data);
    //         console.log(parsed_data);
    //     });
    // });
    $(".switch").click(function(){
        if(status_message==0){
            status_message=1;
        }else if(status_message==1){
            status_message=0;
        }
        
    });
    $("#container_msg").scroll(function (event) {
        // console.log(event)
        var scroll = $("#container_msg").scrollTop();
        console.log('scrollTop',$("#container_msg").scrollTop())
        console.log('mesgs_cont',$("#mesgs_cont").height())
        console.log('height',$("#container_msg").height())
        console.log(scroll)
        // Do something
    });

    $("#sendMsgForm").on('submit',(function(e) {
        e.preventDefault();
        // var data = {
        //     noPhone : $("#noPhone").val(),
        //     message : $("#message").val(),
        //     hotline : $("#noPhoneFrom").val(),
        //     file : $("#profile-img").val(),
        //     type : $("#type").val(),
        // }
        console.log($("#profile-img").val());
        var data = new FormData();
        data.append("noPhone", $("#noPhone").val());
        data.append("message", $("#message").val());
        data.append("noPhoneFrom", $("#noPhoneFrom").val());
        data.append("fileupload", $("#profile-img").prop('files')[0]);
        data.append("type", $("#type").val());

        // console.log(data)

        var today = new Date();
        var h     = today.getHours();
        var m     = today.getMinutes();
        var s     = today.getSeconds();
        // add a zero in front of numbers<10
        m         = checkTime(m);
        s         = checkTime(s);
        // document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
        // create_date: h + ":" + m + ":" + s,

        // DataMsg[DataMsg.length] = {
        //     action          : "inbox",
        //     create_date     : "sending",
        //     destination     : $("#noPhone").val(),
        //     from            : $("#noPhoneFrom").val(),
        //     message         : $("#message").val(),
        //     number_contact  : $("#noPhone").val(),
        //     type            : "text"
        // }

        // console.log(DataMsg)
        // reloadRow();
        // $("#message").val("");
        // $("#container_msg").animate({ scrollTop: 20000000 }, "slow");
        if(status_message==0){
            // alert("3221")
            $.ajax({
                url: "<?php echo base_url('ManageChat/send_whatsapp') ?>",
                type: "POST",
                // data: new FormData(this),
                data : data,
                contentType: false,
                cache: false,
                dataType: "json",
                processData:false,
                success: function(resp)
                {
                    try {
                        console.log(resp);
                            parseData = $.parseJSON(resp);
                            // parseData = resp;
                            // detail()
                            if(resp['status'] == "success"){
                            } else{
                            }
                        } catch(e) {
                        console.log(e);
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
            });
            // $.ajax({
                
            //     // url: self.baseUrl+"ajaxAddQuotes", // Url to which the request is send
            //     url     : "<?php echo base_url();?>ManageChat/send_whatsapp", // Url to which the request is send
            //     type    : "POST",             // Type of request to be send, called as method
            //     enctype: 'multipart/form-data',
            //     data: new FormData(this),
            //     // data    : data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            //     // contentType: false,       // The content type used when sending data to the server.
            //     cache   : false,             // To unable request pages to be cached
            //     // processData:false,        // To send DOMDocument or non processed data file it is set to false
            //     success: function(resp)   // A function to be called if request succeeds
            //     {
            //         // alert(<?php //echo $hotline; ?>)
            //         try {
            //             console.log(resp);
            //             parseData = $.parseJSON(resp);
            //             // parseData = resp;
            //             // detail()
            //             if(resp['status'] == "success"){
            //             } else{
            //             }
            //         } catch(e) {
            //         console.log(e);
            //         }
            //     },
            //     error: function (data) {
            //         console.log('Error:', data);
            //     }
            // });
        }else if(status_message==1){    
            $.ajax({
                headers : { 'X-CSRF-TOKEN': $('meta[name="yuu-token"]').attr('content')},
                // url: self.baseUrl+"ajaxAddQuotes", // Url to which the request is send
                url     : "<?php echo base_url();?>ManageChat/send_wa_milis", // Url to which the request is send
                type    : "POST",             // Type of request to be send, called as method
                data    : data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                // contentType: false,       // The content type used when sending data to the server.
                cache   : false,             // To unable request pages to be cached
                // processData:false,        // To send DOMDocument or non processed data file it is set to false
                success: function(resp)   // A function to be called if request succeeds
                {
                    // alert(<?php //echo $hotline; ?>)
                    try {
                    console.log(resp);
                    parseData = $.parseJSON(resp);
                    // parseData = resp;
                    // detail()
                    if(resp['status'] == "success"){
                    } else{
                    }
                    } catch(e) {
                    console.log(e);
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
        
    }));

    });

    function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
    }

    var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mpeg', 'doc', 'docx', 'pdf', 'odt', 'csv', 'ppt', 'pptx', 'xls', 'xlsx', 'mp3', 'ogg'];
    function readURL(input) {
        
        
        if (input.files && input.files[0]) {
        var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
            isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types

        if (isSuccess) { //yes
            var reader = new FileReader();
            reader.onload = function (e) {
                let html = "";
                html += '<img class="img-responsive pad" src="" id="profile-img-tag" alt="photo" />';
                html += '<p>'+input.files[0].name+'</p>';
                $("#container_msg").html(html);
                if(extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif'){
                    $('#profile-img-tag').attr('src', e.target.result);
                    $("#type").val('image');
                }else if(extension == 'mp4' || extension == 'mpeg'){
                    $('#profile-img-tag').attr('src', 'https://image.flaticon.com/icons/svg/260/260453.svg');
                    $("#type").val('video')
                }else if(extension == 'doc' || extension == 'docx' || extension == 'pdf' || extension == 'odt' || extension == 'csv' || extension == 'ppt' || extension == 'pptx' || extension == 'xls' || extension == 'xlsx' || extension == 'mp3' || extension == 'ogg'){
                    $('#profile-img-tag').attr('src', 'https://image.flaticon.com/icons/svg/579/579879.svg');
                    $("#type").val('document')
                }else{
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
        else { //no
            alert("File Not Support");
        }
    }
    }
    $("#profile-img").change(function(){
        readURL(this);
    });

    function reloadClick(to,start){
        console.log(to)
        
        if(start==0){
            detail(to,start);
        }else if(start!=0){
            detail_previous(to, start);
        }
        $("#container_msg").animate({ scrollTop: 20000000 }, "slow");
    }

    function detail_private(to,start){
        console.log(to)
        
        if(start==0){
            detail(to,start);
        }else if(start!=0){
            detail_previous(to, start);
        }
        $("#container_msg_private").animate({ scrollTop: 20000000 }, "slow");
    }

    function detail_previous(to,start) {
        // $("#noPhoneFrom").val(from);
        $("#customer_number").html(to);
        $("#noPhone").val(to);
        $(".item").removeClass("active_chat");
        $("#item_"+to).addClass("active_chat");
        $("#private").attr('onclick', '');

        $.get( "<?php echo base_url();?>ManageChat/detail_json/<?php echo $hotline; ?>/"+to+"/"+start, function( data ) {
        // $( ".result" ).html( data );

        // console.log(data)
        
        var parsed_data = data;
            console.log(parsed_data);
            DataMsg = parsed_data.data;
            reloadRow();
            // setTimeout(function(){ detail($("#noPhone").val()) }, 10000);
        });
    }

    function detail(to,start) {
        // $("#noPhoneFrom").val(from);
        $("#customer_number").html(to);
        $("#noPhone").val(to);
        $(".item").removeClass("active_chat");
        $("#item_"+to).addClass("active_chat");
        $("#private").attr('onclick', 'detail_private('+to+',0)');

        $.get( "<?php echo base_url();?>ManageChat/detail_json/<?php echo $hotline; ?>/"+to+"/"+start, function( data ) {
        // $( ".result" ).html( data );

        // console.log(data)
        
        var parsed_data = data;
            console.log(parsed_data);
            DataMsg = parsed_data.data;
            reloadRow();
            // setTimeout(function(){ detail($("#noPhone").val()) }, 10000);
        });
    }

    function reloadRow (){
        console.log(DataMsg.length)
        var from = $("#noPhone").val()
        let html = "";
        status_message=0;
        $(".switch").remove("direct-chat-contacts-open");
        $.each(DataMsg, function( index, value ) {
        
            if (from == value._idUser) {
                html += '<div class="direct-chat-msg">'
                html += '<div class="direct-chat-info clearfix">'
                html += '<span class="direct-chat-name pull-left">'+value._idUser+'</span>'
                html += '<span class="direct-chat-timestamp pull-right">'+value.created+'</span>'
                html += '</div>'

                html += '<img class="direct-chat-img" src="https://www.eltis.org/sites/default/files/styles/adaptive/public/default_images/default_user_0.jpg?itok=oxLSK7Nx" alt="Message User Image">'
                html += '<div class="direct-chat-text">'
                if (value.type == "img") {
                    html += '<img class="direct-chat-img" src="'+value.message+'" alt="Message User Image">'
                } else {
                    html += value.message
                }
                html += '</div>'
                html += '</div>'

            } else {

                html += '<div class="direct-chat-msg right">'
                html += '<div class="direct-chat-info clearfix">'
                html += '<span class="direct-chat-name pull-right">'+value._idUser+'</span>'
                html += '<span class="ddirect-chat-timestamp pull-left">'+value.created+'</span>'
                html += '</div>'
                html += '<img class="direct-chat-img" src="https://www.eltis.org/sites/default/files/styles/adaptive/public/default_images/default_user_0.jpg?itok=oxLSK7Nx" alt="Message User Image">'
                html += '<div class="direct-chat-text">'
                if (value.type == "img") {
                    html += '<img class="direct-chat-img" src="'+value.message+'" alt="Message User Image">'
                } else {
                    html += value.message
                }
                html += '</div>'
                html += '</div>'
            }
        });

        $("#container_msg").html(html);
    }

    function reloadRow_private (){
        console.log(DataMsg.length)
        var from = $("#noPhone").val()
        let html = "";
        status_message=0;
        $(".switch").remove("direct-chat-contacts-open");
        $.each(DataMsg, function( index, value ) {
        
            if (from == value._idUser) {
                html += '<div class="direct-chat-msg">'
                html += '<div class="direct-chat-info clearfix">'
                html += '<span class="direct-chat-name pull-left">'+value._idUser+'</span>'
                html += '<span class="direct-chat-timestamp pull-right">'+value.created+'</span>'
                html += '</div>'

                html += '<img class="direct-chat-img" src="https://www.eltis.org/sites/default/files/styles/adaptive/public/default_images/default_user_0.jpg?itok=oxLSK7Nx" alt="Message User Image">'
                html += '<div class="direct-chat-text">'
                if (value.type == "img") {
                    html += '<img class="direct-chat-img" src="'+value.message+'" alt="Message User Image">'
                } else {
                    html += value.message
                }
                html += '</div>'
                html += '</div>'

            } else {

                html += '<div class="direct-chat-msg right">'
                html += '<div class="direct-chat-info clearfix">'
                html += '<span class="direct-chat-name pull-right">'+value._idUser+'</span>'
                html += '<span class="ddirect-chat-timestamp pull-left">'+value.created+'</span>'
                html += '</div>'
                html += '<img class="direct-chat-img" src="https://www.eltis.org/sites/default/files/styles/adaptive/public/default_images/default_user_0.jpg?itok=oxLSK7Nx" alt="Message User Image">'
                html += '<div class="direct-chat-text">'
                if (value.type == "img") {
                    html += '<img class="direct-chat-img" src="'+value.message+'" alt="Message User Image">'
                } else {
                    html += value.message
                }
                html += '</div>'
                html += '</div>'
            }
        });

        $("#container_msg").html(html);$("#container_msg").html(html);
    }

    function sendMsg(noPhone){
    var data = {
        noPhone : $("#noPhone").val(),
        message : $("#msgTXT").val()
        }
    }
    
    function showRecords(){
        $.ajax({
            url : '<?php echo base_url(); ?>ManageChat/list_hotline_member_json/6281299898515', 
            type: 'POST',
            data: '',
            // dataType: 'json',
            success: function(reza){
                var data = reza.data;
                var html = '';
                var firstdata="";
                for(i=0; i<data.length; i++){
                    if(i==0){
                        firstdata=data[i].customer_phone; 
                    }
                    html += '<div class="item" id="item_'+data[i].customer_phone+'" onclick="reloadClick('+data[i].customer_phone+', 0);">'+
                                '<img src="https://www.eltis.org/sites/default/files/styles/adaptive/public/default_images/default_user_0.jpg?itok=oxLSK7Nx" alt="user image" class="offline">'+
                                '<p class="message">'+
                                '<a href="#" class="name">'+
                                    '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> '+data[i].created+'</small>'+
                                    ''+data[i].customer_phone+''+
                                '</a>'+data[i].message+
                                '<span data-toggle="tooltip" title="" class="badge bg-light-blue pull-right" data-original-title="3 New Messages">3</span>'+
                                '</p>'+
                            '</div>';
                }
                $("#showdata").append(html);
                reloadClick(firstdata, 0);
            },
            error: function(){
                alert('Could not load the data');
            }
        });
    }

    showRecords();

    

</script>