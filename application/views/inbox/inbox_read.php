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
        <h2 style="margin-top:0px">Inbox Read</h2>
        <table class="table">
	    <tr><td>Message Id</td><td><?php echo $message_id; ?></td></tr>
	    <tr><td>FromMe</td><td><?php echo $fromMe; ?></td></tr>
	    <tr><td>PushName</td><td><?php echo $pushName; ?></td></tr>
	    <tr><td>Phone</td><td><?php echo $phone; ?></td></tr>
	    <tr><td>Message</td><td><?php echo $message; ?></td></tr>
	    <tr><td>Timestamp</td><td><?php echo $timestamp; ?></td></tr>
	    <tr><td>Receiver</td><td><?php echo $receiver; ?></td></tr>
	    <tr><td>GroupId</td><td><?php echo $groupId; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('inbox') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>