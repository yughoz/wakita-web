<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageInvoice extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('API/ManageInvoice_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('ManageInvoice/create_action'),
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
        $this->template->load('template','ManageInvoice/ManageInvoice_list', $data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->ManageInvoice_model->json();
    }

    public function read($id) 
    {
        $row = $this->ManageInvoice_model->get_by_id($id);
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

            $this->ManageInvoice_model->insert($data);
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

            $this->ManageInvoice_model->update($this->input->post('package_id', TRUE), $data);
            echo json_encode([
                "code" => "success",
                "message" => "Update Record Success",
            ]);
            die();
        }
    }
    
    public function delete_action($id) 
    {
        $row = $this->ManageInvoice_model->get_by_id($id);

        if ($row) {
            $this->ManageInvoice_model->delete($id);
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

/* End of file ManageInvoice.php */
/* Location: ./application/controllers/ManageInvoice.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-09-15 17:31:31 */
/* http://harviacode.com */