<?php
Class Contact extends CI_Controller{
    
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('API/Milis_member_model');
        $this->load->model('API/Milis_model');
        $this->load->model('API/Contact_model');
    }
    
    public function index(){
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
    
    public function update_name(){

        if (checking_akses('edit_contact')) {
	        $where = [
	            "group_hotline"      => $this->input->post('group_hotline',TRUE),
	            "phone"     => $this->input->post('customer_phone',TRUE),
	        ];
	        // $data = [
	        //     "name_replace" => $this->input->post('username',TRUE)
	        // ];


	        $data = [
	            "name_wa"       => $this->input->post('customer_phone',TRUE),
	            "name_replace"  => $this->input->post('username',TRUE),
	            "phone"         => $this->input->post('customer_phone',TRUE),
	            'group_hotline' => $this->input->post('group_hotline',TRUE),
	            'created'       => date("Y-m-d H:i:s"),
	            'createdby'     => $this->session->userdata('email'),
	            'updated'       => date("Y-m-d H:i:s"),
	            'updatedby'     => $this->session->userdata('email'),
	        ];

	        $customer_name  = $this->Contact_model->updateWhere($where,$data);
	        
	        echo json_encode([
	            "code" => "success",
	            "message" => "Update Record Success",
	        ]);die();

        } else {
	        echo json_encode([
	            "code" => "error",
	            "message" => "Access denied",
	        ]);die();
        }
    }

}
