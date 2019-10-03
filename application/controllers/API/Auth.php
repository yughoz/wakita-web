<?php
Class Auth extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Milis_member_model');
        $this->load->model('Milis_model');
        $this->load->model('ManageUser_model');
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
    
    function cheklogin(){
        $email      = $this->input->post('username');
        //$password   = $this->input->post('password');
        $password   = $this->input->post('password',TRUE);
        $hashPass   = password_hash($password,PASSWORD_DEFAULT);
        $test       = password_verify($password, $hashPass);
        // query chek users
        $this->db->where('email',$email);
        // $this->db->or_where('phone',$email);
        //$this->db->where('password',  $test);
        $users       = $this->db->get('tbl_user');
        if($users->num_rows()>0){
            $user = $users->row_array();
            if(password_verify($password,$user['password'])){
                // retrive user data to session
                // $this->db->where('user_id',$email);
                $dataHotline  = $this->Milis_member_model->get_all_where(['user_id' => $user['id_users']]);

                foreach ($dataHotline as $key => $value) {
                    $dataHotlineDetail[] = $this->Milis_model->get_by_id($value->milis_id);
                }

                $this->session->set_userdata($user);
                $data['response']   = 'success';
                $data['data']       =   $user ;
                // $data['data']['company_name'] = $this->config->item('wa_company_name');
                // $data['data']['email_company_name'] = $this->config->item('email_company_name'). $this->config->item('domain');
                // $data['data']['domain'] = $this->config->item('domain');
                $data['dataHotline']=   $dataHotlineDetail ;
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
