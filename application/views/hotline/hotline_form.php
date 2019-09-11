<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA HOTLINE</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>'        

	    <tr><td width='200'>Customer Phone <?php echo form_error('customer_phone') ?></td><td><input type="text" class="form-control" name="customer_phone" id="customer_phone" placeholder="Customer Phone" value="<?php echo $customer_phone; ?>" /></td></tr>
	    <tr><td width='200'>Message <?php echo form_error('message') ?></td><td><input type="text" class="form-control" name="message" id="message" placeholder="Message" value="<?php echo $message; ?>" /></td></tr>
	    <tr><td width='200'>Flag Status <?php echo form_error('flag_status') ?></td><td><input type="text" class="form-control" name="flag_status" id="flag_status" placeholder="Flag Status" value="<?php echo $flag_status; ?>" /></td></tr>
	    <tr><td width='200'>Created <?php echo form_error('created') ?></td><td><input type="text" class="form-control" name="created" id="created" placeholder="Created" value="<?php echo $created; ?>" /></td></tr>
	    <tr><td width='200'>Createdby <?php echo form_error('createdby') ?></td><td><input type="text" class="form-control" name="createdby" id="createdby" placeholder="Createdby" value="<?php echo $createdby; ?>" /></td></tr>
	    <tr><td></td><td><input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('hotline') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>