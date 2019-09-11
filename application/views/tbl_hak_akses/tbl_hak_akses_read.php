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
        <h2 style="margin-top:0px">Tbl_hak_akses Read</h2>
        <table class="table">
	    <tr><td>Id User Level</td><td><?php echo $id_user_level; ?></td></tr>
	    <tr><td>Id Menu</td><td><?php echo $id_menu; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_hak_akses') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>