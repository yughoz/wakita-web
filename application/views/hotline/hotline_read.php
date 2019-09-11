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
        <h2 style="margin-top:0px">Hotline Read</h2>
        <table class="table">
	    <tr><td>Customer Phone</td><td><?php echo $customer_phone; ?></td></tr>
	    <tr><td>Message</td><td><?php echo $message; ?></td></tr>
	    <tr><td>Flag Status</td><td><?php echo $flag_status; ?></td></tr>
	    <tr><td>Created</td><td><?php echo $created; ?></td></tr>
	    <tr><td>Createdby</td><td><?php echo $createdby; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('hotline') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>