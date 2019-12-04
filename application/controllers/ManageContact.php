<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageContact extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('ManageContact_model');
        $this->load->library('form_validation');
	    $this->load->library('datatables');
    }

    public function index()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('ManagePackage/create_action'),
            'id' => set_value(''),
            'package_name' => set_value(''),
            'package_total' => set_value(''),
            'package_price' => set_value(''),
            'created' => set_value(''),
            'createdby' => set_value(''),
            'updated' => set_value(''),
            'updatedby' => set_value(''),
            'member' => '',
	    );
        $this->template->load('template','ManageContact/index', $data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->ManageContact_model->json();
    }

    public function read($id) 
    {
        $row = $this->ManageContact_model->get_by_id($id);
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
            'name_wa'   => '',
            'phone' => $this->input->post('phone',TRUE),
            'name_replace' => $this->input->post('name',TRUE),
            'group_hotline' => $this->input->post('hotline',TRUE),
            'created' => date("Y-m-d H:i:s"),
            'createdby' => $this->session->userdata('email'),
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => $this->session->userdata('email'),
            );

            $this->ManageContact_model->insert($data);
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
            'phone'         => $this->input->post('phone',TRUE),
            'name_replace'  => $this->input->post('name',TRUE),
            'group_hotline' => $this->input->post('hotline',TRUE),
            'updated'       => date("Y-m-d H:i:s"),
            'updatedby'     => $this->session->userdata('email'),
            );

            $this->ManageContact_model->update($this->input->post('id', TRUE), $data);
            echo json_encode([
                "code" => "success",
                "message" => "Update Record Success",
            ]);
            die();
        }
    }
    
    public function delete_action($id) 
    {
        $row = $this->ManageContact_model->get_by_id($id);

        if ($row) {
            $this->ManageContact_model->delete($id);
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
    $this->form_validation->set_rules('phone', 'phone', 'trim|required');
    $this->form_validation->set_rules('hotline', 'hotline', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
