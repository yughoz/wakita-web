<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA INBOX</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>'        

	    <tr><td width='200'>Message Id <?php echo form_error('message_id') ?></td><td><input type="text" class="form-control" name="message_id" id="message_id" placeholder="Message Id" value="<?php echo $message_id; ?>" /></td></tr>
	    <tr><td width='200'>FromMe <?php echo form_error('fromMe') ?></td><td><input type="text" class="form-control" name="fromMe" id="fromMe" placeholder="FromMe" value="<?php echo $fromMe; ?>" /></td></tr>
	    <tr><td width='200'>PushName <?php echo form_error('pushName') ?></td><td><input type="text" class="form-control" name="pushName" id="pushName" placeholder="PushName" value="<?php echo $pushName; ?>" /></td></tr>
	    <tr><td width='200'>Phone <?php echo form_error('phone') ?></td><td><input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="<?php echo $phone; ?>" /></td></tr>
	    <tr><td width='200'>Message <?php echo form_error('message') ?></td><td><input type="text" class="form-control" name="message" id="message" placeholder="Message" value="<?php echo $message; ?>" /></td></tr>
	    <tr><td width='200'>Timestamp <?php echo form_error('timestamp') ?></td><td><input type="text" class="form-control" name="timestamp" id="timestamp" placeholder="Timestamp" value="<?php echo $timestamp; ?>" /></td></tr>
	    <tr><td width='200'>Receiver <?php echo form_error('receiver') ?></td><td><input type="text" class="form-control" name="receiver" id="receiver" placeholder="Receiver" value="<?php echo $receiver; ?>" /></td></tr>
	    <tr><td width='200'>GroupId <?php echo form_error('groupId') ?></td><td><input type="text" class="form-control" name="groupId" id="groupId" placeholder="GroupId" value="<?php echo $groupId; ?>" /></td></tr>
	    <tr><td></td><td><input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('inbox') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>