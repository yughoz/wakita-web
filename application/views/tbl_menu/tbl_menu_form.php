<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA TBL_MENU</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>'        

	    <tr><td width='200'>Title <?php echo form_error('title') ?></td><td><input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $title; ?>" /></td></tr>
	    <tr><td width='200'>Url <?php echo form_error('url') ?></td><td><input type="text" class="form-control" name="url" id="url" placeholder="Url" value="<?php echo $url; ?>" /></td></tr>
	    <tr><td width='200'>Icon <?php echo form_error('icon') ?></td><td><input type="text" class="form-control" name="icon" id="icon" placeholder="Icon" value="<?php echo $icon; ?>" /></td></tr>
	    <tr><td width='200'>Is Main Menu <?php echo form_error('is_main_menu') ?></td><td><input type="text" class="form-control" name="is_main_menu" id="is_main_menu" placeholder="Is Main Menu" value="<?php echo $is_main_menu; ?>" /></td></tr>
	    <tr><td width='200'>Is Aktif <?php echo form_error('is_aktif') ?></td><td><input type="text" class="form-control" name="is_aktif" id="is_aktif" placeholder="Is Aktif" value="<?php echo $is_aktif; ?>" /></td></tr>
	    <tr><td></td><td><input type="hidden" name="id_menu" value="<?php echo $id_menu; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_menu') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>