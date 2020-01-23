<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageHotline extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('API/ManageHotline_model');
        $this->load->library('form_validation');
	    $this->load->library('datatables');
    }

    public function index()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('ManageHotline/create_action'),
            'id' => set_value(''),
            'name' => set_value(''),
            'created' => set_value(''),
            'createdby' => set_value(''),
            'updated' => set_value(''),
            'updatedby' => set_value(''),
            'id_user_level' => set_value(''),
            'member' => '',
	    );
        $this->template->load('template','ManageHotline/ManageHotline_list', $data);
    } 
    public function member($id)
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('ManageHotline/create_action'),
            'id' => set_value(''),
            'name' => set_value(''),
            'created' => set_value(''),
            'createdby' => set_value(''),
            'updated' => set_value(''),
            'updatedby' => set_value(''),
            'member' => $id,
        );
        $this->template->load('template','ManageHotline/ManageHotline_list', $data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->ManageHotline_model->json();
    }

    public function read($id) 
    {
        $row = $this->ManageHotline_model->get_by_id($id);
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
            $data1 = array(
                'name' => $this->input->post('name',TRUE),
                'package_id' => $this->input->post('package_id',TRUE),
                'device_id' => $this->input->post('device_id',TRUE),
                'device_name' => $this->input->post('device_name',TRUE),
                'domain_api' => $this->input->post('domain_api',TRUE),
                'token' => $this->input->post('token',TRUE),
                'phone_number' => $this->input->post('phone_number',TRUE),
                'created' => date("Y-m-d H:i:s"),
                'createdby' => $this->session->userdata('email'),
                'updated' => date("Y-m-d H:i:s"),
                'updatedby' => $this->session->userdata('email'),
            );

            $idx = $this->ManageHotline_model->insert($data1);
            $data2 = array(
                'milis_id' => $idx,
                'invoice_number' => "",
                'invoice_package' => $this->input->post('package_id',TRUE),
                'invoice_start_date' => date("Y-m-d H:i:s"),
                'invoice_end_date' => date('Y-m-d h:i:s', mktime(date('h'),date('i'),date('s'),date('m'),date('d')+30,date('Y'))),
                'created' => date("Y-m-d H:i:s"),
                'createdby' => $this->session->userdata('email'),
                'updated' => date("Y-m-d H:i:s"),
                'updatedby' => $this->session->userdata('email'),
                );
            $this->ManageHotline_model->insert2($data2);
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
            ]);
            die();
        } 
        else {
            $data = array(
            'name' => $this->input->post('name',TRUE),
            'device_id' => $this->input->post('device_id',TRUE),
            'device_name' => $this->input->post('device_name',TRUE),
            'domain_api' => $this->input->post('domain_api',TRUE),
            'token' => $this->input->post('token',TRUE),
            'phone_number' => $this->input->post('phone_number',TRUE),
            'created' => $this->input->post('created',TRUE),
            'createdby' => $this->input->post('createdby',TRUE),
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => $this->session->userdata('email'),
            );

            $this->ManageHotline_model->update($this->input->post('id', TRUE), $data);
            echo json_encode([
                "code" => "success",
                "message" => "Update Record Success",
            ]);
            die();
        }
    }
    
    public function delete_action($id) 
    {
        $row = $this->ManageHotline_model->get_by_id($id);

        if ($row) {
            $this->ManageHotline_model->delete($id);
            echo json_encode([
                "code" => "success",
                "message" => "Delete Record Success",
            ]);
            die();
        } else {
            echo json_encode([
                "code" => "error",
                "message" => "Record Not Found",
            ]);
            die();
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('name', 'name', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
