<?php
Class Auth extends CI_Controller{

    function index(){
        $this->load->view('auth/login');
    }

    function forgot(){
        $this->load->view('auth/forgot');
    }

    function chekForgot(){
        $this->load->model('ManageUser_model');
        

        $subject    = "reza";
        $message    = "<!DOCTYPE html><html data-editor-version='2' class='sg-campaigns' xmlns='http://www.w3.org/1999/xhtml'><head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1'><!--[if !mso]><!-->
        <meta http-equiv='X-UA-Compatible' content='IE=Edge'><!--<![endif]-->
        <!--[if (gte mso 9)|(IE)]>
        <xml>
        <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
        <!--[if (gte mso 9)|(IE)]>
        <style type='text/css'>
          body {width: 600px;margin: 0 auto;}
          table {border-collapse: collapse;}
          table, td {mso-table-lspace: 0pt;mso-table-rspace: 0pt;}
          img {-ms-interpolation-mode: bicubic;}
        </style>
        <![endif]-->
    
        <style type='text/css'>
          body, p, div {
            font-family: verdana,geneva,sans-serif;
            font-size: 16px;
          }
          body {
            color: #516775;
          }
          body a {
            color: #993300;
            text-decoration: none;
          }
          p { margin: 0; padding: 0; }
          table.wrapper {
            width:100% !important;
            table-layout: fixed;
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: 100%;
            -moz-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
          }
          img.max-width {
            max-width: 100% !important;
          }
          .column.of-2 {
            width: 50%;
          }
          .column.of-3 {
            width: 33.333%;
          }
          .column.of-4 {
            width: 25%;
          }
          @media screen and (max-width:480px) {
            .preheader .rightColumnContent,
            .footer .rightColumnContent {
                text-align: left !important;
            }
            .preheader .rightColumnContent div,
            .preheader .rightColumnContent span,
            .footer .rightColumnContent div,
            .footer .rightColumnContent span {
              text-align: left !important;
            }
            .preheader .rightColumnContent,
            .preheader .leftColumnContent {
              font-size: 80% !important;
              padding: 5px 0;
            }
            table.wrapper-mobile {
              width: 100% !important;
              table-layout: fixed;
            }
            img.max-width {
              height: auto !important;
              max-width: 480px !important;
            }
            a.bulletproof-button {
              display: block !important;
              width: auto !important;
              font-size: 80%;
              padding-left: 0 !important;
              padding-right: 0 !important;
            }
            .columns {
              width: 100% !important;
            }
            .column {
              display: block !important;
              width: 100% !important;
              padding-left: 0 !important;
              padding-right: 0 !important;
              margin-left: 0 !important;
              margin-right: 0 !important;
            }
          }
        </style>
        <!--user entered Head Start-->
    
         <!--End Head user entered-->
      </head>
      <body>
        <center class='wrapper' data-link-color='#993300' data-body-style='font-size: 16px; font-family: verdana,geneva,sans-serif; color: #516775; background-color: #F9F5F2;'>
          <div class='webkit'>
            <table cellpadding='0' cellspacing='0' border='0' width='100%' class='wrapper' bgcolor='#F9F5F2'>
              <tbody><tr>
                <td valign='top' bgcolor='#EFEFEF' width='100%'>
                  <table width='100%' role='content-container' class='outer' align='center' cellpadding='0' cellspacing='0' border='0'>
                    <tbody><tr>
                      <td width='100%'>
                        <table width='100%' cellpadding='0' cellspacing='0' border='0'>
                          <tbody><tr>
                            <td>
                              <!--[if mso]>
                              <center>
                              <table><tr><td width='600'>
                              <![endif]-->
                              <table width='100%' cellpadding='0' cellspacing='0' border='0' style='width: 100%; max-width:600px;' align='center'>
                                <tbody><tr>
                                  <td role='modules-container' style='padding: 0px 0px 0px 0px; color: #516775; text-align: left;' bgcolor='#F9F5F2' width='100%' align='left'>
    
        <table class='module preheader preheader-hide' role='module' data-type='preheader' border='0' cellpadding='0' cellspacing='0' width='100%' style='display: none !important; mso-hide: all; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;'>
          <tbody><tr>
            <td role='module-content'>
              <p>HPE Discover More</p>
            </td>
          </tr>
        </tbody></table>
    
        <table class='wrapper' role='module' data-type='image' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed; background-color:#425563'>
          <tbody><tr>
            <td style='font-size:6px;line-height:10px;padding:30px 0px 30px 30px;' valign='top' align='left'>
              <img class='max-width' border='0' style='display:block;color:#000000;text-decoration:none;font-family:Helvetica, arial, sans-serif;font-size:16px;max-width:50% !important;width:20%;height:auto !important;' src='http://hpediscovermore-id.com/assets/logo_wht.png' alt='Ingrid & Anders' width='300' data-responsive='true'>
            </td>
          </tr>
        </tbody></table>
    
        
    
        <table class='wrapper' role='module' data-type='image' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
          <tbody><tr>
            <td style='font-size:6px;line-height:10px;padding:0px 0px 0px 0px;' valign='top' align='center'>
              <img class='max-width' border='0' style='display:block;color:#000000;text-decoration:none;font-family:Helvetica, arial, sans-serif;font-size:16px;max-width:100% !important;width:100%;height:auto !important;' src='http://hpediscovermore-id.com/upload/37.jpg' alt='' width='600' data-responsive='true'>
            </td>
          </tr>
        </tbody></table>
    
        <table class='module' role='module' data-type='text' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
          <tbody><tr>
            <td style='background-color:#ffffff;padding:50px 0px 10px 0px;line-height:30px;text-align:inherit;' height='100%' valign='top' bgcolor='#ffffff'>
                <div style='text-align: center;'><span style='font-size:28px;'><span style='color:#516775;'><span style='font-family:georgia,serif;'><strong>HPE Discover More</strong></span></span></span></div>
            </td>
          </tr>
        </tbody></table>
        
        <table class='module' role='module' data-type='text' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
          <tbody><tr>
            <td style='background-color:#ffffff;padding:10px 40px 20px 40px;line-height:22px;text-align:inherit;' height='100%' valign='top' bgcolor='#ffffff'>
                <div style='text-align: left;'>
                    <span style='font-family:verdana,geneva,sans-serif; font-size:16px; color#ffffff;'>
                        <b>Dear {{SALUTATION}} {{FIRSTNAME}} {{LASTNAME}},</b>
                    </span>
                </div>
    
            </td>
          </tr>
        </tbody></table>
        
        <table class='module' role='module' data-type='text' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
          <tbody><tr>
            <td style='background-color:#ffffff;padding:10px 40px 20px 40px;line-height:22px;text-align:inherit;' height='100%' valign='top' bgcolor='#ffffff'>
                <div style='text-align: left;'><span style='font-family:verdana,geneva,sans-serif;'>Thank you for registering to <b>HPE Discover More</b> event.<br>Your name has been added to our list and kindly wait in receiving a confirmation email shortly.
                </span>
                </div>
    
            </td>
          </tr>
        </tbody></table>
    
        <table class='module' role='module' data-type='text' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;'>
          <tbody><tr>
            <td style='background-color:#ffffff;padding:10px 40px 20px 40px;line-height:22px;text-align:inherit;' height='100%' valign='top' bgcolor='#ffffff'>
                <div style='text-align: left;'>
                    <span style='font-family:verdana,geneva,sans-serif;'>
                        With Kind Regards,<br>Your Hewlett Packard Enterprise Team
                    </span>
                </div>
    
            </td>
          </tr>
        </tbody></table>
        
    
        <table class='module' role='module' data-type='spacer' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;background-color:#EFEFEF;'>
          <tbody><tr>
            <td style='padding:0px 0px 30px 0px;' role='module-content' bgcolor=''>
            </td>
          </tr>
        </tbody></table>
    
      
    
    
        
    
        <table class='module' role='module' data-type='social' align='center' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed; background-color:#EFEFEF;'>
          <tbody>
            <tr>
              <td valign='top' style='padding:0px 0px 0px 0px;font-size:6px;line-height:10px;background-color:#EFEFEF;'>
                <table align='center'>
                  <tbody>
                    <tr>
                      <td style='padding: 0px 5px;'>
            <a role='social-icon-link' href='https://www.facebook.com/HewlettPackardEnterprise/' target='_blank' alt='Facebook' data-nolink='false' title='Facebook ' style='-webkit-border-radius:30px;-moz-border-radius:30px;border-radius:30px;display:inline-block;background-color:#516775;'>
              <img role='social-icon' alt='Facebook' title='Facebook ' height='30' width='30' style='height: 30px, width: 30px' src='https://marketing-image-production.s3.amazonaws.com/social/white/facebook.png'>
            </a>
          </td>
                      <td style='padding: 0px 5px;'>
            <a role='social-icon-link' href='https://twitter.com/hpe' target='_blank' alt='Twitter' data-nolink='false' title='Twitter ' style='-webkit-border-radius:30px;-moz-border-radius:30px;border-radius:30px;display:inline-block;background-color:#516775;'>
              <img role='social-icon' alt='Twitter' title='Twitter ' height='30' width='30' style='height: 30px, width: 30px' src='https://marketing-image-production.s3.amazonaws.com/social/white/twitter.png'>
            </a>
          </td>
                      <td style='padding: 0px 5px;'>
            <a role='social-icon-link' href='https://www.instagram.com/hpe/?hl=id' target='_blank' alt='Instagram' data-nolink='false' title='Instagram ' style='-webkit-border-radius:30px;-moz-border-radius:30px;border-radius:30px;display:inline-block;background-color:#516775;'>
              <img role='social-icon' alt='Instagram' title='Instagram ' height='30' width='30' style='height: 30px, width: 30px' src='https://marketing-image-production.s3.amazonaws.com/social/white/instagram.png'>
            </a>
          </td>
    
        
    
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
        
        <table class='module' role='module' data-type='social' align='center' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed; background-color:#EFEFEF;'>
          <tbody>
            <tr>
              <td valign='top' style='padding:20px 0px 0px 0px;font-size:6px;line-height:10px;background-color:#EFEFEF;'>
                <table align='center'>
                  <tbody>
                    <tr>
                      <td style='padding: 0px 5px;'>
            <a href='https://www.linkedin.com/in/fatahillahreza' target='_blank'><span style='font-family:verdana,geneva,sans-serif;font-size:10px'>Supported By</span> <img style='width:60px;' src='http://app.hpediscovermore-id.com/assets/rezaapp4.png'></a>
          </td>
                      
    
        
    
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      
        <table class='module' role='module' data-type='spacer' border='0' cellpadding='0' cellspacing='0' width='100%' style='table-layout: fixed;background-color:#EFEFEF;'>
          <tbody><tr>
            <td style='padding:0px 0px 30px 0px;' role='module-content' bgcolor=''>
            </td>
          </tr>
        </tbody></table>
    
                                  </td>
                                </tr>
                              </tbody></table>
                              <!--[if mso]>
                              </td></tr></table>
                              </center>
                              <![endif]-->
                            </td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </div>
        </center>
      
    
    </body></html>";

        $email      = $this->input->post('email', TRUE);
        //$password   = $this->input->post('password');
        $phone = $this->input->post('phone',TRUE);
        // query chek users
        if(!empty($email)){
            $this->db->where('email',$email);
        }
        if(!empty($phone)){
            $this->db->where('phone',$phone);
        }
        
        //$this->db->where('password',  $test);
        $users       = $this->db->get('tbl_user');
        // print_r($users);
        if($users->num_rows()>0){
            $user = $users->row_array();
            $data = array(
                'forgot'     => md5($user['id_users'].date("Ymd"))
            );
            // echo $data['forgot'];
            

            
            $this->ManageUser_model->update($user['id_users'], $data);
            $this->load->library('wablas');
            $a= $this->wablas->_sendSendgrid('fatahillah.reza@gmail.com', 'sdfsdf', $message, "");
            echo $a;
           
        }else{
            echo "else";
            // $this->session->set_flashdata('status_login','email atau password yang anda input salah');
            // redirect('auth');
        }
    }

    function reset(){
        $this->load->view('auth/login');
    }
    
    function cheklogin(){
        $email      = $this->input->post('email');
        //$password   = $this->input->post('password');
        $password = $this->input->post('password',TRUE);
        $hashPass = password_hash($password,PASSWORD_DEFAULT);
        $test     = password_verify($password, $hashPass);
        // query chek users
        $this->db->where('email',$email);
        //$this->db->where('password',  $test);
        $users       = $this->db->get('tbl_user');
        if($users->num_rows()>0){
            $user = $users->row_array();
            if(password_verify($password,$user['password'])){
                // retrive user data to session
                $this->session->set_userdata($user);
                redirect('welcome');
            }else{
                redirect('auth');
            }
        }else{
            $this->session->set_flashdata('status_login','email atau password yang anda input salah');
            redirect('auth');
        }
    }
    
    function logout(){
        $this->session->sess_destroy();
        $this->session->set_flashdata('status_login','Anda sudah berhasil keluar dari aplikasi');
        redirect('auth');
    }
}
