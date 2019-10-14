<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;
class Notification extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');

        // $this->load->model('msuser_model');   
        $this->load->model('notification_model');  
        $this->load->model('Milis_member_model');
        $this->load->model('ManageHotline_model');
        // $this->load->model('company_model');  
        $this->url          = "";
        $this->apiToken     = "";
        $this->startRes     = time();
        $this->client       = new GuzzleHttp\Client();
        $this->load->library('wakitalib');

    }
 
    public function index()
    {
      echo "dawdwa 43312";
      

    } 


    public function newNotif()
    {
        $dataParam  = [
            "pid"           =>  $this->wakitalib->get_pid_id('notifications',"TN",'pid',1),
            "general"       =>  $this->input->post('general',TRUE),
            "group_hotline" =>  $this->input->post('group_hotline',TRUE),
            "private_chat"  =>  $this->input->post('private_chat',TRUE),
            "title"         =>  $this->input->post('title',TRUE), 
            "description"   =>  $this->input->post('description',TRUE),
            "created"       =>  date("Y-m-d H:i:s"),
            "createdby"     =>  "API_Notification",

        ]; 



        $this->notification_model->insert($dataParam);
        if (!empty($dataParam['group_hotline'])) {
            $dataHotline = $this->ManageHotline_model->get_by_where(['phone_number' => $dataParam['group_hotline']]);
            // echo print_r($dataHotline);die();
            $memberHotlineStr   = $this->parsingNum($dataHotline->device_id);
            $dataMsg            = $dataParam['description'];
            $this->apiToken     = $dataHotline->token;
            // $this->sendMessage($dataMilis,$dataMsg);
            echo json_encode(['status'=> '1','dataPhone' => $memberHotlineStr]);
            // echo print_r($memberHotlineStr);   
        }

        // echo print_r($dataHotline);   
        // echo print_r($dataParam);   

    } 

    public function sendMessage($number,$message, $type = 'random') {
       $this->Loging("Notification_sendMessage" , [
                                                        "number"=>$number,
                                                        "message"=>$message,
                                                    ]);
       
       try {
            $response = $this->client->request( 'POST', 
                                           $this->url."/send-message", 

                                          [ 
                                            'headers' => [
                                                                'Accept' => 'application/json',
                                                                'Authorization' => $this->apiToken,
                                                            ],
                                            'form_params' 
                                                => [
                                                // 'phone'     => '6285693784939',
                                                'phone'     => $number,
                                                'message'   => $message,
                                                'type'      => $type
                                            ] 
                                          ]
                                        );
            #guzzle repose for future use
            $status_code = $response->getStatusCode(); // 200
            // $response = $response->getReasonPhrase(); // OK
            // echo $response->getProtocolVersion(); // 1.1
            $body =  json_decode($response->getBody(),true);

            $dataInsert = [
                'status' => $body['status'],
                'message' => $body['message'],
                'quota'    => $body['data']['quota'],
                'status_code' => $status_code,
                // 'response' => $response,
                'created' => date("Y-m-d H:i:s"),
                'createdby' => $this->session->userdata('email'),
            ];
            // echo print_r($dataInsert);die();
            $id = $this->Send_message_detail_model->insert_header($dataInsert);
            $this->Loging("api_whatsapp_response_wablas" , ['body' => $body,'status_code' => $status_code]);
            
            foreach ($body['data']['message'] as $key => $value) {
                $data = array(
                    'header_id' => $id,
                    'from_num' => $this->config->item('keys')[0]['numbers'],
                    'dest_num' => $value['phone'],
                    'message_id' => $value['id'],
                    'message_text' => $value['text'],
                    'status' =>     $value['status'],
                    'created' => date("Y-m-d H:i:s"),
                    'createdby' => $this->session->userdata('email'),
                    'updated' => date("Y-m-d H:i:s"),
                    'updatedby' => $this->session->userdata('email'),
                );
                $this->Send_message_detail_model->insert($data);
            }
            // echo json_encode($body);
            return $body;
          } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            // print_r($responseBodyAsString);
          }

        return false;
    }

    function parsingNum($device_code){
        $dataMilis = $this->Milis_member_model->getAllCustomWebhook($device_code);
        $resultArr = "";
        foreach ($dataMilis as $key => $value) {
            $resultArr[] = $value->phone;
        }

        return implode(",", $resultArr);
    }

    


    public function checkStatus()
    {

      // echo $this->input->post('email',TRUE);
        $where = [];

        $data  = $this->hotline_model->get_all();
        // echo print_r($data);
        foreach ($data as $key => $value) {
            if ($result= $this->checkUrl($value)) {
                echo print_r($result);
                # code...
            }

        }
        if (!empty($data)) {
            // $this->url = $data->url_server;
            $response = [
                'response' => 'success',
                'message'=>'data found ', 
                'data' => $data];
        } else {
            $response = array(
                'response' => 'error',
                'message'=>'user not found '
             );
        }

        $this->Loging('cron_check_status_end',"ok");
        // echo json_encode($response);


    } 

    function Loging($name,$param){

      $fullpath=FCPATH.'Log/'.date('Y').'/'.date('m').'/'.date('d');
      $filepath = $fullpath.'/'.$name.'.txt';
      if (!is_dir($fullpath)) {
        mkdir($fullpath, 0755, TRUE);
      }
      $saveData = [
        "time"  => date('H:i:s'),
        "timeRes" => time() - $this->startRes,
        "param" => $param
      ];

      file_put_contents($filepath,json_encode($saveData).PHP_EOL, FILE_APPEND);
    }

}
