<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA TBL_SETTING</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>'        

	    <tr><td width='200'>Nama Setting <?php echo form_error('nama_setting') ?></td><td><input type="text" class="form-control" name="nama_setting" id="nama_setting" placeholder="Nama Setting" value="<?php echo $nama_setting; ?>" /></td></tr>
	    <tr><td width='200'>Value <?php echo form_error('value') ?></td><td><input type="text" class="form-control" name="value" id="value" placeholder="Value" value="<?php echo $value; ?>" /></td></tr>
	    <tr><td></td><td><input type="hidden" name="id_setting" value="<?php echo $id_setting; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_setting') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>