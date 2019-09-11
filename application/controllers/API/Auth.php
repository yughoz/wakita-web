<?php
Class Auth extends CI_Controller{
    
    
    
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
        $this->db->or_where('phone',$email);
        //$this->db->where('password',  $test);
        $users       = $this->db->get('tbl_user');
        if($users->num_rows()>0){
            $user = $users->row_array();
            if(password_verify($password,$user['password'])){
                // retrive user data to session
                $this->session->set_userdata($user);
                $data['response']   = 'success';
                $data['data']       =   $user ;
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
    
    function logout(){
        $this->session->sess_destroy();
        $this->session->set_flashdata('status_login','Anda sudah berhasil keluar dari aplikasi');
        // redirect('auth');
    }
}
