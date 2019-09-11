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
        <h2 style="margin-top:0px">Send_message_detail Read</h2>
        <table class="table">
	    <tr><td>Header Id</td><td><?php echo $header_id; ?></td></tr>
	    <tr><td>From Num</td><td><?php echo $from_num; ?></td></tr>
	    <tr><td>Dest Num</td><td><?php echo $dest_num; ?></td></tr>
	    <tr><td>Message Id</td><td><?php echo $message_id; ?></td></tr>
	    <tr><td>Message Text</td><td><?php echo $message_text; ?></td></tr>
	    <tr><td>Status</td><td><?php echo $status; ?></td></tr>
	    <tr><td>Created</td><td><?php echo $created; ?></td></tr>
	    <tr><td>Createdby</td><td><?php echo $createdby; ?></td></tr>
	    <tr><td>Updated</td><td><?php echo $updated; ?></td></tr>
	    <tr><td>Updatedby</td><td><?php echo $updatedby; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('send_message') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>