<style>
table.reza-fat tr th{
    padding:0 5px;
}
.right .direct-chat-text {
    margin-right: 20px !important;
}
.active_chat{ background:#ebebeb;}
.direct-chat-text {
   margin-left: 20px !important;
}
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">

            <div class="col-lg-4">
                <div class="box box-info">
                    <div class="box-header ui-sortable-handle" style="cursor: move;">
                        <i class="fa fa-comments-o"></i>
                        <h3 class="box-title">Chat</h3>
                    </div>
                    <!-- <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"> -->
                    <form action="#" id="sendContactForm">
                    <input type="hidden"  id="noPhoneFrom"  placeholder="Search" value="<?php echo $hotline ?>">
                    <div class="box-body chat" id="showdata" style="">
                        
                    </div>
                    </form>
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
                            <button onclick="setPrivate()" type="button" id="private" class="btn btn-box-tool switch" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Private Message">
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
                        <input type="hidden"  id="noPhoneFrom"  value="<?php echo $hotline ?>"/>
                        <input type="hidden"  id="noPhone" value="text"/>
                        <input type="hidden"  id="type"  value="text" />
                        <!-- <div class="form-group">
                  <label>Textarea</label>
                  <textarea id="lala" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                </div> -->
                        <div class="input-group">
                            <input type="text" id="message" placeholder="Type Message ..." class="form-control">
                            
                            <div class="input-group-btn">
                                <div class="btn btn-default btn-file">
                                    <i class="fa fa-paperclip"></i> Attachment
                                    <input type="file" name="attachment" id="profile-img">
                                </div>
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
<script src="<?php echo base_url('assets/plugins/socket.io/socket.io.js') ?>"></script>
<script src="<?php echo base_url('assets/js/inputEmoji.js') ?>"></script>
<script type="text/javascript">
    const icon = '<?php echo base_url();?>assets/icon/';
    const icon_user = "https://www.eltis.org/sites/default/files/styles/adaptive/public/default_images/default_user_0.jpg?itok=oxLSK7Nx";
    var socket = io.connect('http://149.129.222.185:8881',
                    {
                        'reconnection'          : true,
                        'reconnectionDelay'     : 500,
                        'reconnectionAttempts'  : Infinity, 
                        'transports'            : ['websocket'],
                    });

    var private         = 0;
    var status_message  = 0;
    var start           = 0;
    var DataMsg         = [];
    var tempDetail      = "";
    var generateLink    = "<?php echo $generateLink; ?>";
    var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mpeg', 'doc', 'docx', 'pdf', 'odt', 'csv', 'ppt', 'pptx', 'xls', 'xlsx', 'mp3', 'ogg'];
    
    $( document ).ready(function() {
        
        $("#message").emoji({
            place: 'after',button:'&#x1F642;',listCSS: { position:'absolute'},
            rowSize: 10
        });

        $(".switch").click(function(){
            if(status_message==0){
                status_message=1;
            }else if(status_message==1){
                status_message=0;
            }
            
        });
        $("#container_msg").scroll(function (event) {
            var scroll = $("#container_msg").scrollTop();
        });

        $("#sendMsgForm").on('submit',(function(e) {
            e.preventDefault();
            //console.log($("#profile-img").val());
            var data = new FormData();
            data.append("noPhone", $("#noPhone").val());
            data.append("message", $("#message").val());
            data.append("noPhoneFrom", $("#noPhoneFrom").val());
            data.append("fileupload", $("#profile-img").prop('files')[0]);
            data.append("type", $("#type").val());

            var today = new Date();
            var h     = today.getHours();
            var m     = today.getMinutes();
            var s     = today.getSeconds();
            // add a zero in front of numbers<10
            m         = checkTime(m);
            s         = checkTime(s);
            
            if(status_message == 0){
                $.ajax({
                    url         : "<?php echo base_url('ManageChat/send_whatsapp') ?>",
                    type        : "POST",
                    // data: new FormData(this),
                    data        : data,
                    contentType : false,
                    cache       : false,
                    dataType    : "json",
                    processData : false,
                    success     : function(resp)
                    {
                        try {
                            $("#container_msg").html(tempDetail);
                            if(resp.code == "success"){
                                document.getElementById("sendMsgForm").reset();
                            } else{
                                document.getElementById("sendMsgForm").reset();
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

    $("#profile-img").change(function(){
        readURL(this);
    });

    function setPrivate(){
        if(private == 0){
            private = 1;
            loadPersonalChatContact(to);

        }else{
            private = 0;    
        }
    }

    function sendMsg(noPhone){
        var data = {
                noPhone : $("#noPhone").val(),
                message : $("#msgTXT").val()
            }
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

    function enterString(string){
        var string = string;

        if(string.length > 10){
            if(string.length %2 == 1){
                string = string+" ";
            }
            var length  = (string.length / 2);
            var name1   = string.substring(0, length); 
            var name2   = string.substring(length);
            return string = name1+"<br>"+name2;
        }else{
            return string;
        }
    }

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
                alert("Select Another File, This File Not Support");
            }
        }
    }
    
    function appendDetailChatSocket(value){
        
        // console.log(value);
        let html                    = "";
        var detail                  = [];
        detail["user_send_phone"]   = value.customer_phone;
        detail["username_phone"]    = <?php echo $userSession; ?>;
        detail["name"]              = value.user_send_title;
        detail["created"]           = value.created;
        detail["image"]             = value.image;
        detail["video"]             = value.video;
        detail["document"]          = value.document;
        detail["message"]           = value.message;
        detail["type"]              = value.type;
        var xhtml                   = getDetailChat(detail);
        html        += xhtml;
        tempDetail  += html; 
        // $("#container_msg").append(html);
        $("#container_msg").html(tempDetail);
    }

    function loadPersonalContact(to, name, getStart){

        // $("#noPhoneFrom").val(from);
        $("#customer_number").html(name+" ( "+to+" )");
        $("#noPhone").val(to);
        $(".item").removeClass("active_chat");
        $("#item_"+to).addClass("active_chat");
        // $("#private").attr('onclick', 'detail_private('+to+',0)');

        socket.removeListener('get_session');
        socket.removeListener('get_key');

        start       = getStart;
        tempDetail  = "";

        loadPersonalChatContact(to);

        console.log('ok')

        socket.on('get_session', function (data) {
            console.log(data);
            socket_session=data.session;

            socket.emit('get_id', { 
                socket_session  : socket_session,
                id_account      : to
            });
        });

        socket.on('get_key', function(data) {
            const value = JSON.parse(data.datas)
            console.log(value)
            appendDetailChatSocket(value);
            $("#container_msg").animate({ scrollTop: 60000000 }, "slow");
        });
        $("#container_msg").animate({ scrollTop: 60000000 }, "slow");
    }

    function loadPersonalChatContact(to) {
        
        var url = "";
        if(private == 0){
            url = '<?php echo base_url();?>ManageChat/detail_json/<?php echo $hotline; ?>/"+to+"/"+start';
        }else{
            url = 'sss';
        }
        $.get( url, 
            function( data ) {
                // $( ".result" ).html( data );
                // console.log(data)
                var parsed_data = data;
                var from        = $("#noPhone").val()
                let html        = "";

                // console.log(parsed_data);
                DataMsg         = parsed_data.data;//console.log(DataMsg.length)
                start           = start + 10;
                status_message  = 0;

                if(DataMsg.length >= 10){
                    var zhtml = '<button type="button" onclick="loadPersonalChatContact('+to+', '+start+');" class="btn btn-block btn-primary btn-xs">Load More</button>';
                    $("#container_msg").html(zhtml);
                    // html += '<button type="button" onclick="loadPersonalChatContact('+to+', '+start+');" class="btn btn-block btn-primary btn-xs">Load More</button>'
                }
                $(".switch").remove("direct-chat-contacts-open");
                $.each(DataMsg, function( index, value ) {
                    // console.log(value);
                    var detail = [];
                    detail["user_send_phone"]   = value.user_phone;
                    detail["username_phone"]    = <?php echo $userSession; ?>;
                    detail["name"]              = value.username;
                    detail["created"]           = value.created;
                    detail["image"]             = value.image_name;
                    detail["video"]             = value.video_name;
                    detail["document"]          = value.document_name;
                    detail["message"]           = value.message;
                    detail["type"]              = "";
                    var xhtml = getDetailChat(detail);
                    html += xhtml;
                });
                if(start == 0){
                    tempDetail = html;
                }else{
                    tempDetail = html + tempDetail;
                }   
                $("#container_msg").append(tempDetail);
                $("#container_msg_private").append(tempDetail);
            }
        );
    }

    function getDetailChat(chat){
        let html = "";

        if (chat.username_phone == chat.user_send_phone) {
                html += '<div class="direct-chat-msg right">'
                html += '<div class="direct-chat-info clearfix">'
                html += '<span class="direct-chat-name pull-right">'+chat.name+'</span>'
                html += '<span class="ddirect-chat-timestamp pull-left">'+chat.created+'</span>'
                
            }else{
                html += '<div class="direct-chat-msg">'
                html += '<div class="direct-chat-info clearfix">'
                html += '<span class="direct-chat-name pull-left">'+chat.name+'</span>'
                html += '<span class="direct-chat-timestamp pull-right">'+chat.created+'</span>'
            }
                html += '</div>'
                html += '<img class="direct-chat-img" src="'+icon_user+'" alt="Message User Image">'
            
            if(chat.image || chat.video|| chat.document){
                if (chat.username_phone == chat.user_send_phone) {
                html += '<div class="direct-chat-text col-lg-6 pull-right">'
                }else{
                html += '<div class="direct-chat-text col-lg-6 pull-left">'
                }
                    html += '<ul class="mailbox-attachments clearfix">'
                    html += '<li>'
                    if (chat.image) {
                        html += '<span class="mailbox-attachment-icon has-img"><img src="'+generateLink+'image/'+chat.image+'" alt="Attachment"></span>'
                        html += '<div class="mailbox-attachment-info">'
                        html += '<a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> '+enterString(chat.image)+'</a>'
                        html += '<span class="mailbox-attachment-size">'
                        html += '&nbsp;'+chat.message
                        html += '<a href="'+generateLink+'image/'+chat.image+'" target="_blank" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>'
                    }else if(chat.video){
                        html += '<span class="mailbox-attachment-icon"><i class="fa fa-file-movie-o"></i></span>'
                        html += '<div class="mailbox-attachment-info">'
                        html += '<a href="'+generateLink+'video/'+chat.video+'" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> '+enterString(chat.video)+'</a>'
                        html += '&nbsp;'
                        html += '<a href="'+generateLink+'video/'+chat.video+'" target="_blank" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>'
                        
                    }else if(chat.document){
                        html += '<span class="mailbox-attachment-icon"><i class="fa fa-file-movie-o"></i></span>'
                        html += '<div class="mailbox-attachment-info">'
                        html += '<a href="'+generateLink+'document/'+chat.document+'" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> '+enterString(chat.document)+'</a>'
                        html += '&nbsp;'
                        html += '<a href="'+generateLink+'document/'+chat.document+'" target="_blank" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>'
                    }else{}
                    html += '</span>'
                    html += '</div>'
                    html += '</li>'
                    html += '</ul>'
                
                html += '</div>'
            }else{
                if (chat.username_phone == chat.user_send_phone) {
                    html += '<div class="direct-chat-text col-lg-10 pull-right">'
                }else{
                    html += '<div class="direct-chat-text col-lg-10 pull-left">'
                }
                if (chat.message) {
                    html += chat.message
                }else{
                    html += '&nbsp;'
                }
                html += '</div>'
            }
            html += '</div>'
            return html;
    }

    function saveContact(phone){
        
        $("#contact_"+phone+"").replaceWith('<input id="name_'+phone+'" placeholder="Change Contact Name"/>');
        $("#name_"+phone+"").on("change",function() {
            var data = new FormData();
            data.append("phone", $("#noPhone").val());
            data.append("name", $("#name_"+phone+"").val());
            data.append("hotline", $("#noPhoneFrom").val());
            
            $.ajax({
                url         : "<?php echo base_url('ManageChat/saveContact') ?>",
                type        : "POST",
                // data: new FormData(this),
                data        : data,
                contentType : false,
                cache       : false,
                dataType    : "json",
                processData : false,
                success     : function(data)
                {
                    if(data.code == "success"){
                        $('#name_'+phone+'').replaceWith('<span onclick="saveContact('+phone+', 0);" id="contact_'+phone+'">'+data.value+'</span>');
                    } else{
                        $('#name_'+phone+'').replaceWith('<span onclick="saveContact('+phone+', 0);" id="contact_'+phone+'">'+phone+'</span>');
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });
    }
    
    function showContacts(){
        $.ajax({
            url : '<?php echo base_url(); ?>ManageChat/list_hotline_member_json/<?php echo $hotline; ?>', 
            type: 'POST',
            data: '',
            // dataType: 'json',
            success: function(reza){
                var data        = reza.data;
                var html        = '';
                var firstdata   = '';
                for(i=0; i<data.length; i++){
                    var name = '';
                    if(i==0){
                        firstdata=data[i].customer_phone; 
                    }
                    if(data[i].name_replace){
                        name = data[i].name_replace;
                    }else if(data[i].name_wa){
                        name = data[i].name_wa;
                    }else{
                        name = data[i].customer_phone;
                    }
                    // console.log(data[i].name_replace);
                    var phone_num="'"+data[i].customer_phone+"'";
                    var phone_name="'"+name+"'";
                    html += '<div class="item" id="item_'+data[i].customer_phone+'" onclick="loadPersonalContact('+phone_num+', '+phone_name+', 0);">'
                    html += '<img src="'+icon_user+'" alt="user image" class="offline">'
                    html += '<p class="message">'
                    html += '<span class="name">'
                    html += '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> '+data[i].created+'</small>'
                    html += '<span onclick="saveContact('+data[i].customer_phone+', 0)" id="contact_'+data[i].customer_phone+'">'+name+'</span>'
                    html += '</span>'+data[i].message
                    if(data[i].createdby == 'API_WABLAS'){
                        html += '<span data-toggle="tooltip" title="" class="pull-right" data-original-title=""><i class="fa fa-fw fa-circle-o"></i></span>'
                    }else{
                        html += '<span data-toggle="tooltip" title="" class="pull-right" data-original-title=""><i class="fa fa-fw fa-check"></i></span>'
                    }
                    
                    html += '</p>'
                    html += '</div>'
                }
                $("#showdata").append(html);
                loadPersonalContact(firstdata, name, 0);
            },
            error: function(){
                alert('Could not load the data');
            }
        });
    }

    showContacts();
</script>