<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Milis_member Read</h2>
        <table class="table">
	    <tr><td>Milis Id</td><td><?php echo $milis_id; ?></td></tr>
	    <tr><td>Contact Admin</td><td><?php echo $contact_admin; ?></td></tr>
	    <tr><td>Created</td><td><?php echo $created; ?></td></tr>
	    <tr><td>Createdby</td><td><?php echo $createdby; ?></td></tr>
	    <tr><td>Updated</td><td><?php echo $updated; ?></td></tr>
	    <tr><td>Updatedby</td><td><?php echo $updatedby; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('milis_member') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>