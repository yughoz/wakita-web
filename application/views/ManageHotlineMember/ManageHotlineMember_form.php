<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA MILIS_MEMBER</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered'>

	    <tr><td width='200'>Milis Id <?php echo form_error('milis_id') ?></td><td><input type="text" class="form-control" name="milis_id" id="milis_id" placeholder="Milis Id" value="<?php echo $milis_id; ?>" /></td></tr>
	    <tr><td width='200'>Contact Admin <?php echo form_error('contact_admin') ?></td><td><input type="text" class="form-control" name="contact_admin" id="contact_admin" placeholder="Contact Admin" value="<?php echo $contact_admin; ?>" /></td></tr>
	    <tr><td width='200'>Created <?php echo form_error('created') ?></td><td><input type="text" class="form-control" name="created" id="created" placeholder="Created" value="<?php echo $created; ?>" /></td></tr>
	    <tr><td width='200'>Createdby <?php echo form_error('createdby') ?></td><td><input type="text" class="form-control" name="createdby" id="createdby" placeholder="Createdby" value="<?php echo $createdby; ?>" /></td></tr>
	    <tr><td width='200'>Updated <?php echo form_error('updated') ?></td><td><input type="text" class="form-control" name="updated" id="updated" placeholder="Updated" value="<?php echo $updated; ?>" /></td></tr>
	    <tr><td width='200'>Updatedby <?php echo form_error('updatedby') ?></td><td><input type="text" class="form-control" name="updatedby" id="updatedby" placeholder="Updatedby" value="<?php echo $updatedby; ?>" /></td></tr>
	    <tr><td></td><td><input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('milis_member') ?>" class="btn bt-ninfo"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>