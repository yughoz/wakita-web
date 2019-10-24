
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class ManageChat extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Send_message_detail_model');
        $this->load->model('ManageChat_model');
        $this->load->model('ManageHotline_model');
        $this->load->model('Contact_model');
        $this->load->model('Milis_member_model');
        $this->load->model('Hotline_model');

        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->config->load('apiwha');
        $this->load->model('Inbox_model');
        
        $this->apiToken     = "";
        $this->url          = $this->config->item('APIWeb');
        $this->client       = new GuzzleHttp\Client();
        
        $this->startRes = time();

        $this->load->library('wablas');
    }

    public function index()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('ManageChat/create_action'),
            'id' => set_value(''),
            'name' => set_value(''),
            'created' => set_value(''),
            'createdby' => set_value(''),
            'updated' => set_value(''),
            'updatedby' => set_value(''),
            'id_user_level' => set_value(''),
            'member' => '',
	    );
        $this->template->load('template','ManageChat/ManageChat_list', $data);
    } 

    public function list_hotline_json() {
        header('Content-Type: application/json');
        echo $this->ManageChat_model->list_json();
    }

    public function hotline($id)
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
            'hotline' => $id,
            'generateLink' => base_url().'API/DirectLink/file/',
            'userSession'   => $this->session->userdata('phone'),
        );
        $this->template->load('template','ManageChat/ManageChat_list_member', $data);
    }

    public function list_hotline_member_json($hotline) {

        $where = [
            "group_hotline" =>$hotline,
            "flag_status >" => "2"
        ];
        
        header('Content-Type: application/json');
        
        $result = array('data' => array());

        $data = $this->ManageChat_model->group_json($where);
        foreach ($data as $key => $value) {
            // //i assigned $buttons variable to hold my edit and delete btn to pass in my array.
            $buttons  = '
            <button class="btn btn-primary" onclick="editData('.$value->id.')" data-toggle="modal" data-target="#myModal">Edit</button>
            <button class="btn btn-danger" onclick="deleteData('.$value->id.')" data-toggle="modal" data-target="#deleteModal">Delete</button>
            ';

            $result['data'][$key] = array(
               'id' => $value->id,
                'customer_phone' => $value->customer_phone,
                'name_wa' => $value->name_wa,
                'name_replace' => $value->name_replace,
                'message' => $value->message,
                'flag_status' => $value->flag_status,
                'created' => $value->created,
                'createdby' => $value->createdby,
                'image_name' => $value->image_name,
                'group_hotline' => $value->group_hotline,
                'buttons' => $buttons
            );
        }
        echo json_encode($result);
    }

    public function detail_json($hotline, $customer, $start, $private) {

        header('Content-Type: application/json');
         
        if($private == 1){
            $table = 'hotline_private';
        }else if($private == 0){
            $table = 'hotline';
        }
        $whereArr = array(
            $table.'.customer_phone'        => $customer,
            // 'hotline.group_hotline' => $hotline,
        );
        // echo $table;
        // exit();
        $startFrom  = $start;
        $datas      = array_reverse($this->Hotline_model->detail_list($whereArr, $startFrom, $table));
        // echo var_dump($datas);
        // exit();
        $count      = $this->Hotline_model->count_all($whereArr,$table);
        foreach ($datas as $key => $value) {
            if (!empty($value->image_name)) {
                // $datas[$key]->image = base_url('assets/foto_wa')."/".$value->image_name;
                $datas[$key]->image	= base_url("API/DirectLink/file/")."image/".$value->image_name;
            }
            if ($value->createdby == "API_WABLAS") {
                $datas[$key]->username = "Customer - ".$value->username_title;
                if (!empty($value->image_name)) {
                    $datas[$key]->image 		= base_url("API/DirectLink/file/")."image/".$value->image_name;
                    $datas[$key]->extension 	= pathinfo($value->image_name, PATHINFO_EXTENSION);
                    // /$datas[$key]->image = base_url('assets/foto_wa')."/".$value->image_name;
                }

                if (!empty($value->video_name)) {
                    $datas[$key]->video 		= base_url("API/DirectLink/file/")."video/".$value->video_name;
                    $datas[$key]->extension 	= pathinfo($value->video_name, PATHINFO_EXTENSION);
                    // /$datas[$key]->image = base_url('assets/foto_wa')."/".$value->image_name;
                }

                if (!empty($value->document_name)) {
                    $datas[$key]->document      = base_url("API/DirectLink/file/")."document/".$value->document_name;
                    $datas[$key]->extension 	= pathinfo($value->document_name, PATHINFO_EXTENSION);
                    // /$datas[$key]->image 	= base_url('assets/foto_wa')."/".$value->image_name;
                }

            }
            $datas[$key]->_idUser = $value->user_phone ?? $value->customer_phone;
         }
        echo json_encode(["data" => $datas,"counter" =>$count,"startFrom" => $startFrom]);

    }

    public function send_whatsapp() 
    {
        $type       = $this->input->post('type',TRUE);

        $this->apiToken  = $this->ManageChat_model->get_token($this->input->post('noPhoneFrom',TRUE));
        if($type != 'text'){
            $resultUpload = $this->wablas->upload_file($this->input->post('type',TRUE), 'fileupload');
        }
        
        $data = array(
            'private'           => $this->input->post('private', TRUE),
            'token'             => $this->apiToken,
            'type'              => $this->input->post('type',TRUE),
            'phone'             => $this->input->post('noPhone',TRUE),
            'caption'           => $this->input->post('message',TRUE),
            'file'              => '',
            'status'            => $this->input->post('status',TRUE),
            'hotline'           => $this->input->post('noPhoneFrom',TRUE),
            'createdby'         => "API",
            'updatedby'         => "API",
            'session_email'     => $this->session->userdata('email'),
            'session_userphone' => $this->session->userdata('phone'),
            'session_username'  => $this->session->userdata('full_name')
        );
        if($type != 'text'){
            $data['file'] = $resultUpload['file_name'];
        }

        // print_r($resultUpload);

        if($this->wablas->_sendWablas($data) == true){
            echo json_encode([
                "code"      => "success",
                "message"   => "Create Record Success",
            ]);
        }else{
            echo json_encode([
                "code"      => "failed",
                "message"   => "Create Record failed",
            ]);
        }
        die();
    }

    public function saveContact(){
        $dataContact = [
            "name_wa"       => "",
            "name_replace"  => $this->input->post('name',TRUE),
            "phone"         => $this->input->post('phone',TRUE),
            'group_hotline' => $this->input->post('hotline',TRUE),
            'created'       => date("Y-m-d H:i:s"),
            'createdby'     => $this->session->userdata('email'),
            'updated'       => date("Y-m-d H:i:s"),
            'updatedby'     => $this->session->userdata('email'),
        ];

        $customer_name  = $this->Contact_model->insert_update_web($dataContact);
        if($customer_name){
            echo json_encode([
                "code"      => "success",
                "value"   => $customer_name,
            ]);
        }else{
            echo json_encode([
                "code"      => "failed",
                "value"   => $this->input->post('phone',TRUE)
            ]);
        }
    }

    public function manufacturer_list()
    {

        $result = array('data' => array());

        $data = $this->manufacturer_model->fetchManufacturerData();
        foreach ($data as $key => $value) {

            //i assigned $buttons variable to hold my edit and delete btn to pass in my array.
            $buttons  = '
            <button class="btn btn-primary" onclick="editData('.$value->id.')" data-toggle="modal" data-target="#myModal">Edit</button>
            <button class="btn btn-danger" onclick="deleteData('.$value->id.')" data-toggle="modal" data-target="#deleteModal">Delete</button>
            ';

            $result['data'][$key] = array(
                $value->id,
                $value->brand,
                $buttons
            );
        }
        echo json_encode($result);      
    }

    // public function json() {
    //     header('Content-Type: application/json');
    //     $where = [
    //         "group_hotline" =>$this->input->post('group_hotline',TRUE),
    //         "flag_status >" => "2"
    //     ];
    //     echo $this->Hotline_model->group_json($where);
    // }

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
            echo $idx;
            // die();
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
