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
        $this->load->model('ManageHotline_model');
        $this->load->library('form_validation');
	    $this->load->library('datatables');
    }

    public function index()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('managehotline/create_action'),
            'id' => set_value(''),
            'name' => set_value(''),
            'created' => set_value(''),
            'createdby' => set_value(''),
            'updated' => set_value(''),
            'updatedby' => set_value(''),
            'member' => '',
	    );
        $this->template->load('template','managehotline/managehotline_list', $data);
    } 
    public function member($id)
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('managehotline/create_action'),
            'id' => set_value(''),
            'name' => set_value(''),
            'created' => set_value(''),
            'createdby' => set_value(''),
            'updated' => set_value(''),
            'updatedby' => set_value(''),
            'member' => $id,
        );
        $this->template->load('template','managehotline/managehotline_list', $data);
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
    
    public function create_action() 
    {
        
        $this->_rules();

        

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "code" => "error",
                "message" => validation_errors(),
                "form_error" => $this->form_validation->error_array(),
            ]);
            die();
        } else {
            $data = array(
                'name' => $this->input->post('name',TRUE),
                'device_id' => $this->input->post('device_id',TRUE),
                'number_phone' => $this->input->post('number_phone',TRUE),
                'created' => date("Y-m-d H:i:s"),
                'createdby' => $this->session->userdata('email'),
                'updated' => date("Y-m-d H:i:s"),
                'updatedby' => $this->session->userdata('email'),
            );

            $this->ManageHotline_model->insert($data);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
            ]);
            die();
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
        } else {
            $data = array(
                'name' => $this->input->post('name',TRUE),
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
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Milis.php */
/* Location: ./application/controllers/Milis.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-14 12:08:38 */
/* http://harviacode.com */