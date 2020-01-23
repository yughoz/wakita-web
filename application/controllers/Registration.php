<?php

// use ElephantIO\Engine\SocketIO\Version1X;
// use sendgrid;

Class Registration extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        // is_login();
        $this->load->library('form_validation');  
       
    }

    function index(){
        $this->load->view('auth/login');
    }

    function requestConfirm($verify){
        $this->load->model('API/ManageCustomer_model');
        $customer   = $this->ManageCustomer_model->get_verification($verify);
        print_r($customer);
        if($customer){
            // $this->ManageCustomer_model->update($id, $data);
        }else{
            echo "baba";
        }
    }

    function testing(){
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'response'  => 'false',
                'message'   =>'Something went wrong in _rules controller',
                'result'    => ''
            ]);
        } else {
            $email      = $this->input->post('email', TRUE);
            $fullname   = $this->input->post('fullname', TRUE);
            $phone      = $this->input->post('phone', TRUE);
            $password   = $this->input->post('password', TRUE);
            $repassword  = $this->input->post('repassword', TRUE);

            if(!empty($email) && !empty($fullname) && !empty($phone) && !empty($password) && !empty($repassword)){
                if($password == $repassword){

                    try{
                        $this->load->config('variable');
                        $this->load->model('API/ManageCustomer_model');
                        $this->load->library('wablas');
                        $this->load->library('wakitalib');

                        $subject    = "Confirm your account";
                        // $subject    = "Invoice Wakita";
                        $logo       = base_url()."assets/logo/logo.png";
                        $logoHor    = base_url()."assets/logo/banner.png";
                        $banner     = base_url()."assets/logo/banner.jpg";
                        $apps       = "<a href='https://play.google.com/store/apps/details?id=com.wacsnew'><img src='".base_url()."assets/logo/google-play.png' class='max-width'></a>";
                        
                        $header     = "Welcome to <b>wakita.id</b>";
                        // $header     = "Invoice Wakita 01 November 2019";
                        $footer     = "Thank You,<br>Best Regard<br><b>Wakita Team</b>";
                        $facebook   = "https://www.facebook.com";
                        $instagram  = "https://www.instagram.com";
                        $twitter    = "https://twitter.com";
                        
                        $message    = $this->config->item('template_reset_password');

                        $options    = array("cost"=>4);
                        $hashPass   = password_hash($password,PASSWORD_BCRYPT,$options);
                        $session    = "verification_".md5($email.date("Ymdhis"));
                        
                        $data = array(
                            'pid'           => $this->wakitalib->get_pid_id('tbl_customer',"TCUSTOMER",'id_users',1),
                            'full_name'     => $fullname,
                            'email'         => $email,
                            'phone'         => $phone,
                            'password'      => $hashPass,
                            'images'        => '',
                            'verification'  => $session,
                            'id_user_level' => 1,
                            'is_aktif'      => 0,
                            'is_trial'      => 0,
                            'created'       => date("Y-m-d H:i:s"),
                            'createdby'     => 'API_REGISTRATION',
                            'updated'       => date("Y-m-d H:i:s"),
                            'updatedBy'     => 'API_REGISTRATION',
                        );

                        // if ($this->ManageCustomer_model->check_insert($data)) {
                            if($this->ManageCustomer_model->insert($data) == true){
                                
                                $salutation = "Welcome <b>".$data['full_name']."</b>";
                                $content    = "Thanks for signing up. We're thrilled to have you on board.\n</br>
                                You need to confirm your account and you will be ready to go!</br></br>
                                <a target='_blank' href='".base_url()."registration/requestConfirm/".$data['verification']."'>Confirm Account</a>";

                                // $content    = "<table style='width:100%' border='0' cellspacing='1' cellpadding='10' bgcolor='#CCCCCC'>
                                // <tbody>
                                // <tr>
                                // <td bgcolor='#3399CC'><span style='font-weight:bold;font-size:14px;color:#ffffff'>INVOICE #0000001</span><br><span style='font-weight:bold;font-size:12px;color:#ffffff'>Jatuh Tempo: 31/10/2019</span></td>
                                // </tr>
                                // <tr>
                                // <td bgcolor='#FFFFFF'>
                                // <p>Wakita 10K Chat - rezafatahillah.com (31/09/2019 - 30/10/2019) Rp 300.000,00<br>
                                // Paket Chat : Paket Tidak termasuk Android & Iphone Apps<br>
                                // ------------------------------<wbr>------------------------<br>
                                // Sub Total: Rp 3.500.000,00<br>
                                // Credit Chat: Rp 10,000<br>
                                // Total: Rp. 3,500,000</p>
                                // <p><em><a href='http://clientzone.rumahweb.com/viewinvoice.php?id=2080068' target='_blank' data-saferedirecturl='https://www.google.com/url?q=http://clientzone.rumahweb.com/viewinvoice.php?id%3D2080068&amp;source=gmail&amp;ust=1571715845021000&amp;usg=AFQjCNF2_Zf_1jPeERyFgv751-hjAus8Gw'>Detail invoice »»</a><br>Untuk tagihan berlangganan, keterlambatan pembayaran dapat menyebabkan layanan Anda dihentikan/disuspend secara otomatis oleh sistem. Silahkan lakukan pembayaran sebelum jatuh tempo.</em></p>
                                // </td>
                                // </tr>
                                // <tr>
                                // <td bgcolor='#FFFFFF'>
                                // <p><span style='font-weight:bold;font-size:14px'>TOTAL : Rp 3.500.000,00</span></p>
                                // </td>
                                // </tr>
                                // <tr>
                                // <td bgcolor='#FFFFFF'>
                                // <p style='background-color:#ffff99;border:solid 1px #f6c55a;padding:3px 7px;font-size:10px'><strong><em>Catatan:</em></strong><em>&nbsp;</em><em>Untuk pembayaran yang sudah lewat tanggal jatuh tempo, mohon konfirmasikan terlebih dahulu ke Rumahweb agar dapat dicek apakah layanan masih dapat diaktifkan kembali atau tidak</em>.</p>
                                // <p>Metode pembayaran:BCA Transfer</p>
                                // <p>&nbsp;</p>
                                // </td>
                                // </tr>
                                // </tbody>
                                // </table>";
                                
                                $originalMail = ["{{LOGO}}","{{LOGOHOR}}","{{BANNER}}","{{HEADER}}","{{SALUTATION}}", "{{CONTENT}}","{{FOOTER}}","{{APPS}}","{{FACEBOOK}}","{{INSTAGRAM}}","{{TWITTER}}"];
                                $replaceMail  = [
                                $logo, 
                                $logoHor, 
                                $banner, 
                                $header,
                                $salutation,
                                $content,
                                $footer,
                                $apps,
                                $facebook,
                                $instagram,
                                $twitter
                                ];

                                $newContentSendgrid	= str_replace($originalMail, $replaceMail, $message);
                                $return = $this->wablas->_sendSendgrid($data['email'], $subject, $newContentSendgrid, "");
                                if($return == true){
                                    echo json_encode([
                                        'response'  => 'true',
                                        'message'   =>'Email Successfully Registered',
                                        'result'    => ''
                                    ]);
                                }else{
                                    echo json_encode([
                                        'response'  => 'true',
                                        'message'   =>'email successfully registered but Email not sent',
                                        'result'    => ''
                                    ]);
                                }
                            }
            
                        // } else {
                        //     echo json_encode([
                        //         'response'  => 'false',
                        //         'message'   =>'Email or Mobile registred',
                        //         'result'    => ''
                        //     ]);
                        // }
                    }catch(Exception $arg){
                        echo json_encode([
                            'response'  => 'false',
                            'message'   =>'Error in try catch : '.$arg,
                            'result'    => ''
                        ]);
                    }

                }else{
                    echo json_encode([
                        'response'  => 'false',
                        'message'   =>'Password not same',
                        'result'    => ''
                    ]);
                }
            }else{
                echo json_encode([
                    'response'  => 'false',
                    'message'   =>'All field must be set',
                    'result'    => ''
                ]);
            }
        }
        
        
    }
    public function _rules() 
    {
        $this->form_validation->set_rules('fullname', 'fullname', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
        $this->form_validation->set_error_delimiters('\n', '');
    }
}
