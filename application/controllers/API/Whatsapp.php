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
        // checking_login_api();
        
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Send_message_detail_model');
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->config->load('apiwha');
        $this->config->load('companyProfile');
        $this->load->model('Inbox_model');
        $this->load->model('Milis_model');
        $this->load->model('ManageHotline_model');
        $this->load->model('ManageUserLevel_model');

        // $this->apiToken     = "2iNvy9zUUVSwMXSO71SIvdNwjE2c7DrfV6Kn3tCRcOvrkMnvl74kraCUbhZAHZZO";
        // $this->wablasClient = new WablasClient($apiToken);
        $this->apiToken     = "";
        $this->company_pid   = "";
        $this->group_hotline   = "";
        $this->url          = $this->config->item('APIWeb');
        $this->client       = new GuzzleHttp\Client();
        $this->load->model('Milis_member_model');
        $this->load->model('Hotline_model');
        $this->load->model('Contact_model');
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
        $this->Loging("webhook_start" , $_POST);
        $this->company_pid = $this->input->post('company_pid',TRUE);
        $this->group_hotline = $this->input->post('receiver',TRUE);
        $data = array(
            'pid'           => $this->wakitalib->get_pid_id('inbox_'.$this->company_pid,"WAIN",'pid',1),
            'message_id'    => $this->input->post('id',TRUE),
            'fromMe'        => $this->input->post('fromMe',TRUE),
            'pushName'      => $this->input->post('pushName',TRUE),
            'company_pid'   => $this->company_pid,
            'phone'         => $this->input->post('phone',TRUE),
            'message'       => $this->input->post('message',TRUE),
            'timestamp'     => $this->input->post('timestamp',TRUE),
            'receiver'      => $this->input->post('receiver',TRUE),
            'groupId'       => $this->input->post('groupId',TRUE),
            'image'         => $this->input->post('image',TRUE),
            'file'          => $this->input->post('file',TRUE),
            'created'       => date("Y-m-d H:i:s"),
            'createdby'     => "API_",
        );

        $dataContat = [
            "name_wa"       => $data['pushName'],
            "name_replace"  => $data['pushName'],
            "phone"         => $data['phone'],
            'group_hotline' => $data['receiver'],
            'created'       => date("Y-m-d H:i:s"),
            'createdby'     => "API_webhook",
            'updated'       => date("Y-m-d H:i:s"),
            'updatedby'     => "API_webhook",
        ];

        // $ext = pathinfo($path, PATHINFO_EXTENSION);

        if (!empty($this->input->post('groupId',TRUE) )) {
            if ($this->input->post('groupId',TRUE) != "62") {
                exit();
            }
        }
        if (!empty($this->company_pid)) {
            $tableName = $this->Inbox_model->getTable();
            $this->Inbox_model->setTable($tableName."_".$this->company_pid);
            $tableName = $this->Hotline_model->getTable();
            $tableName .= "_".$this->company_pid;
            $this->Hotline_model->setTable($tableName);
        
        }
        $this->Inbox_model->insert($data);
        $customer_name  = $this->Contact_model->insert_update($dataContat);
        $dataHotline    = $this->Hotline_model->get_by_where(["customer_phone" =>$data['phone']]);
        // echo print_r($dataHotline);die();
        if (!empty($dataHotline)) {
            if ($dataHotline->flag_status == 1) {   
            //     $messageArr = explode(" ", $this->input->post('message',TRUE));
            //     $name  = end($messageArr);
            //     // echo end($messageArr);die();
            //     $insHotline = array(
            //         'customer_phone' => $this->input->post('phone',TRUE),
            //         'message'       => $this->input->post('message',TRUE),
            //         'created'       => date("Y-m-d H:i:s"),
            //         'message_id'	=> $data['message_id'],
            //         'group_hotline'	=> $data['receiver'],
            //         'createdby'     => "API_WABLAS",
            //         'flag_status'   => "2",
            //     );
            //     $this->Inbox_model->insertHotline($insHotline);
            //     echo "Terimakasih infonya \napa yang bisa kami bantu ?";

            // } elseif ($dataHotline->flag_status == 2) {
                $insHotline = array(
                    'pid'           => $this->wakitalib->get_pid_id('hotline_'.$this->company_pid,"HOTIN",'pid',1),
                    'customer_phone' => $this->input->post('phone',TRUE),
                    'message'       => $this->input->post('message',TRUE),
                    'company_pid'   => $this->company_pid,
                    'created'       => date("Y-m-d H:i:s"),
                    'message_id'	=> $data['message_id'],
                    'group_hotline'	=> $data['receiver'],
                    'createdby'     => "API_WABLAS",
                    'flag_status'   => "3",
                );
                $this->Inbox_model->insertHotlineCustom($insHotline,'hotline_'.$this->company_pid);
                // 
                $dataMilis = $this->parsingNum($data['receiver']);
                $dataMsg   = $this->parsingMsg($data['phone']);
                $this->apiToken  = $this->ManageHotline_model->get_token($data['receiver']);
                $this->sendMessage($dataMilis,$dataMsg);


                // $this->sendSocket($insHotline);
                $insHotline['message'] = "You have new customer";
                $this->sendFCM($insHotline);

                // echo "Layanan apa yang ingin di tanyakan";

            } else{
            	$insHotline = array(
                    'pid'           => $this->wakitalib->get_pid_id('hotline_'.$this->company_pid,"HOTIN",'pid',1),
                    'customer_phone' => $this->input->post('phone',TRUE),
                    'message'       => $this->input->post('message',TRUE),
                    'company_pid'   => $this->company_pid,
                    'created'       => date("Y-m-d H:i:s"),
                    'message_id'	=> $data['message_id'],
                    'group_hotline'	=> $data['receiver'],
                    'createdby'     => "API_WABLAS",
                    'image_name'    => $this->input->post('image',TRUE),
                    
                    // 'document_name' => $this->input->post('file',TRUE),
                    'flag_status'   => "4",
                );
                if (!empty($this->input->post('file'))) {

                    $ext = pathinfo($this->input->post('file',TRUE), PATHINFO_EXTENSION);
                    if(in_array($ext, ["mp4" ,"mpeg"] )){
                        $insHotline['video_name']       = $this->input->post('file');
                    } else {
                        $insHotline['document_name']    = $this->input->post('file');
                    }
                }
                $this->Inbox_model->insertHotlineCustom($insHotline,'hotline_'.$this->company_pid);
                // $this->Inbox_model->insertHotline($insHotline);
                // $tableName = $this->Hotline_model->getTable();
                // $tableName .= "_".$this->company_pid;
                // $this->Hotline_model->setTable($tableName);

                $dataHotline                        = $this->Hotline_model->get_by_where(["customer_phone" => $data['phone']]);
                $insHotline['customer_username']	= "Customer - " .  $customer_name;
                $insHotline['customer_title']   	= $customer_name;
                $insHotline['user_send_username']	= "Customer - " .  $customer_name;
                $insHotline['user_send_title']   	= $customer_name;
                $insHotline['user_send_phone']   	= $insHotline['customer_phone'];
                $insHotline['type'] 	= "";
                $insHotline['image']    = "";
                $insHotline['imageUrl'] = "";
                $insHotline['file']    	= "";
                $insHotline['fileUrl'] 	= "";
                $insHotline['video']    = "";
                $insHotline['videoUrl'] = "";
                // echo "balas";

                if (!empty($this->input->post('image'))) {

                    $ext = pathinfo($this->input->post('image',TRUE), PATHINFO_EXTENSION);
                    $insHotline['type'] = $ext;

                    $insHotline['image']    = $this->input->post('image',TRUE);                 
                    $insHotline['imageUrl'] = base_url("API/DirectLink/file/")."image/".$this->input->post('image',TRUE);                        
                }

                if (!empty($this->input->post('file'))) {
                    $ext = pathinfo($this->input->post('file',TRUE), PATHINFO_EXTENSION);
                    $insHotline['type'] = $ext;
                    if(in_array($ext, ["mp4" ,"mpeg"] )){
                        $insHotline['video']    = $this->input->post('file',TRUE);
                        $insHotline['videoUrl'] = base_url("API/DirectLink/file/")."video/".$this->input->post('file',TRUE);
                    } else {
                        $insHotline['file']    	= $this->input->post('file',TRUE);
                        $insHotline['fileUrl'] 	= base_url("API/DirectLink/file/")."document/".$this->input->post('file',TRUE);
                    }
                    // $insHotline['videoUrl'] = "";

                }

                $insHotline['destination'] = "inbox";

                $this->sendSocket($insHotline);
                $this->sendFCM($insHotline);

            }           
            // echo  print_r($dataHotline);die();
        } else {

            // if ($data['message'] == "HALO SAYA CUSTOMER") {
                // customer_phone
                if ($this->input->post('groupId',TRUE) == "62") {
                    $insHotline = array(
                        'customer_phone' => $this->input->post('phone',TRUE),
                        'message'       => $this->input->post('message',TRUE),
                        'company_pid'   => $this->company_pid,
                        'created'       => date("Y-m-d H:i:s"),
                        'message_id'	=> $data['message_id'],
                        'group_hotline'	=> $data['receiver'],
                        'createdby'     => "API_WABLAS",
                        'flag_status'   => "1",
                    );
                    $this->Inbox_model->insertHotlineCustom($insHotline);

                    $timeStr = $this->parsingTime(date("H"));
                    echo $timeStr.", kami dari ".$this->config->item('wa_company_name')." \nAda yang bisa kami bantu ? ?";
                    # code...
                }
            // }

        }
            // echo json_encode([
            //     "code" => "success",
            //     "message" => "Create Record Success",
            // ]);die();
    }


    public function webhook_track() 
    {
        $this->Loging("webhook_track" , $_POST);

        $this->company_pid = $this->input->post('company_pid',TRUE);
        $this->group_hotline = $this->input->post('receiver',TRUE);
        $data = array(
            'status' => $this->input->post('status',TRUE),
            // 'device_id' => $this->input->post('device_id',TRUE),/
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => "API_webhook",
        );

        $where = [
            'message_id' => $this->input->post('id',TRUE),
        ];
        
        $tableName = $this->Send_message_detail_model->get_table();
        $this->Send_message_detail_model->set_table($tableName."_".$this->company_pid);
        $this->Send_message_detail_model->updateWhere($data,$where);
        // echo 'mantap 2'; 
        $this->Loging("webhook_track_end" , $data);

        // $this->Inbox_model->insert($data);/
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
    function parsingNum($id){
        $dataMilis = $this->Milis_member_model->getAllCustomWebhook($id);
        $resultArr = [];
        foreach ($dataMilis as $key => $value) {
            $resultArr[] = $value->phone;
        }

        return implode(",", $resultArr);
    }
    function parsingMsg($phone){
        $dataMsg  = $this->Hotline_model->get_all_where(["customer_phone" => $phone]);
        foreach ($dataMsg as $key => $value) {
            $resultArr[] = "[".date("m/d H:i",strtotime($value->created))."] +".$value->customer_phone.": ".$value->message;
        }
        $parsing = "You have new customer.\n\n";
        // $parsing .= "Customer chat : \n";
        $parsing .= implode("\n", $resultArr);
        $parsing .= "\n\nPlease check your app.";
        return $parsing;
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


            // $this->apiToken  = $data['receiver'];
            $this->sendMessage($data['dest_num'],$data['message_text']);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
            ]);die();
        }
    }

    function session(){
        echo $this->input->post('company_pid',TRUE);
    }


    public function send_wa_milis() 
    {
        $this->Loging("send_wa_milis" , ["POST"=>$_POST]);
        $this->group_hotline = $this->input->post('group_hotline',TRUE);
        $this->company_pid = $this->session->userdata('company_pid');
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
            'company_pid'   => $this->company_pid,
            'dest_num' => $this->input->post('noPhone',TRUE),
            'message_id' => $this->input->post('message_id',TRUE),
            'message_text' => $this->input->post('message',TRUE),
            'status' => $this->input->post('status',TRUE),
            'created' => date("Y-m-d H:i:s"),
            'createdby' => "API",
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => "API",
        );

        // $customer_name  = $this->Contact_model->insert_update($dataContat);

        $this->apiToken  = $this->ManageHotline_model->get_token($this->input->post('group_hotline',TRUE));
        if ($dataResult = $this->sendMessage($data['dest_num'],$data['message_text'])) {
            $this->Loging("api_whatsapp_send_wa_milis_message" , [
                                                    "dataResult"=>$dataResult,
                                                ]);
            $insHotline = array(
                'pid'           => $this->wakitalib->get_pid("HTOUT"),               
                'company_pid'   => $this->company_pid,
                'customer_phone' => $this->input->post('noPhone',TRUE),
                'message'       => $this->input->post('message',TRUE),
                'created'       => date("Y-m-d H:i:s"),
                'message_id'	=> $dataResult['data']['message'][0]['id'],
                'group_hotline'	=> $this->input->post('group_hotline',TRUE),
                'createdby'     => $this->session->userdata('email'),
                'user_phone'    => $this->session->userdata('phone'),
                'flag_status'   => "5",
            );

            if (!empty($this->company_pid)) {
                $tableName = $this->Inbox_model->getTable();
                $this->Inbox_model->setTable($tableName."_".$this->company_pid);
            }

            $this->Inbox_model->insertHotlineCustom($insHotline,'hotline_'.$this->company_pid);

            $insHotline['customer_username']	= "";
            $insHotline['customer_title']   	= "";
            $insHotline['user_send_username']	= $this->session->userdata('full_name');
            $insHotline['user_send_title']   	= $this->session->userdata('full_name');
            $insHotline['user_send_phone']   	= $this->session->userdata('phone');
            $insHotline['type'] 	= "";
            $insHotline['image']    = "";
            $insHotline['imageUrl'] = "";
            $insHotline['file']    	= "";
            $insHotline['fileUrl'] 	= "";
            $insHotline['video']    = "";
            $insHotline['videoUrl'] = "";
            
            $insHotline['destination'] = "inbox";
            // $insHotline['username'] = $this->session->userdata('full_name');
            $this->sendSocket($insHotline);
            $this->sendFCM($insHotline);


        };
        echo json_encode([
            "code" => "success",
            "message" => "Create Record Success",
        ]);die();
        // }
    }
    

    public function send_img_wa_milis() 
    {
        $this->Loging("send_img_wa_milis" , ["POST"=>$_POST]);
        $this->company_pid      = $this->session->userdata('company_pid');
        $this->group_hotline    = $this->input->post('group_hotline',TRUE);
        
        $data = array(
            // 'header_id' => $this->input->post('header_id',TRUE),
            'from_num' => $this->config->item('keys')[0]['numbers'],
            'number' => $this->input->post('noPhone',TRUE),
            'message_id' => $this->input->post('message_id',TRUE),
            'message' => $this->input->post('message',TRUE),
            'status' => $this->input->post('status',TRUE),
            'type_file' => $this->input->post('type_file',TRUE),
            'created' => date("Y-m-d H:i:s"),
            'createdby' => "API",
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => "API",
        );
        $imgUrl = "";
        $resultUpload['file_name'] = $this->saveIMG($this->input->post('file_img'));
        $data['imageUrl'] = base_url('assets/foto_wa')."/".$resultUpload['file_name'];
        $data['doc_name'] = $resultUpload['file_name'];

        $this->apiToken  = $this->ManageHotline_model->get_token($this->input->post('group_hotline',TRUE));
        // $this->sendImg($data);

        if ($dataResult = $this->sendImg($data)) {
        //      $this->Loging("api_whatsapp_send_wa_milis_message" , [
        //                                             "dataResult"=>$dataResult,
        //                                         ]);
            $insHotline = array(
                'pid'           => $this->wakitalib->get_pid("HTOUT"),   
                'company_pid'   => $this->company_pid,
                'customer_phone' => $this->input->post('noPhone',TRUE),
                'message'       => $this->input->post('message',TRUE),
                'created'       => date("Y-m-d H:i:s"),
                'message_id'    => $dataResult['data']['message'][0]['id'],
                'group_hotline' => $this->input->post('group_hotline',TRUE),
                'createdby'     => $this->session->userdata('email'),
                'image_name'    => $dataResult['data']['message'][0]['image'],
                'user_phone'    => $this->session->userdata('phone'),
                'flag_status'   => "5",
            );

            if (!empty($this->company_pid)) {
                $tableName = $this->Inbox_model->getTable();
                $this->Inbox_model->setTable($tableName."_".$this->company_pid);
            }

            $this->Inbox_model->insertHotlineCustom($insHotline,'hotline_'.$this->company_pid);

            $insHotline['username'] = $this->session->userdata('full_name');
            $this->sendSocket($insHotline);


        };
        $insHotline['imageUrl'] = $data['imageUrl'];
        echo json_encode([
            "code" => "success",
            "message" => "Create Record Success",
            "data"    => $insHotline,
            "imgUrl"  => $data['doc_name'],
        ]);die();
        // }
    }

    public function sendImg($data, $type = 'random') {
        
        $this->Loging("sendDOC" , $data);
       
       try {
        $url =  $this->url."/send-image";
        if ($data['type_file'] == "doc_file") {
            $url =  $this->url."/send-document";
        }
            $response = $this->client->request( 'POST', 
                                          $url, 
                                          [ 
                                            'headers' => [
                                                                // 'Accept' => 'application/json',
                                                                'Authorization' => $this->apiToken,
                                                            ],
                                            'form_params' 
                                                => [
                                                'phone'     => $data['number'],
                                                'caption'   => $data['message'],
                                                'image'     => $data['imageUrl'],
                                                'document'  => $data['imageUrl'],
                                            ] 
                                          ]
                                        );
            #guzzle repose for future use
            $res['status_code'] = $response->getStatusCode(); // 200
            // $response = $response->getReasonPhrase(); // OK
            // echo $response->getBody();die(); // 1.1
            $res['body'] =  json_decode($response->getBody(),true);
            $this->Loging("response_wablas" , $res);

            $tableHeaderName = $this->Send_message_detail_model->get_header_table();
            $this->Send_message_detail_model->set_header_table($tableHeaderName."_".$this->company_pid);


            // echo print_r($dataInsert);die();
            $tableName = $this->Send_message_detail_model->get_table();
            $this->Send_message_detail_model->set_table($tableName."_".$this->company_pid);
            // echo print_r($res['body']);die();

            $dataInsert = [
                'pid'           => $this->wakitalib->get_pid("SMH"),
                'status'        => $res['body']['status'],
                'message'       => $res['body']['message'],
                'quota'         => $res['body']['data']['quota'],
                'status_code'   => $res['status_code'],
                // 'response'   => $response,
                'created'       => date("Y-m-d H:i:s"),
                'createdby'     => $this->session->userdata('email'),
            ];
            // echo print_r($dataInsert);die();
            // $id = $this->Send_message_detail_model->insert_header($dataInsert);
            
            foreach ($res['body']['data']['message'] as $key => $value) {
                $data = array(
                'pid'                  => $this->wakitalib->get_pid("SMD"),
                'header_pid'           => $dataInsert['pid'],
                'header_status'        => $res['body']['status'],
                'header_message'       => $res['body']['message'],
                'header_quota'         => $res['body']['data']['quota'],
                'header_status_code'   => $res['status_code'],
                'from_num'      => $this->group_hotline,
                'dest_num'      => $value['phone'],
                'message_id'    => $value['id'],
                'message_text'  => $value['caption'] ?? "",
                'message_image' => $value['image'] ?? "",
                'status'        => $value['status'],
                'created'       => date("Y-m-d H:i:s"),
                'type'          => $data['type_file'],
                'document_name' => $data['doc_name'],
                'createdby'     => $this->session->userdata('email'),
                'updated'       => date("Y-m-d H:i:s"),
                'updatedby'     => $this->session->userdata('email'),
                );
                $this->Send_message_detail_model->insert($data);
            }
            // echo json_encode($body);
            return $res['body'];
          } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $res['response'] = $e->getResponse();
            $res['responseBodyAsString'] = $res['response']->getBody()->getContents();
            $this->Loging("response_wablas_error" , $res);
            // print_r($responseBodyAsString);
          }

        return false;
    }
    
    public function saveIMG($file64){
        $filename = time().rand(100,999).".png";
        $path = FCPATH."/assets/foto_wa/".$filename;
        file_put_contents($path, base64_decode($file64));

        return $filename;
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
            
            $res['status_code'] = $response->getStatusCode(); // 200
            // $response = $response->getReasonPhrase(); // OK
            // echo $response->getBody();die(); // 1.1
            $res['body'] =  json_decode($response->getBody(),true);
            $this->Loging("response_wablas" , $res);

            $tableHeaderName = $this->Send_message_detail_model->get_header_table();
            $this->Send_message_detail_model->set_header_table($tableHeaderName."_".$this->company_pid);


            // echo print_r($dataInsert);die();
            $tableName = $this->Send_message_detail_model->get_table();
            $this->Send_message_detail_model->set_table($tableName."_".$this->company_pid);

            $dataInsertHeader = [
                'pid'    => $this->wakitalib->get_pid("SMH"),
                'company_pid'   => $this->company_pid,
                'status' => $res['body']['status'],
                'message' => $res['body']['message'],
                'quota'    => $res['body']['data']['quota'],
                'status_code' => $res['status_code'],
                // 'response' => $response,
                'created' => date("Y-m-d H:i:s"),
                'createdby' => $this->session->userdata('email'),
            ];

            // echo print_r($dataInsertHeader);die();


            // $id = $this->Send_message_detail_model->insert_header($dataInsertHeader);
            $this->Loging("api_whatsapp_response_wablas" , ['res' => $res]);
            
            foreach ($res['body']['data']['message'] as $key => $value) {
                $data = array(
                'pid'    => $this->wakitalib->get_pid("SMD"),
                'company_pid'   => $this->company_pid,
                'header_pid'           => $dataInsertHeader['pid'],
                'header_status'        => $res['body']['status'],
                'header_message'       => $res['body']['message'],
                'header_quota'         => $res['body']['data']['quota'],
                'header_status_code'   => $res['status_code'],
                'from_num' => $this->group_hotline,
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

        	return $res['body'];
          } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            // print_r($responseBodyAsString);
          }

        return false;
    }


    public function parsingTime($dateParam) {
        $date =  intval($dateParam);
        $timeSting = "";
        if ($date < 10) {
            $timeSting = "Selamat Pagi";
        } elseif($date < 15){
            $timeSting = "Selamat Siang";
        }  elseif($date < 18){
            $timeSting = "Selamat Sore";
        } else {
            $timeSting = "Selamat Malam";
        }

        return $timeSting;
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