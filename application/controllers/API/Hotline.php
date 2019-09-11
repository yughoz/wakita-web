<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hotline extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Hotline_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
        $this->load->model('Milis_member_model');
        $this->load->model('Milis_model');

    }

    public function index()
    {
        header('Content-Type: application/json');
        $where = [
            "group_hotline" =>$this->input->post('group_hotline',TRUE),
            "flag_status >" => "2"
        ];
        $datas = $this->Hotline_model->group_json($where);
        $dataNew = [];
        $dataTemp =[];
        $countTemp = 0;
        foreach ($datas as $key => $value) {
            if ($value->flag_status == "3") {
                $value->dateParse = $this->getDate($value->created);
                $dataNew[]        = $value;
            } else {

                $dataTemp[$countTemp] = $value;
                if (!empty($value->image_name)) {
                    $dataTemp[$countTemp]->message = "Photo";
                }
                if ($value->createdby == "API_WABLAS") {
                    $dataTemp[$countTemp]->statusReplay = "customer";
                    $dataTemp[$countTemp]->statusColor  = "#ffb300";
                } else {
                    $dataTemp[$countTemp]->statusReplay = "cs";
                    $dataTemp[$countTemp]->statusColor  = "#cfcdcc";
                }

                $dataTemp[$countTemp]->message   = str_replace("\n", " ", $dataTemp[$countTemp]->message);
                
                $dataTemp[$countTemp]->dateParse =$this->getDate($value->created);

                $countTemp++;
            }

        }
        echo json_encode(["data" => $dataTemp,"dataNew" => $dataNew]);
    } 


    function getDate($date)
    {
        $current = strtotime(date("Y-m-d"));
        $date    = strtotime($date);

        $datediff = $date - $current;
        $difference = floor($datediff/(60*60*24));
        if($difference==0)
        {
            return date("H:i",$date);
        }
        else if($difference > 1)
        {
            return 'Future Date';
        }
        else if($difference > 0)
        {
            return 'tomorrow';
        }
        else if($difference < -1)
        {
            return date("Y/m/d",$date);
        }
        
        return 'yesterday';
        
    }

    public function detail()
    {
        header('Content-Type: application/json');
         $whereArr = array(
            'customer_phone'    => $this->input->post('customer_phone',TRUE),
            'group_hotline'     => $this->input->post('group_hotline',TRUE),
        );
        $startFrom = $this->input->post('start',TRUE);
        $datas =  $this->Hotline_model->detail_list($whereArr,$startFrom);
        $count =  $this->Hotline_model->count_all($whereArr);
        foreach ($datas as $key => $value) {
            if (!empty($value->image_name)) {
            	$datas[$key]->image = base_url('assets/foto_wa')."/".$value->image_name;
            }
            if ($value->createdby == "API_WABLAS") {
                $datas[$key]->username = "Customer";
                if (!empty($value->image_name)) {
                    $datas[$key]->image = "https://simo.wablas.com/image/".$value->image_name;
                    // /$datas[$key]->image = base_url('assets/foto_wa')."/".$value->image_name;
                }

            }
            $datas[$key]->_idUser = $value->user_phone ?? $value->customer_phone;
         }
        echo json_encode(["data" => $datas,"counter" =>$count,"startFrom" => $startFrom]);
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Hotline_model->json();
    }

    public function read($id) 
    {
        $row = $this->Hotline_model->get_by_id($id);
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
    		'customer_phone' => $this->input->post('customer_phone',TRUE),
    		'message' => $this->input->post('message',TRUE),
    		'flag_status' => $this->input->post('flag_status',TRUE),
    		'created' => date("Y-m-d H:i:s"),
    		'createdby' => $this->session->userdata('email'),
	    );

            $this->Hotline_model->insert($data);
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
            ]);die();
        } else {
            $data = array(
    		'customer_phone' => $this->input->post('customer_phone',TRUE),
    		'message' => $this->input->post('message',TRUE),
    		'flag_status' => $this->input->post('flag_status',TRUE),
    		'created' => $this->input->post('created',TRUE),
    		'createdby' => $this->input->post('createdby',TRUE),
	    );

            $this->Hotline_model->update($this->input->post('id', TRUE), $data);
            echo json_encode([
                "code" => "success",
                "message" => "Update Record Success",
            ]);die();
        }
    }
    
    public function delete_action($id) 
    {
        $row = $this->Hotline_model->get_by_id($id);

        if ($row) {
            $this->Hotline_model->delete($id);
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

    public function list() 
    {
        $dataHotline  = $this->Milis_member_model->get_all_where(['user_id' => $this->session->userdata('id_users')]);

        if ($dataHotline) {
            $row = [];
            foreach ($dataHotline as $key => $value) {
                $dataTemp = $this->Milis_model->get_by_id($value->milis_id);
                $dataTemp->group_hotline = $dataTemp->phone_number ;
                $row[] = $dataTemp;
            }
            echo json_encode([
                "code" => "success",
                "data"  => $row,
                "message" => "",
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
	$this->form_validation->set_rules('customer_phone', 'customer phone', 'trim|required');
	$this->form_validation->set_rules('message', 'message', 'trim|required');
	$this->form_validation->set_rules('flag_status', 'flag status', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Hotline.php */
/* Location: ./application/controllers/Hotline.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-15 11:41:24 */
/* http://harviacode.com */