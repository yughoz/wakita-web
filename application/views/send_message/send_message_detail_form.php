<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA SEND_MESSAGE_DETAIL</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>'        

	    <tr><td width='200'>Header Id <?php echo form_error('header_id') ?></td><td><input type="text" class="form-control" name="header_id" id="header_id" placeholder="Header Id" value="<?php echo $header_id; ?>" /></td></tr>
	    <tr><td width='200'>From Num <?php echo form_error('from_num') ?></td><td><input type="text" class="form-control" name="from_num" id="from_num" placeholder="From Num" value="<?php echo $from_num; ?>" /></td></tr>
	    <tr><td width='200'>Dest Num <?php echo form_error('dest_num') ?></td><td><input type="text" class="form-control" name="dest_num" id="dest_num" placeholder="Dest Num" value="<?php echo $dest_num; ?>" /></td></tr>
	    <tr><td width='200'>Message Id <?php echo form_error('message_id') ?></td><td><input type="text" class="form-control" name="message_id" id="message_id" placeholder="Message Id" value="<?php echo $message_id; ?>" /></td></tr>
	    <tr><td width='200'>Message Text <?php echo form_error('message_text') ?></td><td><input type="text" class="form-control" name="message_text" id="message_text" placeholder="Message Text" value="<?php echo $message_text; ?>" /></td></tr>
	    <tr><td width='200'>Status <?php echo form_error('status') ?></td><td><input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" /></td></tr>
	    <tr><td width='200'>Created <?php echo form_error('created') ?></td><td><input type="text" class="form-control" name="created" id="created" placeholder="Created" value="<?php echo $created; ?>" /></td></tr>
	    <tr><td width='200'>Createdby <?php echo form_error('createdby') ?></td><td><input type="text" class="form-control" name="createdby" id="createdby" placeholder="Createdby" value="<?php echo $createdby; ?>" /></td></tr>
	    <tr><td width='200'>Updated <?php echo form_error('updated') ?></td><td><input type="text" class="form-control" name="updated" id="updated" placeholder="Updated" value="<?php echo $updated; ?>" /></td></tr>
	    <tr><td width='200'>Updatedby <?php echo form_error('updatedby') ?></td><td><input type="text" class="form-control" name="updatedby" id="updatedby" placeholder="Updatedby" value="<?php echo $updatedby; ?>" /></td></tr>
	    <tr><td></td><td><input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('send_message') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>