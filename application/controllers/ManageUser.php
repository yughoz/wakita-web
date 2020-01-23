<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageUser extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('API/ManageUser_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
        $this->load->library('wakitalib');
        $this->load->model('API/ManageUser_model');
        $this->load->model('API/ManageHotline_model');
        $this->load->model('API/ManageHotlineMember_model');
        $this->load->model('API/ManageUserLevel_model');
        $this->startRes = time();
    }

    public function company_pid(){
        echo $this->session->userdata('company_pid');
    }
    public function index()
    {
        $data['hotline'] = $this->ManageHotline_model->get_all_where(['company_id'    => $this->session->userdata('company_pid')]);
        // echo print_r($data);die(); 
        $this->template->load('template','ManageUser/ManageUser_list',$data);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        $arrData  = json_decode($this->ManageUser_model->json(),true);
        foreach ($arrData['data'] as $key => $value) {
            $userLevel = $this->ManageUserLevel_model->get_by_id($value['id_user_level']);
           $arrData['data'][$key]['nama_level'] = $userLevel->nama_level ?? "";
        }
        echo json_encode($arrData);
    }

    public function read($id) 
    {
        $row = $this->User_model->get_by_id($id);
        if ($row) {
            $data = array(
            'pid'      => $row->pid,
            'full_name'     => $row->full_name,
            'email'         => $row->email,
            'phone'         => $row->phone,
            'password'      => $row->password,
            'images'        => $row->images,
            'id_user_level' => $row->id_user_level,
            // 'is_aktif'      => $row->is_aktif,
	        );
            $this->template->load('template','ManageUser/ManageUser_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ManageUser'));
        }
    }

    public function create() 
    {
        $data = array(
            'button'        => 'Create',
            'action'        => site_url('ManageUser/create_action'),
            'pid'           => set_value('pid'),
            'full_name'     => set_value('full_name'),
            'phone'         => set_value('phone'),
            'email'         => set_value('email'),
            'password'      => set_value('password'),
            'images'        => set_value('images'),
            'id_user_level' => set_value('id_user_level'),
            // 'is_aktif'      => set_value('is_aktif'),
	    );
        $this->template->load('template','ManageUser/ManageUser_form', $data);
    }
    
    
    public function create_action() 
    {
        $this->_rules();
        $foto = $this->upload_foto();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $password       = $this->input->post('password',TRUE);
            $options        = array("cost"=>4);
            $hashPassword   = password_hash($password,PASSWORD_BCRYPT,$options);
            
            $data = array(
                'pid'           => $this->wakitalib->get_pid('TUSR'),
                'full_name'     => $this->input->post('full_name',TRUE),
                'email'         => $this->input->post('email',TRUE),
                'phone'         => $this->input->post('phone',TRUE),
                'password'      => $hashPassword,
                'images'        => $foto['file_name'],
                'id_user_level' => $this->input->post('id_user_level',TRUE),
                'company_id'    => $this->session->userdata('company_pid'),
                'created'       => date("Y-m-d H:i:s"),
                'createdby'     => $this->session->userdata('email'),
                'updated'       => date("Y-m-d H:i:s"),
                'updatedby'     => $this->session->userdata('email'),
                // 'is_aktif'      => $this->input->post('is_aktif',TRUE),
            );


            // echo print_r($data);die;

            // if ($this->ManageUser_model->check_insert($data)) {
                $this->ManageUser_model->insert($data);
                $this->session->set_flashdata('message', 'Create Record Success');
                redirect(site_url('ManageUser'));

            // } else {
            //     $this->session->set_flashdata('message', 'Email or phone already exist');
            //     redirect(site_url('ManageUser/create'));
            // }
        }
    }

    public function create_trial_action() 
    {
        // $this->_rules();
        $this->form_validation->set_rules('counter', 'counter', 'trim|required');
        $foto = $this->upload_foto();
        $random = [];
        $prefix = "@".$this->input->post('prefix',TRUE);
        $prefix .= ".trial.id";
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "code" => "error",
                "message" => validation_errors(),
                "form_error" => $this->form_validation->error_array(),
            ]);die();
        } else {

            for ($i=0; $i < $this->input->post('counter',TRUE); $i++) { 

                $username   = date('M').rand(1000,9999);
                $email      = $username.$prefix;
                $password   = $this->randomPassword();


                $options        = array("cost"=>4);
                $hashPassword   = password_hash($password,PASSWORD_BCRYPT,$options);
                
                $data = array(
                    'pid'           => $this->wakitalib->get_pid_id('tbl_user',"TRIAL",'pid',1),
                    'full_name'     => $username,
                    'email'         => $email,
                    'phone'         => "0812345".rand(100,999),
                    'password'      => $hashPassword,
                    'images'        => "",
                    'id_user_level' => $this->input->post('id_user_level',TRUE),
                    // 'is_aktif'      => "1",
                );

                if ($this->ManageUser_model->check_insert($data)) {
                    $user_id = $this->ManageUser_model->insert($data);

                    $dataHotlineMember = array(
                        'pid'       => $this->wakitalib->get_pid_id('hotline_member',"HM",'pid',1),
                        'group_number'  => $this->input->post('group_number',TRUE),
                        'user_id'   => $user_id,
                        'created'   => date("Y-m-d H:i:s"),
                        'createdby' => $this->session->userdata('email'),
                        'updated'   => date("Y-m-d H:i:s"),
                        'updatedby' => $this->session->userdata('email'),
                    );

                    $this->ManageHotlineMember_model->insert($dataHotlineMember);
                    // $this->session->set_flashdata('message', 'Create Record Success');
                    // redirect(site_url('ManageUser'));
                    

                    $random[$i]['id_user']  =   $user_id;
                    $random[$i]['email']    =   $email;
                    $random[$i]['password'] =   $password;

                }
            }
            
            $this->Loging("ManageUser_create_trial_action" , $random);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
                "data" => $random
            ]);die();

            // echo print_r($random);
        }
    }

    function randomPassword( $length = 7 ) 
    { 
        // $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?"; 
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
        // $length = rand(10, 16); 
        $password = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );
        // $password = str_shuffle(sha1(rand() . time()) . $chars );
        // echo substr($password, 0,$length);
        // echo "<br>\n";
        // echo $password ;
        // echo "<br>\n ".$length;
        return $password;
    }
    
    public function update($id) 
    {
        $row = $this->ManageUser_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button'        => 'Update',
                'action'        => site_url('ManageUser/update_action'),
                'pid'      => set_value('pid', $row->pid),
                'pid'           => set_value('pid', $row->pid),
                'full_name'     => set_value('full_name', $row->full_name),
                'email'         => set_value('email', $row->email),
                'phone'         => set_value('phone', $row->phone),
                'password'      => set_value('password', $row->password),
                'images'        => set_value('images', $row->images),
                'id_user_level' => set_value('id_user_level', $row->id_user_level),
                // 'is_aktif'      => set_value('is_aktif', $row->is_aktif),
            );

            $this->template->load('template','ManageUser/ManageUser_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ManageUser'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();
        $foto = $this->upload_foto();
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('pid', TRUE));
        } else {
            if($foto['file_name']==''){
                $data = array(
                    'full_name'     => $this->input->post('full_name',TRUE),
                    'email'         => $this->input->post('email',TRUE),
                    'phone'         => $this->input->post('phone',TRUE),
                    'id_user_level' => $this->input->post('id_user_level',TRUE),
                    // 'is_aktif'      => $this->input->post('is_aktif',TRUE)
                );
            }else{
                $data = array(
                    'full_name'     => $this->input->post('full_name',TRUE),
                    'email'         => $this->input->post('email',TRUE),
                    'phone'         => $this->input->post('phone',TRUE),
                    'images'        => $foto['file_name'],
                    'id_user_level' => $this->input->post('id_user_level',TRUE),
                    // 'is_aktif'      => $this->input->post('is_aktif',TRUE)
                );
                
                // ubah foto profil yang aktif
                $this->session->set_userdata('images',$foto['file_name']);
            }
            $msData = $this->ManageUser_model->get_by_id($this->input->post('pid', TRUE));
            // $msData  = $this->ManageUser_model->getMSuser($this->input->post('pid', TRUE));
            // print_r($oldData);
            // print_r($msData);
            // die();
            if ($this->ManageUser_model->updateMST($msData->pid,$data,$msData) == true) {
                $this->ManageUser_model->update($this->input->post('pid', TRUE), $data);
                $this->session->set_flashdata('message', 'Update Record Success');
                // print_r($msData);
                // die();
                redirect(site_url('ManageUser'));
            } else {
                $this->session->set_flashdata('message', 'Email or phone already exist');
                $this->update($this->input->post('pid', TRUE));
            }
            
        }
    }
     
    public function update_profile_action() 
    {
        $this->_rules_profile();
        $foto = $this->upload_foto();
        if ($this->form_validation->run() == FALSE) {
            $this->profile($this->input->post('pid', TRUE));
        } else {
            if($foto['file_name']==''){
                $data = [
                    'full_name'     => $this->input->post('full_name',TRUE),
                    'email'         => $this->input->post('email',TRUE),
                    'phone'         => $this->input->post('phone',TRUE)
                ];
                    // 'id_user_level' => $this->input->post('id_user_level',TRUE),
                    // 'is_aktif'      => $this->input->post('is_aktif',TRUE));
            }else{
                $data = [
                    'full_name'     => $this->input->post('full_name',TRUE),
                    'email'         => $this->input->post('email',TRUE),
                    'phone'         => $this->input->post('phone',TRUE),
                    'images'        => $foto['file_name']
                ];
                    // 'id_user_level' => $this->input->post('id_user_level',TRUE),
                    // 'is_aktif'      => $this->input->post('is_aktif',TRUE));
                
                // ubah foto profil yang aktif
                $this->session->set_userdata('images',$foto['file_name']);
            }
            if (!empty($this->input->post('old_password'))) {
                if(password_verify($this->input->post('old_password',TRUE),$this->session->userdata('password'))){
                    $password       = $this->input->post('new_password',TRUE);
                    $options        = array("cost"=>4);
                    $hashPassword   = password_hash($password,PASSWORD_BCRYPT,$options);
                    $data['password'] = $hashPassword;
// die("1212");
                }else {
                    // die("32123");
                    $this->session->set_flashdata('message', 'Invalid Old password');
                    redirect(site_url('ManageUser/profile'));die();
                }
            }

            $this->ManageUser_model->update($this->input->post('pid', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('ManageUser/profile'));
        }
    }
    
    function upload_foto(){
        $config['upload_path']          = './assets/foto_profil';
        $config['allowed_types']        = 'gif|jpg|png';
        //$config['max_size']             = 100;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        $this->load->library('upload', $config);
        $this->upload->do_upload('images');
        return $this->upload->data();
    }
    
    public function delete($id) 
    {
        $row = $this->ManageUser_model->get_by_id($id);

        if ($row) {
            $oldData = $this->ManageUser_model->get_by_id($id);
            // $msData  = $this->ManageUser_model->getMSuser($oldData->pid);
            $this->ManageUser_model->delete($id,$oldData->pid);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('ManageUser'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ManageUser'));
        }
    }

    public function _rules() 
    {
    	$this->form_validation->set_rules('full_name', 'full name', 'trim|required');
    	$this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
    	//$this->form_validation->set_rules('password', 'password', 'trim|required');
    	//$this->form_validation->set_rules('images', 'images', 'trim|required');
    	$this->form_validation->set_rules('id_user_level', 'id user level', 'trim|required');
    	// $this->form_validation->set_rules('is_aktif', 'is aktif', 'trim|required');

    	$this->form_validation->set_rules('pid', 'pid', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    public function _rules_profile() 
    {
        $this->form_validation->set_rules('full_name', 'full name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
        $this->form_validation->set_rules('pid', 'pid', 'trim');
        if (!empty($this->input->post('old_password'))) {
            // $this->form_validation->set_rules('old_password', 'password', 'trim|required');
            $this->form_validation->set_rules('new_password', 'new password', 'trim|required');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'trim|required|matches[new_password]');
            
        }
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_user.xls";
        $judul = "tbl_user";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Full Name");
        xlsWriteLabel($tablehead, $kolomhead++, "Email");
        xlsWriteLabel($tablehead, $kolomhead++, "Password");
        xlsWriteLabel($tablehead, $kolomhead++, "Images");
        xlsWriteLabel($tablehead, $kolomhead++, "Id User Level");
        xlsWriteLabel($tablehead, $kolomhead++, "Is Aktif");

	foreach ($this->ManageUser_model->get_all() as $data) {
        $kolombody = 0;

        //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
        xlsWriteNumber($tablebody, $kolombody++, $nourut);
        xlsWriteLabel($tablebody, $kolombody++, $data->full_name);
        xlsWriteLabel($tablebody, $kolombody++, $data->email);
        xlsWriteLabel($tablebody, $kolombody++, $data->password);
        xlsWriteLabel($tablebody, $kolombody++, $data->images);
        xlsWriteNumber($tablebody, $kolombody++, $data->id_user_level);
        // xlsWriteLabel($tablebody, $kolombody++, $data->is_aktif);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=tbl_user.doc");

        $data = array(
            'tbl_user_data' => $this->ManageUser_model->get_all(),
            'start' => 0
        );
        
        $this->load->view('user/tbl_user_doc',$data);
    }
    
    function profile(){
        $id = $this->session->userdata('pid');
        $row = $this->ManageUser_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button'        => 'Update',
                'action'        => site_url('ManageUser/update_profile_action'),
                'pid'      => set_value('pid', $row->pid),
                'full_name'     => set_value('full_name', $row->full_name),
                'email'         => set_value('email', $row->email),
                'phone'         => set_value('phone', $row->phone),
                'password'      => set_value('password', $row->password),
                'images'        => set_value('images', $row->images),
                'id_user_level' => set_value('id_user_level', $row->id_user_level),
                // 'is_aktif'      => set_value('is_aktif', $row->is_aktif),
            );
            $this->template->load('template','ManageUser/ManageUser_profile', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ManageUser/ManageUser_profile'));
        }
    }


    function Loging($name,$param){

        $fullpath=FCPATH.'Log/'.date('Y').'/'.date('m').'/'.date('d');
        $filepath = $fullpath.'/'.$name.'.txt';
        if (!is_dir($fullpath)) {
            mkdir($fullpath, 0755, TRUE);
        }
        $saveData = [
            "timeRes" => time() - $this->startRes,
            "param" => $param
        ];

        file_put_contents($filepath,json_encode($saveData).PHP_EOL, FILE_APPEND);
    }

}

/* End of file User.php */
/* Location: ./application/controllers/User.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-10-04 06:32:22 */
/* http://harviacode.com */