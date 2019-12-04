<div class="content-wrapper">

    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Profile User</h3>
            </div>

            <?php
            $status_login = $this->session->userdata('message');
            if (empty($status_login)) {
                $message = "";
            } else {
                $message = $status_login;
            }
            ?>
            <p class="login-box-msg"><?php echo $message; ?></p>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                <table class='table table-bordered'>        
                        <tr><td width='200'>Full Name <?php echo form_error('full_name') ?></td><td><input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" value="<?php echo $full_name; ?>" /></td></tr>
                        <tr><td width='200'>Email <?php echo form_error('email') ?></td>
                                <td>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" />
                                </td>
                        </tr>
                        <tr>
                                <td width='200'>Phone <?php echo form_error('phone') ?></td>
                                <td><input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="<?php echo $phone; ?>" /></td>
                        </tr>


                        <tr>
                                <td width='200'>Old Password <?php echo form_error('old_password') ?></td><td><input type="password" class="form-control" name="old_password" id="password" placeholder="Old Password"  /></td>
                        </tr>

                        <tr>
                                <td width='200'>New Password <?php echo form_error('new_password') ?></td><td><input type="password" class="form-control" name="new_password" id="password" placeholder="New Password"  /></td>
                        </tr>


                        <tr>
                                <td width='200'>Confrim Password <?php echo form_error('confirm_password') ?></td><td><input type="password" class="form-control" name="confirm_password" id="password" placeholder="Confirm Password"  /></td>
                        </tr>


                    

                    <tr><td width='200'>Foto Profile <?php echo form_error('images') ?></td><td> <input type="file" name="images"></td></tr>
                    <tr><td></td><td><input type="hidden" name="id_users" value="<?php echo $id_users; ?>" /> 
                            <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
                            <a href="<?php echo site_url('ManageUser/profile') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
                </table>
           </form>        
        </div>
        </section>
</div>