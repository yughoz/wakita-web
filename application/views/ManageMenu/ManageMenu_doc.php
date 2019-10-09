<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            .word-table {
                border:1px solid black !important; 
                border-collapse: collapse !important;
                width: 100%;
            }
            .word-table tr th, .word-table tr td{
                border:1px solid black !important; 
                padding: 5px 10px;
            }
        </style>
    </head>
    <body>
        <h2>Tbl_menu List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Url</th>
                <th>Icon</th>
                <th>Is Main Menu</th>
                <th>Is Aktif</th>
            </tr>
            <?php
            foreach ($managemenu_data as $managemenu)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $managemenu->title ?></td>
		      <td><?php echo $managemenu->url ?></td>
		      <td><?php echo $managemenu->icon ?></td>
		      <td><?php echo $managemenu->is_main_menu ?></td>
		      <td><?php echo $managemenu->is_aktif ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>