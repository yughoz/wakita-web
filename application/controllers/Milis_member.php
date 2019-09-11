<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Milis_member extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('User_model');
        $this->load->model('Milis_member_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
    }

    public function index()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('milis_member/create_action'),
	    'id' => set_value(''),
	    'milis_id' => set_value(''),
	    'user_id' => set_value(''),
	    'created' => set_value(''),
	    'createdby' => set_value(''),
	    'updated' => set_value(''),
	    'updatedby' => set_value(''),
	);
        $this->template->load('template','milis_member/milis_member_list', $data);
    } 
    public function member($id)
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('milis_member/create_action'),
        'id' => set_value(''),
        'milis_id' => set_value(''),
        'user_id' => set_value(''),
        'created' => set_value(''),
        'createdby' => set_value(''),
        'updated' => set_value(''),
        'updatedby' => set_value(''),
        'member' => $id,
    );
        $this->template->load('template','milis_member/milis_member_list', $data);
    } 
    
    public function json($milis_id = "") {
        header('Content-Type: application/json');
        echo $this->Milis_member_model->json($milis_id);
    }


    public function user($milis_id) {
        header('Content-Type: application/json');
        $dataMilis = $this->getUserMilis($milis_id);
        // echo print_r($dataMilis);
        // die;
        echo $this->User_model->jsonMember($dataMilis);
    }

    function getUserMilis($milis_id){
        $dataMilis = $this->Milis_member_model->get_all(["milis_id" => $milis_id]);
        $dataTemp = [];
        foreach ($dataMilis as $key => $value) {
            $dataTemp[] = $value->user_id;
        }

        return $dataTemp;
    }


    public function read($id) 
    {
        $row = $this->Milis_member_model->get_by_id($id);
        if ($row) {
            echo json_encode([
                "code" => "success",
                "message" => "Record Found",
                "data" => $row,
            ]);die();
        } else {
            echo json_encode([
                "code" => "error",
                "message" => "Record Not Found",
            ]);die();
        }
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "code" => "error",
                "message" => validation_errors(),
                "form_error" => $this->form_validation->error_array(),
            ]);die();
        } else {
            $data = array(
    		'milis_id' => $this->input->post('milis_id',TRUE),
    		'user_id' => $this->input->post('user_id',TRUE),
    		'created' => date("Y-m-d H:i:s"),
    		'createdby' => $this->session->userdata('email'),
    		'updated' => date("Y-m-d H:i:s"),
    		'updatedby' => $this->session->userdata('email'),
	    );

            $this->Milis_member_model->insert($data);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
            ]);die();
        }
    }
    public function add_member($member_id,$user_id) 
    {
        $this->_rules();

        // if ($this->form_validation->run() == FALSE) {
        //     echo json_encode([
        //         "code" => "error",
        //         "message" => validation_errors(),
        //         "form_error" => $this->form_validation->error_array(),
        //     ]);die();
        // } else {
            $data = array(
                'milis_id'  => $member_id,
                'user_id'   => $user_id,
                'created'   => date("Y-m-d H:i:s"),
                'createdby' => $this->session->userdata('email'),
                'updated'   => date("Y-m-d H:i:s"),
                'updatedby' => $this->session->userdata('email'),
            );

            $this->Milis_member_model->insert($data);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
            ]);die();
        // }
    }
    
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {

            echo json_encode([
                "code" => "error",
                "message" => validation_errors(),
                "form_error" => $this->form_validation->error_array(),
            ]);die();
        } else {
            $data = array(
		'milis_id' => $this->input->post('milis_id',TRUE),
		'user_id' => $this->input->post('user_id',TRUE),
		'created' => $this->input->post('created',TRUE),
		'createdby' => $this->input->post('createdby',TRUE),
		'updated' => date("Y-m-d H:i:s"),
		'updatedby' => $this->session->userdata('email'),
	    );

            $this->Milis_member_model->update($this->input->post('id', TRUE), $data);
            echo json_encode([
                "code" => "success",
                "message" => "Update Record Success",
            ]);die();
        }
    }
    
    public function delete_action($id) 
    {
        $row = $this->Milis_member_model->get_by_id($id);

        if ($row) {
            $this->Milis_member_model->delete($id);
            echo json_encode([
                "code" => "success",
                "message" => "Delete Record Success",
            ]);die();
        } else {
            echo json_encode([
                "code" => "error",
                "message" => "Record Not Found",
            ]);die();
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('milis_id', 'milis id', 'trim|required');
	$this->form_validation->set_rules('user_id', 'contact admin', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Milis_member.php */
/* Location: ./application/controllers/Milis_member.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-14 12:19:40 */
/* http://harviacode.com */