<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManagePackage extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('API/ManagePackage_model');
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
        $this->template->load('template','ManagePackage/ManagePackage_list', $data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->ManagePackage_model->json();
    }

    public function read($id) 
    {
        $row = $this->ManagePackage_model->get_by_id($id);
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
            'package_name' => $this->input->post('package_name',TRUE),
            'package_total' => $this->input->post('package_total',TRUE),
            'package_price' => $this->input->post('package_price',TRUE),
            'created' => date("Y-m-d H:i:s"),
            'createdby' => $this->session->userdata('email'),
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => $this->session->userdata('email'),
            );

            $this->ManagePackage_model->insert($data);
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
            'package_name' => $this->input->post('package_name',TRUE),
            'package_total' => $this->input->post('package_total',TRUE),
            'package_price' => $this->input->post('package_price',TRUE),
            'created' => $this->input->post('created',TRUE),
            'createdby' => $this->input->post('createdby',TRUE),
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => $this->session->userdata('email'),
            );

            $this->ManagePackage_model->update($this->input->post('package_id', TRUE), $data);
            echo json_encode([
                "code" => "success",
                "message" => "Update Record Success",
            ]);
            die();
        }
    }
    
    public function delete_action($id) 
    {
        $row = $this->ManagePackage_model->get_by_id($id);

        if ($row) {
            $this->ManagePackage_model->delete($id);
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
    $this->form_validation->set_rules('package_name', 'package_name', 'trim|required');
    $this->form_validation->set_rules('package_total', 'package_total', 'trim|required');
    $this->form_validation->set_rules('package_price', 'package_price', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
