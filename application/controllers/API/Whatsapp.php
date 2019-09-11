<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class Whatsapp extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // is_login();
        
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Send_message_detail_model');
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->config->load('apiwha');
        $this->load->model('Inbox_model');

        $this->apiToken     = "2iNvy9zUUVSwMXSO71SIvdNwjE2c7DrfV6Kn3tCRcOvrkMnvl74kraCUbhZAHZZO";
        // $this->wablasClient = new WablasClient($apiToken);
        $this->url          = 'https://wablas.com/api';
        $this->client       = new GuzzleHttp\Client();
        $this->load->model('Milis_member_model');
        $this->load->model('Hotline_model');
        $this->startRes = time();

    }

    public function index()
    {
        echo "hello";
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Send_message_detail_model->json();
    }

    public function read($id) 
    {
        $row = $this->Send_message_detail_model->get_by_id($id);
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
    public function webhook() 
    {
        $data = array(
            'message_id' => $this->input->post('id',TRUE),
            'fromMe' => $this->input->post('fromMe',TRUE),
            'pushName' => $this->input->post('pushName',TRUE),
            'phone' => $this->input->post('phone',TRUE),
            'message' => $this->input->post('message',TRUE),
            'timestamp' => $this->input->post('timestamp',TRUE),
            'receiver' => $this->input->post('receiver',TRUE),
            'groupId' => $this->input->post('groupId',TRUE),
            'image' => $this->input->post('image',TRUE),
            'file' => $this->input->post('file',TRUE),
            'created' => date("Y-m-d H:i:s"),
            'createdby' => "API_",
        );

            $this->Inbox_model->insert($data);
            $dataHotline = $this->Inbox_model->get_hotline($data['phone']);
            if (!empty($dataHotline)) {
                if ($dataHotline->flag_status == 1) {
                    $messageArr = explode(" ", $this->input->post('message',TRUE));
                    $name  = end($messageArr);
                    // echo end($messageArr);die();
                    $insHotline = array(
                        'customer_phone' => $this->input->post('phone',TRUE),
                        'message'       => $this->input->post('message',TRUE),
                        'created'       => date("Y-m-d H:i:s"),
                        'message_id'	=> $data['message_id'],
                        'group_hotline'	=> "huawei_cloud",
                        'createdby'     => "API_WABLAS",
                        'flag_status'   => "2",
                    );
                    $this->Inbox_model->insertHotline($insHotline);
                    echo "Terimakasih infonya ".$name."\napa yang bisa kami bantu ?";

                } elseif ($dataHotline->flag_status == 2) {
                    $insHotline = array(
                        'customer_phone' => $this->input->post('phone',TRUE),
                        'message'       => $this->input->post('message',TRUE),
                        'created'       => date("Y-m-d H:i:s"),
                        'message_id'	=> $data['message_id'],
                        'group_hotline'	=> "huawei_cloud",
                        'createdby'     => "API_WABLAS",
                        'flag_status'   => "3",
                    );
                    $this->Inbox_model->insertHotline($insHotline);
                    // 
                    $dataMilis = $this->parsingNum('1');
                    $dataMsg   = $this->parsingMsg($data['phone']);
                    $this->sendMessage($dataMilis,$dataMsg);


                    // echo "Layanan apa yang ingin di tanyakan";

                } else{
                	$insHotline = array(
                        'customer_phone' => $this->input->post('phone',TRUE),
                        'message'       => $this->input->post('message',TRUE),
                        'created'       => date("Y-m-d H:i:s"),
                        'message_id'	=> $data['message_id'],
                        'group_hotline'	=> "huawei_cloud",
                        'createdby'     => "API_WABLAS",
                        'flag_status'   => "4",
                    );
                    $this->Inbox_model->insertHotline($insHotline);

                    $dataHotline = $this->Inbox_model->get_hotline($data['phone']);
                    $insHotline['username'] = "Customer";
                    $this->sendSocket($insHotline);
                    $this->sendFCM($insHotline);

                }           
                // echo  print_r($dataHotline);die();
            } else {

                // if ($data['message'] == "HALO SAYA CUSTOMER") {
                    // customer_phone
                    $insHotline = array(
                        'customer_phone' => $this->input->post('phone',TRUE),
                        'message'       => $this->input->post('message',TRUE),
                        'created'       => date("Y-m-d H:i:s"),
                        'message_id'	=> $data['message_id'],
                        'group_hotline'	=> "huawei_cloud",
                        'createdby'     => "API_WABLAS",
                        'flag_status'   => "1",
                    );
                    $this->Inbox_model->insertHotline($insHotline);
                    echo "Selamat pagi, kami dari totech \nMohon info nama anda : ";
                // }

            }
            // echo json_encode([
            //     "code" => "success",
            //     "message" => "Create Record Success",
            // ]);die();
    }

    function sendFCM($data){
        $data = json_encode([
            // "to" => 'egVqgonXXHM:APA91bEPXbTBW2iW16UQHQOyXI0f-Yfb6HcRX6Q2_7BYs6vZ_Y7JBzUmH8JDPITPRSyQt-t0JKGKRK64dqgvBFAEuXjfw4hKekS6MDZmRFEPjC9AfwZZBg0ZfDJI-z3PWQ3N7JK8P6WJ',
            // "to" => 'cYhsZSacooo:APA91bEWIS4j_C1SXLMx1uLQaw_0bzzE-SCiS6j2u7ruWnT-REQo20yNhy0pbqYUD76-KwBclQTB7K475SzgYxRyxRfqfjq3P7WEgPSmy0vDJ8wCVojBVvpZiZjwtZkHZXQ0LOGV9Pxo',
            
            'to'=>'/topics/huawei_cloud',
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
        $client->emit('post_key', ['socket_session' => '', 'id_account' => '6285693784939', 'datas' => json_encode($data)]);
        $client->emit('sendWA', ['socket_session' => '', 'id_account' => 'huawei_cloud', 'datas' => json_encode($data)]);
        $client->close();
    }
    function parsingNum($id){
        $dataMilis = $this->Milis_member_model->getAllCustom("1");
        $resultArr = "";
        foreach ($dataMilis as $key => $value) {
            $resultArr[] = $value->phone;
        }

        return implode(",", $resultArr);
    }
    function parsingMsg($phone){
        $dataMsg  = $this->Hotline_model->get_all_where(["customer_phone" => $phone]);
        foreach ($dataMsg as $key => $value) {
            $resultArr[] = $value->message;
        }

        return implode("\n", $resultArr);
    }

    public function send_wa() 
    {
        $this->_rules();
        if ($this->input->post('key',TRUE) != $this->config->item('keys')[0]['apikeys']) {
            echo json_encode([
                "code" => "error",
                "message" => "invalid key",
            ]);die();
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                "code" => "error",
                "message" => validation_errors(),
                "form_error" => $this->form_validation->error_array(),
            ]);die();
        } else {
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


            $this->sendMessage($data['dest_num'],$data['message_text']);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
            ]);die();
        }
    }


    public function send_wa_milis() 
    {
         $this->Loging("send_wa_milis" , ["POST"=>$_POST]);
        // $this->_rules();
        // if ($this->input->post('key',TRUE) != $this->config->item('keys')[0]['apikeys']) {
        //     echo json_encode([
        //         "code" => "error",
        //         "message" => "invalid key",
        //     ]);die();
        // }

        // if ($this->form_validation->run() == FALSE) {
        //     echo json_encode([
        //         "code" => "error",
        //         "message" => validation_errors(),
        //         "form_error" => $this->form_validation->error_array(),
        //     ]);die();
        // } else {
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


            if ($dataResult = $this->sendMessage($data['dest_num'],$data['message_text'])) {
                 $this->Loging("api_whatsapp_send_wa_milis_message" , [
                                                        "dataResult"=>$dataResult,
                                                    ]);
            	$insHotline = array(
                    'customer_phone' => $this->input->post('noPhone',TRUE),
                    'message'       => $this->input->post('message',TRUE),
                    'created'       => date("Y-m-d H:i:s"),
                    'message_id'	=> $dataResult['data']['message'][0]['id'],
                    'group_hotline'	=> $this->input->post('group_hotline',TRUE),
                    'createdby'     => $this->session->userdata('email'),
                    'user_phone'     => $this->session->userdata('phone'),
                    'flag_status'   => "5",
                );
                $this->Inbox_model->insertHotline($insHotline);

                $insHotline['username'] = $this->session->userdata('full_name');
                $this->sendSocket($insHotline);


            };
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
            ]);die();
        // }
    }
    

    
    public function sendMessage($number,$message, $type = 'random') {
       $this->Loging("api_whatsapp_1st_response_wablas" , [
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

	public function _rules() 
    {
    	// $this->form_validation->set_rules('header_id', 'header id', 'trim|required');
	    // $this->form_validation->set_rules('from_num', 'from num', 'trim|required');
	    $this->form_validation->set_rules('noPhone', 'No Phone', 'trim|required');
	    $this->form_validation->set_rules('key', 'key', 'trim|required');
	    // $this->form_validation->set_rules('message_id', 'message id', 'trim|required');
	    $this->form_validation->set_rules('message', 'message text', 'trim|required');
	    // $this->form_validation->set_rules('status', 'status', 'trim|required');

	    $this->form_validation->set_rules('id', 'id', 'trim');
	    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Send_message.php */
/* Location: ./application/controllers/Send_message.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-08 15:45:08 */
/* http://harviacode.com */