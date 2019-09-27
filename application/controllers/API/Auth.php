<?php
Class Auth extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Milis_member_model');
        $this->load->model('Milis_model');
        $this->config->load('companyProfile');
    }
    
    function index(){
        $data = [];
        $this->db->where('email',$this->session->userdata('email'));
        //$this->db->where('password',  $test);
        $users       = $this->db->get('tbl_user');
        if($users->num_rows()>0){
            $data = $users->row_array();
        }
        echo json_encode([
            "status" => "mantap",
            "data" => $data
        ]);
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
                $data['data']['company_name'] = $this->config->item('wa_company_name');
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
    
    function logout(){
        $this->session->sess_destroy();
        $this->session->set_flashdata('status_login','Anda sudah berhasil keluar dari aplikasi');
        // redirect('auth');
    }
}
