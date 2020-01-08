<?php
Class Auth extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('ManageHotlineMember_model');
        $this->load->model('Milis_model');
        $this->load->model('ManageHotline_model');
        $this->load->model('ManageUser_model');
        $this->load->model('ManageUserLevel_model');
        $this->load->model('Hotline_model');
        $this->load->model('User_model');
        $this->config->load('companyProfile');
        $this->config->load('mobile');
        $this->load->library('form_validation');        
    }
    
    function index(){
        $data = [];
        $this->db->where('email',$this->session->userdata('email'));
        //$this->db->where('password',  $test);
        $users       = $this->db->get('tbl_user');
        $version     = [
                        "version" =>$this->config->item('version'),
                        "min_version" =>$this->config->item('min_version'),

                        ];
        if($users->num_rows()>0){
            $data = $users->row_array();
            // echo $data['password'];die;
            if($data['password'] == $this->session->userdata('password')){
                unset($data['password']);
                echo json_encode([
                    "status" => "success",
                    "data" => $data,
                    "data_version" => $version,
                ]);
            } else {
              echo json_encode([
                "status" => "failled",
                "message"   => "password expired",
                "data" => [],
                "data_version" => $version,
            ]);  
            }
        } else {
            echo json_encode([
                "status" => "failled",
                "data" => [],
                "data_version" => $version,
            ]);
        }
        // $this->load->view('auth/login');

    }
    
    function qrCode($token){
        $this->config->load('apiwha');
        // echo $this->config->item('webServer')."generate/index.php?token=qtDcp1mKZz8gDXVlTu4FULKg7yTnRtm2GP2ccatCPy4D9v53t2V7WOyp4qufgefJ&url=aHR0cDovL3BhcmFtaXRoYS53YWJsYXMuY29t";die(); 
        // header("Content-type: application");
        // // header("Content-Disposition: inline; filename=filename.pdf");
        // @readfile($this->config->item('webServer')."generate/index.php?token=qtDcp1mKZz8gDXVlTu4FULKg7yTnRtm2GP2ccatCPy4D9v53t2V7WOyp4qufgefJ&url=aHR0cDovL3BhcmFtaXRoYS53YWJsYXMuY29t");
        // echo $token;die();
        echo '<body style="margin:0px;padding:0px;overflow:hidden">
                <iframe src="'.$this->config->item('webServer')."generate/index.php?token=".$token."&url=aHR0cDovL3BhcmFtaXRoYS53YWJsYXMuY29t".'" frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"></iframe>
            </body>';
    }
    function cheklogin(){
        $email      = $this->input->post('username');
        //$password   = $this->input->post('password');
        $password   = $this->input->post('password',TRUE);
        $hashPass   = password_hash($password,PASSWORD_DEFAULT);
        $test       = password_verify($password, $hashPass);
        $dataHotlineDetail = [];
        // query chek users
        // $this->db->where('email',$email);
        // id_user_level
        // $this->db->or_where('phone',$email);
        //$this->db->where('password',  $test);
        // $users       = $this->db->get('tbl_user');
        // $whereServer['ms_users.email'] = $email;



        
        $users = $this->User_model->get_by_where(["email" =>$email]);
        $whereServer['ms_users.email'] = $email;

        $dataUrlServer  = $this->ManageUser_model->get_by_where_server($whereServer);
     
        if($users){
            $user = json_decode(json_encode($users),true);
            if(password_verify($password,$user['password'])){
                // retrive user data to session
                // $this->db->where('user_id',$email);
                $datasLevel = $this->ManageUserLevel_model->get_by_id($user['id_user_level']);
                // echo print_r($datasLevel);die();
                $dataHotline  = $this->ManageHotlineMember_model->get_all_where(['user_id' => $user['pid']]);
                // echo print_r($dataHotline);die();
                foreach ($dataHotline as $key => $value) {
                    $dataHotlineDetail[] = $this->ManageHotline_model->get_by_where(['phone_number' =>$value->group_number]);
                }


                $user['user_level'] = $datasLevel->nama_level;
                $user['company_pid'] = $dataUrlServer->company_id;

                $this->session->set_userdata($user);
                $data['response']   =   'success';
                $data['data']       =   $user ;
                // $data['dataUrlServer']       =   $dataUrlServer ;
                $data['data_level'] =   $this->ManageUserLevel_model->get_akses($user['id_user_level']) ;
                // $data['data']['company_name'] = $this->config->item('wa_company_name');
                // $data['data']['email_company_name'] = $this->config->item('email_company_name'). $this->config->item('domain');
                // $data['data']['domain'] = $this->config->item('domain');
                $data['dataHotline']=   $dataHotlineDetail ;
                // $this->Hotline_model->vw_group_milis($this->session->userdata('company_pid'));
                echo json_encode($data);
                // redirect('welcome');
            }else{
                $response = array(
                    'response' => 'error',
                    'message'=>'incorrect username or password'
                 );
                echo json_encode($response);
                // redirect('auth');
            }
        }else{
            $response = array(
                'response' => 'error',
                'message'=>'incorrect username or password'
             );
            echo json_encode($response);
        }
    }



    function getCompanyId (){
        echo $this->session->userdata('company_pid');
    }
    function getToken ($get){
        echo print_r($this->Milis_model->get_token($get));
    }

    function editProfile(){
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "code" => "error",
                "message" => validation_errors(),
                "form_error" => $this->form_validation->error_array(),
            ]);die();
        } else {
            $data = array(
                'full_name'     => $this->input->post('full_name',TRUE),
                // 'email'         => $this->input->post('email',TRUE),
                'phone'         => $this->input->post('phone',TRUE),        
                // 'updated'   => date("Y-m-d H:i:s"),
                // 'updatedby' =>  $this->session->userdata('email'),
            );

            $this->ManageUser_model->update($this->session->userdata('id_users'), $data);
            $this->db->where('email',$this->session->userdata('email'));
            // $this->db->or_where('phone',$email);
            //$this->db->where('password',  $test);
            $users       = $this->db->get('tbl_user');
            if($users->num_rows()>0){
                $user = $users->row_array();
                $this->session->set_userdata($user);
                echo json_encode([
                    "code" => "success",
                    "data"  => $user,
                    "userID" =>$this->session->userdata('id_users'),
                    "message" => "Update Record Success",
                ]);die();

            } else{
                echo json_encode([
                "code" => "error",
                "message" => "failled Update data",
            ]);
            }
        }
    }
    function changePassword(){
        $this->form_validation->set_rules('password', 'Old password', 'trim|required');
        $this->form_validation->set_rules('newPassword', 'New password', 'trim|required');

        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "code" => "error",
                "message" => validation_errors(),
                "form_error" => $this->form_validation->error_array(),
            ]);die();
        } else {
            if(password_verify($this->input->post('password',TRUE),$this->session->userdata('password'))){
                $password       = $this->input->post('newPassword',TRUE);
                $options        = array("cost"=>4);
                $hashPassword   = password_hash($password,PASSWORD_BCRYPT,$options);
                $data = array(
                    'password'     => $hashPassword,
                );
                $this->ManageUser_model->update($this->session->userdata('id_users'), $data);
                echo json_encode([
                    "code" => "success",
                    "message" => "Update password Success",
                ]);die();
            } else {
                $response = array(
                    'response' => 'error',
                    'message'=>'incorrect old password'
                );
                echo json_encode($response);
            }

            // $this->ManageUser_model->update($this->session->userdata('id_users'), $data);
            
        }
    }

    public function _rules() 
    {
        $this->form_validation->set_rules('full_name', 'full name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
        $this->form_validation->set_error_delimiters('\n', '');
    }

    
    function logout(){
        $this->session->sess_destroy();
        $this->session->set_flashdata('status_login','Anda sudah berhasil keluar dari aplikasi');
        // redirect('auth');
    }
}
