<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class Hotline_private extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Hotline_private_model');
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->model('Milis_member_model');
        $this->load->model('ManageHotlineMember_model');
        $this->load->model('ManageHotline_model');
        $this->load->model('Inbox_model');

        $this->client       = new GuzzleHttp\Client();
    }

    public function index()
    {
        header('Content-Type: application/json');
        $where = [
            "vw_group_milis.group_hotline" =>$this->input->post('group_hotline',TRUE),
            "flag_status >" => "2"
        ];
        $datas          =   $this->Hotline_private_model->group_json($where);
        $dataNew        =   []  ;
        $dataTemp       =   []  ;
        $countTemp      =   0   ;
        $strLengtLabel  =   13  ;
        $strLengtMsg    =   30  ;

        foreach ($datas as $key => $value) {
            if (empty($value->customer_title) ) {
                $value->customer_title      = "+".$value->customer_phone;
                $value->username_title_sort = "+".$value->customer_phone;
                $value->username_num        = "";
                // $dataTemp[$countTemp]->username = "312";
            } else {
                $value->full_name       = $value->customer_title;
                $moreStr = strlen($value->customer_title) > $strLengtLabel ? "..." : ""; 
                // $value->username_title_sort  = substr($value->username_title, 0, $strLengtLabel ).$moreStr; 
                $value->username_title_sort  = $value->username_title_sort.$moreStr; 
                $value->username_num    = "+".$value->customer_phone;
                // $value->username = $value->username." +".$value->customer_phone."";
            }
            
            // if ($this->session->userdata('user_level') == "TRIAL") {
            if (  !checking_akses("show_number") ) {
                $value->customer_title      = $this->parsingTrialNumber($value->customer_phone);
                $value->username_title_sort = $this->parsingTrialNumber($value->customer_phone);
                $value->username_num        = "";
            }

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


                $moreStr = strlen($dataTemp[$countTemp]->message) >= $strLengtMsg ? "...." : ""; 
                // $value->username_title  = substr($value->username_title, 0, $strLengtMsg ).$moreStr;

                // $dataTemp[$countTemp]->message   =  str_replace("\n", " ", $dataTemp[$countTemp]->message);
                // $dataTemp[$countTemp]->message   =  substr($dataTemp[$countTemp]->message, 0, $strLengtMsg ).$moreStr;
                $dataTemp[$countTemp]->message   =  str_replace("\n", " ", $dataTemp[$countTemp]->message).$moreStr;
                
                $dataTemp[$countTemp]->dateParse =  $this->getDate($value->created);

                $countTemp++;
            }

        }
        echo json_encode(["data" => $dataTemp,"dataNew" => $dataNew ]);
    } 

    function parsingTrialNumber($str)
    {
        $length = "********".substr($str,9);
        // echo $length;
        return $length;
    }

    public function search()
    {
        header('Content-Type: application/json');
        $where = [
            "hotline_private.group_hotline" =>$this->input->post('group_hotline',TRUE),
            "flag_status >" => "2",
        ];
        $where_like = [
            "message" => $this->input->post('search',TRUE),
        ];
        $datas          =   $this->Hotline_private_model->group_json_search($where,$where_like);
        $dataNew        =   []  ;
        $dataTemp       =   []  ;
        $countTemp      =   0   ;
        $strLengtLabel  =   13  ;
        $strLengtMsg    =   30  ;

        foreach ($datas as $key => $value) {
            if (empty($value->customer_title) ) {
                $value->customer_title      = "+".$value->customer_phone;
                $value->username_title_sort = "+".$value->customer_phone;
                $value->username_num        = "";
                // $dataTemp[$countTemp]->username = "312";
            } else {
                $value->full_name       = $value->customer_title;
                $moreStr = strlen($value->customer_title) > $strLengtLabel ? "..." : ""; 
                // $value->username_title_sort  = substr($value->username_title, 0, $strLengtLabel ).$moreStr; 
                $value->username_title_sort  = $value->username_title_sort.$moreStr; 
                $value->username_num    = "+".$value->customer_phone;
                // $value->username = $value->username." +".$value->customer_phone."";
            }

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


                $moreStr = strlen($dataTemp[$countTemp]->message) >= $strLengtMsg ? "...." : ""; 
                // $value->username_title  = substr($value->username_title, 0, $strLengtMsg ).$moreStr;

                // $dataTemp[$countTemp]->message   =  str_replace("\n", " ", $dataTemp[$countTemp]->message);
                // $dataTemp[$countTemp]->message   =  substr($dataTemp[$countTemp]->message, 0, $strLengtMsg ).$moreStr;
                $dataTemp[$countTemp]->message   =  str_replace("\n", " ", $dataTemp[$countTemp]->message).$moreStr;
                
                $dataTemp[$countTemp]->dateParse =  $this->getDate($value->created);

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

    function getDevices($group_hotline)
    {
        $dataTemp = $this->ManageHotline_model->get_all_where(["phone_number" => $group_hotline]);
        // print_r($dataTemp);
        if ($dataTemp) {
            echo json_encode([
                "code" => "success",
                "data"  => [
                            "device_name" => $dataTemp[0]->device_name
                            ],
                "message" => "",
            ]);die();
        } else {
            echo json_encode([
                "code" => "error",
                "message" => "Record Not Found",
            ]);die();
        }
        
    }

    public function detail()
    {
        header('Content-Type: application/json');
         $whereArr = array(
            'customer_phone'    => $this->input->post('customer_phone',TRUE),
            'hotline_private.group_hotline'     => $this->input->post('group_hotline',TRUE),
        );
        $limit 		= $this->input->post('limit') ?? 10;
        $startFrom = 0;
        if ($this->input->post('start',TRUE) >= 0) {
        	$startFrom 	= $this->input->post('start',TRUE);
        } else {
        	$limit = $this->input->post('start',TRUE) + $limit;
        }

        if (!empty($this->input->post('search_id',TRUE))) {
            $whereGetId = [
                // 'customer_phone'    => $this->input->post('customer_phone',TRUE),
                // 'hotline.group_hotline'     => $this->input->post('group_hotline',TRUE),
                'id'                  => $this->input->post('search_id',TRUE),
            ];
            $limitQuery = $this->Hotline_private_model->getQueryNumber($whereArr, $this->input->post('search_id',TRUE)); 
            // echo print_r($limitQuery);
            $limitSearchData = $this->Hotline_private_model->getRowNumber($limitQuery,$whereGetId, $this->input->post('search_id',TRUE));
            $limit = $limitSearchData->rowNumber + 3;
            $limitTemp = $limit-13 ;
            $startFrom = ($limitTemp >= 0) ? $limitTemp : 0;
            // echo print_r($limitSearchData);
            // die();
        }
        $datas =  $this->Hotline_private_model->detail_list($whereArr,$startFrom,'hotline_private',$limit);
        $count =  $this->Hotline_private_model->count_all($whereArr);
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
                    $datas[$key]->document_name = base_url("API/DirectLink/file/")."document/".$value->document_name;
                    $datas[$key]->extension 	= pathinfo($value->document_name, PATHINFO_EXTENSION);
                    // /$datas[$key]->image 	= base_url('assets/foto_wa')."/".$value->image_name;
                }

            }
            $datas[$key]->_idUser = $value->user_phone ?? $value->customer_phone;
        }
        echo json_encode(["data" => $datas,"counter" =>$count,"startFrom" => $startFrom,"startNext" => $startFrom+$limit+1,"limit"=>$limit, "edit_contact" => checking_akses("edit_contact")]);
    } 
    

    public function send_private() 
    {
        // $this->Loging("send_wa_milis" , ["POST"=>$_POST]);

        $data = array(
            // 'header_id' => $this->input->post('header_id',TRUE),
            'from_num' => $this->config->item('keys')[0]['numbers'],
            'dest_num' => $this->input->post('noPhone',TRUE),
            'message_id' => $this->input->post('message_id',TRUE),
            'message_text' => $this->input->post('message',TRUE),
            'status' => $this->input->post('status',TRUE),
            'created' => date("Y-m-d H:i:s"),
            'createdby' => "API",
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => "API",
        );

        
        // $insHotline = array(
        //         'customer_phone' => $this->input->post('noPhone',TRUE),
        //     'message'       => $this->input->post('message',TRUE),
        //     'created'       => date("Y-m-d H:i:s"),
        //     'message_id'    => "1",
        //     'group_hotline' => $this->input->post('group_hotline',TRUE),
        //     'createdby'     => $this->session->userdata('email'),
        //     'user_phone'    => $this->session->userdata('phone'),
        //     'flag_status'   => "5",
        // );
        // $this->Inbox_model->insertHotline($insHotline);

        $insHotline = array(
            'created'           => date("Y-m-d H:i:s"),
            'createdby'         => $this->session->userdata('email'),
            'customer_phone'    => $this->input->post('noPhone',TRUE),
            'user_phone'        => $this->session->userdata('phone'),
            'message'           => $this->input->post('message',TRUE),
            'group_hotline'     => $this->input->post('group_hotline',TRUE),
            'flag_status'       => "4",
        );
        $this->Inbox_model->insertHotlinePrivate($insHotline);

        $insHotline['customer_username']    = "";
        $insHotline['customer_title']       = "";
        $insHotline['user_send_username']   = $this->session->userdata('full_name');
        $insHotline['user_send_title']      = $this->session->userdata('full_name');
        $insHotline['user_send_phone']      = $this->session->userdata('phone');
        $insHotline['type']     = "";
        $insHotline['image']    = "";
        $insHotline['imageUrl'] = "";
        $insHotline['file']     = "";
        $insHotline['fileUrl']  = "";
        $insHotline['video']    = "";
        $insHotline['videoUrl'] = "";
        
        $insHotline['destination'] = "inbox";
        // $insHotline['username'] = $this->session->userdata('full_name');
        $this->sendSocket($insHotline);
        $this->sendFCM($insHotline);

        echo json_encode([
            "code" => "success",
            "message" => "Create Record Success",
        ]);die();
        // }
    }


    function sendFCM($data){
        $data = json_encode([
            // "to" => 'egVqgonXXHM:APA91bEPXbTBW2iW16UQHQOyXI0f-Yfb6HcRX6Q2_7BYs6vZ_Y7JBzUmH8JDPITPRSyQt-t0JKGKRK64dqgvBFAEuXjfw4hKekS6MDZmRFEPjC9AfwZZBg0ZfDJI-z3PWQ3N7JK8P6WJ',
            // "to" => 'cYhsZSacooo:APA91bEWIS4j_C1SXLMx1uLQaw_0bzzE-SCiS6j2u7ruWnT-REQo20yNhy0pbqYUD76-KwBclQTB7K475SzgYxRyxRfqfjq3P7WEgPSmy0vDJ8wCVojBVvpZiZjwtZkHZXQ0LOGV9Pxo',
            
            'to'=>'/topics/'.$data['group_hotline'],
            'priority'=>'high',
            "notification" => [
                "body" => $data['message'],
                "title" => $data['customer_phone'],
                "icon" => "ic_launcher"
            ],
            "data" => $data
        ]);
        //FCM API end-point
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AIzaSyC3rX7bePdEZnRLZJ_asuCdBEDqp6BrFUI';
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
        );
        //CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        // if ($result === FALSE) {
        //     die('Oops! FCM Send Error: ' . curl_error($ch));
        // }
        $this->Loging("send_fcm" , ["result"=>$result]);
        curl_close($ch);
    }
    function sendSocket($data){
        $client = new Client(new Version1X('http://149.129.222.185:8881'));
        $client->initialize();

        // send message to connected clients
        $client->emit('post_key', ['socket_session' => '', 'id_account' => $data['customer_phone'], 'datas' => json_encode($data)]);
        $client->emit('sendWA', ['socket_session' => '', 'id_account' => $data['group_hotline'], 'datas' => json_encode($data)]);
        $client->close();
    }
    

    public function json() {
        header('Content-Type: application/json');
        echo $this->Hotline_private_model->json();
    }

    public function read($id) 
    {
        $row = $this->Hotline_private_model->get_by_id($id);
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

            $this->Hotline_private_model->insert($data);
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

            $this->Hotline_private_model->update($this->input->post('id', TRUE), $data);
            echo json_encode([
                "code" => "success",
                "message" => "Update Record Success",
            ]);die();
        }
    }
    
    public function delete_action($id) 
    {
        $row = $this->Hotline_private_model->get_by_id($id);

        if ($row) {
            $this->Hotline_private_model->delete($id);
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
        $dataHotline  = $this->ManageHotlineMember_model->get_all_where(['user_id' => $this->session->userdata('id_users')]);

        if ($dataHotline) {
            $row = [];
            foreach ($dataHotline as $key => $value) {
                $dataTemp = $this->ManageHotline_model->get_by_where(['phone_number' =>$value->group_number]);
                $dataTemp->hotline_name = $dataTemp->device_name ;
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