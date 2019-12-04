<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Outbox extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');
        // $this->load->model('Hotline_model');
        $this->load->library('form_validation');        
	    $this->load->library('datatables');
        $this->load->model('Send_message_detail_model');
        $this->load->model('Milis_member_model');
        $this->load->model('Milis_model');
        $this->load->model('ManageHotline_model');
        $this->config->load('apiwha');
        $this->config->load('companyProfile');
        header('Content-Type: application/json');    
        $this->startRes = time();
        $this->apiToken     = "";
        $this->url          = $this->config->item('APIWeb');
        $this->client       = new GuzzleHttp\Client();
        $this->load->model('Milis_member_model');
        $this->load->model('Hotline_model');
        $this->load->model('Contact_model');
        $this->load->model('Inbox_model');

    }
 
    public function index()
    {
        echo "43312";
    } 
    public function getToken($token)
    {
        // $token = "2iNvy9zUUVSwMXSO71SIvdNwjE2c7DrfV6Kn3tCRcOvrkMnvl74kraCUbhZAHZZO";
        $encode = $this->base64url_encode($token);
        $decode = $this->base64url_decode($encode);
        echo "token ori \n".$token;
        echo "<br>\n";
        echo "token encode : ".$encode;
        echo "\n<br>";
        echo "token decode\n".$decode;

    } 

    function base64url_encode($data) {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function base64url_decode($data) {
      $decode =  base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));

      if (!preg_match('/^[\w.-]+$/', $decode)) {
          return false;
      } else {
        return $decode;
      }
    }

    function sendMessage()
    {
        try {
            $this->_rules();
            if ($this->form_validation->run() == FALSE) {
                echo json_encode([
                    "result"    => "error",
                    "code"      => "502",
                    "message"   => "invalid parameter",
                    "form_error" => $this->form_validation->error_array(),
                ]);die();
            } elseif ($this->checkHeader() == FALSE) {
                echo json_encode([
                    "result" => "error",
                    "code"  => "501",
                    "message" => "invalid Authorization token",
                ]);die();
            } else {
                $getToken = $this->checkValidHeader();
                if ($getToken == FALSE) {
                    echo json_encode([
                        "result"    => "error",
                        "code"      => "503",
                        "message"   => "Authorization token error",
                    ]);die();
                } else {

                    $resultMsg = $this->sendMessageApi(
                        $this->input->post('phone',TRUE),
                        $this->input->post('message',TRUE),
                        $getToken->phone_number
                    );
                        // // echo $resultMsg['data']['message']['status'];die();
                        // if (!empty($resultMsg['data']['message']['status'])) {
                        //     unset($resultMsg['data']['message']['status']);
                        // }

                    echo json_encode([
                        "result"    => "success",
                        "code"      => "200",
                        "message"   => "Create Record Success",
                        "data"      => $resultMsg['data']['message'],
                    ]);die();
                }
            }
            
        } catch (Exception $e) {
            echo json_encode([
                "result"    => "error",
                "code"      => "500",
                "message"   => "Unknown error",
            ]);die();
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


    public function sendMessageApi($number,$message, $from_num) {
       $this->Loging("api_outbox_response_wablas" , [
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
                                                'type'      => ""
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
                'from_num' => $from_num,
                'dest_num' => $value['phone'],
                'message_id' => $value['id'],
                'message_text' => $value['text'],
                'status' =>     $value['status'],
                'created' => date("Y-m-d H:i:s"),
                'createdby' => "API_Outbox",
                'updated' => date("Y-m-d H:i:s"),
                'updatedby' => "API_Outbox",
                );
                $this->Send_message_detail_model->insert($data);
                if ($this->input->post('save_hotline',TRUE) == "1" || $this->input->post('save_hotline',TRUE) == "Y") {
                    $insHotline = array(
                        'customer_phone' => $value['phone'],
                        'message'       => $value['text'],
                        'created'       => date("Y-m-d H:i:s"),
                        'message_id'    => $value['id'],
                        'group_hotline' => $from_num,
                        'createdby'     => "API_OUTBOX",
                        'flag_status'   => "5",
                    );
                    $this->Inbox_model->insertHotline($insHotline);
                }
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

    function checkHeader(){
        $token = $this->input->get_request_header('Authorization', TRUE);
        if (empty($token)) {
           return FALSE;
        } 
        else {
            $decodeToken = $this->base64url_decode($token);
            if($decodeToken  == FALSE){
               return FALSE;
            }
            $this->apiToken = $decodeToken;
            return $token;
        }
    }

    function checkValidHeader(){
        // echo  $this->apiToken;die();
        $where = [
                    "token" => $this->apiToken
                ];
        $dataHotline = $this->ManageHotline_model->get_by_where($where);
        if (!empty($dataHotline)) {
            return $dataHotline;
        }
        return FALSE;
    }

    function _rules() 
    {
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');

        $this->form_validation->set_rules('message', 'message', 'trim|required');
        // $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Hotline.php */
/* Location: ./application/controllers/Hotline.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-15 11:41:24 */
/* http://harviacode.com */