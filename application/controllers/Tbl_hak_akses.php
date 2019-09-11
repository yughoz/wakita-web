<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_hak_akses extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Tbl_hak_akses_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_hak_akses/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_hak_akses/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_hak_akses/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_hak_akses_model->total_rows($q);
        $tbl_hak_akses = $this->Tbl_hak_akses_model->get_limit_data($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_hak_akses_data' => $tbl_hak_akses,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->template->load('template','tbl_hak_akses/tbl_hak_akses_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Tbl_hak_akses_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'id_user_level' => $row->id_user_level,
		'id_menu' => $row->id_menu,
	    );
            $this->template->load('template','tbl_hak_akses/tbl_hak_akses_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_hak_akses'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_hak_akses/create_action'),
	    'id' => set_value('id'),
	    'id_user_level' => set_value('id_user_level'),
	    'id_menu' => set_value('id_menu'),
	);
        $this->template->load('template','tbl_hak_akses/tbl_hak_akses_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'id_user_level' => $this->input->post('id_user_level',TRUE),
		'id_menu' => $this->input->post('id_menu',TRUE),
	    );

            $this->Tbl_hak_akses_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success 2');
            redirect(site_url('tbl_hak_akses'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_hak_akses_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_hak_akses/update_action'),
		'id' => set_value('id', $row->id),
		'id_user_level' => set_value('id_user_level', $row->id_user_level),
		'id_menu' => set_value('id_menu', $row->id_menu),
	    );
            $this->template->load('template','tbl_hak_akses/tbl_hak_akses_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_hak_akses'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'id_user_level' => $this->input->post('id_user_level',TRUE),
		'id_menu' => $this->input->post('id_menu',TRUE),
	    );

            $this->Tbl_hak_akses_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_hak_akses'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_hak_akses_model->get_by_id($id);

        if ($row) {
            $this->Tbl_hak_akses_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_hak_akses'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_hak_akses'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('id_user_level', 'id user level', 'trim|required');
	$this->form_validation->set_rules('id_menu', 'id menu', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Tbl_hak_akses.php */
/* Location: ./application/controllers/Tbl_hak_akses.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-07-10 05:30:13 */
/* http://harviacode.com */