<?php

// use ElephantIO\Engine\SocketIO\Version1X;
// use sendgrid;

Class Auth extends CI_Controller{

    function index(){
        $this->load->view('auth/login');
    }

    function forgot(){
        $data = array(
            'button'        => 'Submit',
            'action'        => site_url('Auth/checkForgot'),
            'title'         => 'Please Input Registered Email or Phone Number',
        );
        $this->load->view('auth/forgot', $data);
    }

    function chekForgot(){

        $email      = $this->input->post('email', TRUE);
        $phone      = $this->input->post('phone',TRUE);

        if(!empty($email) || !empty($phone))
        {
            $this->load->config('variable');
            $this->load->model('ManageUser_model');
            $this->load->library('wablas');

            $subject    = "Wakita Reset Password";
            $logo       = base_url()."assets/logo/logo.png";
            $logoHor    = base_url()."assets/logo/banner.png";
            $banner     = base_url()."assets/logo/banner.jpg";
            $apps       = "<a href='https://play.google.com/store/apps/details?id=com.wacsnew'><img src='".base_url()."assets/logo/google-play.png' class='max-width'></a>";
            
            $header     = "<b>Reset Password</b>";
            $footer     = "Thank You,<br>Best Regard<br><b>Wakita Team</b>";
            $facebook   = "https://www.facebook.com";
            $instagram  = "https://www.instagram.com";
            $twitter    = "https://twitter.com";
            
            $message    = $this->config->item('template_reset_password');
            
            // query chek users
            if(!empty($email)){
                $this->db->where('email',$email);
            }
            if(!empty($phone)){
                $this->db->where('phone',$phone);
            }
            
            //$this->db->where('password',  $test);
            $users       = $this->db->get('tbl_user');
            
            if($users->num_rows() > 0)
            {
                $user     = $users->row_array();
                $session  = md5($user['id_users'].date("Ymdh"));
                $data     = array(
                    'forgot'     => $session
                );

                $salutation = "Dear <b>".$user['full_name']."</b>";
                $content    = "We received a request to reset your password. if you did not make this request, please ignore this email.\n</br>
                Click link below to reset your password and access your account.</br></br>
                <a target='_blank' href='".base_url()."auth/requestReset/".$session."'>Reset Password</a>";
                
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

                $newContentSendgrid = str_replace($originalMail, $replaceMail, $message);

                try{
                    $this->ManageUser_model->update($user['id_users'], $data);
                    $return = $this->wablas->_sendSendgrid($user['email'], $subject, $newContentSendgrid, "");
                    if($return == true){
                        $this->session->set_flashdata('status_login','Request Reset Success, Please Check Your Email Address.');
                        redirect('auth/forgot');
                    }else{
                        $this->session->set_flashdata('status_login','Request Change Password Failed.');
                        redirect('auth/forgot');
                    }
                }catch(Exception $e){
                    $this->session->set_flashdata('status_login','Request Change Password Failed.');
                    redirect('auth/forgot');
                }
            }else{
                $this->session->set_flashdata('status_login','Email Address Or Mobile Phone Not Registred2.');
                redirect('auth/forgot');
            }
        }else{
            $this->session->set_flashdata('status_login','Email Address Or Mobile Phone Not Registred.');
            redirect('auth/forgot');
        }
    }

    function reset(){
            $password       = $this->input->post('newpassword',TRUE);
            $repassword     = $this->input->post('renewpassword',TRUE);
            $forgot         = $this->input->post('forgot',TRUE);
            if(!empty($password) && !empty($repassword)){

                if($password == $repassword){
                    $id             = $this->input->post('id', TRUE);
                    $options        = array("cost"=>4);
                    $hashPassword   = password_hash($password,PASSWORD_BCRYPT,$options);

                    $data = [
                        'password'      => $hashPassword,
                        'forgot'        => ''
                    ];
                    $this->load->model('ManageUser_model');
                    $this->ManageUser_model->update($id, $data);
                    $this->session->set_flashdata('message', 'Update Record Success');
                    redirect(site_url('auth'));
                }else{
                    $this->session->set_flashdata('message', 'Password Not Same');
                    redirect(site_url('auth/requestReset/'.$forgot));
                }
                
            }else{
                $this->session->set_flashdata('message', 'Password And RePassword Must Be Set');
                redirect(site_url('auth/requestReset/'.$forgot));
            }
    }

    function requestReset($id){
        $this->load->model('ManageUser_model');
        $users=$this->ManageUser_model->get_reset($id);
        // print_r($users);
        if($users){
            foreach ($users as $row)
            {
                $data = array(
                    'button'        => 'Update',
                    'id'            => $row->id_users,
                    'forgot'        => $row->forgot,
                    'action'        => site_url('Auth/reset'),
                    'title'         => 'Hi <b>'.$row->full_name.'</b></br>Please choose a new password to finish signing in.',
                );
                $this->load->view('auth/reset', $data);
            }
        }else{
            redirect(site_url('auth'));
        }
    }
    
    function cheklogin(){
        $this->load->model('ManageUser_model');
        $this->load->model('ManageUserLevel_model');
        $this->load->model('ManageHotlineMember_model');
        $this->load->model('ManageHotline_model');
        $this->load->model('User_model');

        $email      = $this->input->post('email');
        //$password   = $this->input->post('password');
        $password = $this->input->post('password',TRUE);
        $hashPass = password_hash($password,PASSWORD_DEFAULT);
        $test     = password_verify($password, $hashPass);
        // query chek users
        // $this->db->where('email',$email);
        //$this->db->where('password',  $test);
        // $users       = $this->db->get('tbl_user');


        
        $users = $this->User_model->get_by_where(["email" =>$email]);
        $whereServer['ms_users.email'] = $email;
        // $dataUrlServer  = $this->ManageUser_model->get_by_where_server($whereServer);
        if($users){
            $user = json_decode(json_encode($users),true);
        // echo print_r($user);die();
            // if(password_verify($password,$user['password'])){
                // retrive user data to session
                // $this->db->where('user_id',$email);
                $datasLevel = $this->ManageUserLevel_model->get_by_id($user['id_user_level']);
                // echo print_r($datasLevel);die();
                $dataHotline  = $this->ManageHotlineMember_model->get_all_where(['user_id' => $user['pid']]);
                // echo print_r($dataHotline);die();
                $pidHotlineArr = [];
                foreach ($dataHotline as $key => $value) {
                    // array_push($pidHotlineArr,$value->pid);
                    $dataHotlineDetail = $this->ManageHotline_model->get_by_where(['phone_number' =>$value->group_number]);
                    $pidHotlineArr[] = $dataHotlineDetail->pid;
                    // $dataHotlineDetail[] = $dataHotlineDetail;
                }


                $user['user_level'] = $datasLevel->nama_level;
                $user['company_pid'] = $users->company_id;
                $user['id_users'] = $users->pid;
                $user['pidHotlineArr'] = $pidHotlineArr;
                $data['data_level'] =   $this->ManageUserLevel_model->get_akses($user['id_user_level']) ;
                // echo print_r($user);die();

                $data['dataHotline']=   $dataHotlineDetail ;
                // retrive user data to session
                $this->session->set_userdata($user);

                redirect('welcome');
            // }else{
            //     $this->session->set_flashdata('status_login','password yang anda input salah');
            //     redirect('auth');
            // }
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
