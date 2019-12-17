<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inbox extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Inbox_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
        $this->company_pid  = $this->session->userdata('company_pid');
    }

    public function index()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('inbox/create_action'),
	    'id' => set_value(''),
	    'message_id' => set_value(''),
	    'fromMe' => set_value(''),
	    'pushName' => set_value(''),
	    'phone' => set_value(''),
	    'message' => set_value(''),
	    'timestamp' => set_value(''),
	    'receiver' => set_value(''),
	    'groupId' => set_value(''),
	);
        $this->template->load('template','inbox/inbox_list', $data);
    } 
    
    public function json() {
        header('Content-Type: application/json');

        $tableName = $this->Inbox_model->get_table();
        $this->Inbox_model->set_table($tableName."_".$this->company_pid);
        echo $this->Inbox_model->json();
    }

    public function read($id) 
    {
        $row = $this->Inbox_model->get_by_id($id);
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
		'message_id' => $this->input->post('message_id',TRUE),
		'fromMe' => $this->input->post('fromMe',TRUE),
		'pushName' => $this->input->post('pushName',TRUE),
		'phone' => $this->input->post('phone',TRUE),
		'message' => $this->input->post('message',TRUE),
		'timestamp' => $this->input->post('timestamp',TRUE),
		'receiver' => $this->input->post('receiver',TRUE),
		'groupId' => $this->input->post('groupId',TRUE),
	    );

            $this->Inbox_model->insert($data);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
            ]);die();
        }
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
		'message_id' => $this->input->post('message_id',TRUE),
		'fromMe' => $this->input->post('fromMe',TRUE),
		'pushName' => $this->input->post('pushName',TRUE),
		'phone' => $this->input->post('phone',TRUE),
		'message' => $this->input->post('message',TRUE),
		'timestamp' => $this->input->post('timestamp',TRUE),
		'receiver' => $this->input->post('receiver',TRUE),
		'groupId' => $this->input->post('groupId',TRUE),
	    );

            $this->Inbox_model->update($this->input->post('id', TRUE), $data);
            echo json_encode([
                "code" => "success",
                "message" => "Update Record Success",
            ]);die();
        }
    }
    
    public function delete_action($id) 
    {
        $row = $this->Inbox_model->get_by_id($id);

        if ($row) {
            $this->Inbox_model->delete($id);
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
	$this->form_validation->set_rules('message_id', 'message id', 'trim|required');
	$this->form_validation->set_rules('fromMe', 'fromme', 'trim|required');
	$this->form_validation->set_rules('pushName', 'pushname', 'trim|required');
	$this->form_validation->set_rules('phone', 'phone', 'trim|required');
	$this->form_validation->set_rules('message', 'message', 'trim|required');
	$this->form_validation->set_rules('timestamp', 'timestamp', 'trim|required');
	$this->form_validation->set_rules('receiver', 'receiver', 'trim|required');
	$this->form_validation->set_rules('groupId', 'groupid', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Inbox.php */
/* Location: ./application/controllers/Inbox.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-09 15:10:00 */
/* http://harviacode.com */