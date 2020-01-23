<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageUserLevel extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //is_login();
        $this->load->model('API/ManageUserLevel_model');
        $this->load->library('form_validation');
	    $this->load->library('datatables');
    }

    public function index()
    {
        $this->template->load('template','ManageUserLevel/ManageUserLevel_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->ManageUserLevel_model->json();
    }

    public function read($id) 
    {
        $row = $this->ManageUserLevel_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id_user_level' => $row->id_user_level,
                'nama_level' => $row->nama_level,
	        );
            $this->template->load('template','ManageUserLevel/ManageUserLevel_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ManageUserLevel'));
        }
    }
    
    function akses(){
        $data['level'] = $this->db->get_where('tbl_user_level',array('id_user_level'=>  $this->uri->segment(3)))->row_array();
        $data['menu'] = $this->db->get('tbl_menu')->result();
        $this->template->load('template','ManageUserLevel/ManageUserLevel_akses',$data);
    }
    
    function kasi_akses_ajax(){
        $id_menu        = $_GET['id_menu'];
        $id_user_level  = $_GET['level'];
        // chek data
        $params = array('id_menu'=>$id_menu,'id_user_level'=>$id_user_level);
        $akses = $this->db->get_where('tbl_hak_akses',$params);
        if($akses->num_rows()<1){
            // insert data baru
            $this->db->insert('tbl_hak_akses',$params);
        }else{
            $this->db->where('id_menu',$id_menu);
            $this->db->where('id_user_level',$id_user_level);
            $this->db->delete('tbl_hak_akses');
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('ManageUserLevel/create_action'),
            'id_user_level' => set_value('id_user_level'),
            'nama_level' => set_value('nama_level'),
        );
        $this->template->load('template','ManageUserLevel/ManageUserLevel_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'nama_level' => $this->input->post('nama_level',TRUE),
            );

            $this->ManageUserLevel_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('ManageUserLevel'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->ManageUserLevel_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('ManageUserLevel/update_action'),
		        'id_user_level' => set_value('id_user_level', $row->id_user_level),
		        'nama_level' => set_value('nama_level', $row->nama_level),
	        );
            $this->template->load('template','ManageUserLevel/ManageUserLevel_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ManageUserLevel'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_user_level', TRUE));
        } else {
            $data = array(
                'nama_level' => $this->input->post('nama_level',TRUE),
            );

            $this->ManageUserLevel_model->update($this->input->post('id_user_level', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('ManageUserLevel'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->ManageUserLevel_model->get_by_id($id);

        if ($row) {
            $this->ManageUserLevel_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('ManageUserLevel'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ManageUserLevel'));
        }
    }

    public function _rules() 
    {
        $this->form_validation->set_rules('nama_level', 'nama level', 'trim|required');
        $this->form_validation->set_rules('id_user_level', 'id_user_level', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_user_level.xls";
        $judul = "tbl_user_level";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
	    xlsWriteLabel($tablehead, $kolomhead++, "Nama Level");

	    foreach ($this->ManageUserLevel_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	        xlsWriteLabel($tablebody, $kolombody++, $data->nama_level);

	        $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=tbl_user_level.doc");

        $data = array(
            'tbl_user_level_data' => $this->ManageUserLevel_model->get_all(),
            'start' => 0
        );
        
        $this->load->view('ManageUserLevel/tbl_user_level_doc',$data);
    }

}

/* End of file Userlevel.php */
/* Location: ./application/controllers/Userlevel.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-10-04 06:29:37 */
/* http://harviacode.com */