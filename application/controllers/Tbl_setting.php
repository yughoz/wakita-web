<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_setting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('API/Tbl_setting_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_setting/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_setting/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_setting/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_setting_model->total_rows($q);
        $tbl_setting = $this->Tbl_setting_model->get_limit_data($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_setting_data' => $tbl_setting,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->template->load('template','tbl_setting/tbl_setting_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Tbl_setting_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_setting' => $row->id_setting,
		'nama_setting' => $row->nama_setting,
		'value' => $row->value,
	    );
            $this->template->load('template','tbl_setting/tbl_setting_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_setting'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_setting/create_action'),
	    'id_setting' => set_value('id_setting'),
	    'nama_setting' => set_value('nama_setting'),
	    'value' => set_value('value'),
	);
        $this->template->load('template','tbl_setting/tbl_setting_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'nama_setting' => $this->input->post('nama_setting',TRUE),
		'value' => $this->input->post('value',TRUE),
	    );

            $this->Tbl_setting_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success 2');
            redirect(site_url('tbl_setting'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_setting_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_setting/update_action'),
		'id_setting' => set_value('id_setting', $row->id_setting),
		'nama_setting' => set_value('nama_setting', $row->nama_setting),
		'value' => set_value('value', $row->value),
	    );
            $this->template->load('template','tbl_setting/tbl_setting_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_setting'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_setting', TRUE));
        } else {
            $data = array(
		'nama_setting' => $this->input->post('nama_setting',TRUE),
		'value' => $this->input->post('value',TRUE),
	    );

            $this->Tbl_setting_model->update($this->input->post('id_setting', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_setting'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_setting_model->get_by_id($id);

        if ($row) {
            $this->Tbl_setting_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_setting'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_setting'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nama_setting', 'nama setting', 'trim|required');
	$this->form_validation->set_rules('value', 'value', 'trim|required');

	$this->form_validation->set_rules('id_setting', 'id_setting', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Tbl_setting.php */
/* Location: ./application/controllers/Tbl_setting.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-10 05:30:13 */
/* http://harviacode.com */