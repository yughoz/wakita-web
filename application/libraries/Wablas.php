<?php (! defined('BASEPATH')) and exit('No direct script access allowed');
/**
 * CodeIgniter Notification Sendgrid and Wablast
 *
 * @package CodeIgniter
 * @author  Reza Fatahillah <fatahillah.reza@gmail.com>
 * @link    https://www.facebook.com/fatahillah.reza
 */

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class Wablas
{
    /**
     * ci instance object
     *
     */
    private $_ci;
    /**
     * Wablas site up, verify and api url.
     *
     */
    const sendgrid_host         = 'smtp.sendgrid.net';
    const sendgrid_port         = 587; //25, 456 , 587
    const wablas_url            = 'https://simo.wablas.com/api';
    const wablas_url_message    = self::wablas_url.'/send-message';
    const wablas_url_image      = self::wablas_url.'/send-image';
    const wablas_url_video      = self::wablas_url.'/send-video';
    const wablas_url_document   = self::wablas_url.'/send-document';
    const fcm_url               = 'https://fcm.googleapis.com';
    const fcm_url_send          = self::fcm_url.'/fcm/send';
    const fcm_server_key        = 'AIzaSyC3rX7bePdEZnRLZJ_asuCdBEDqp6BrFUI';
    const socket_url            = 'http://149.129.222.185:8881';
    const path_image            = 'assets/foto_wa/';
    const path_video            = 'assets/video_wa/';
    const path_document         = 'assets/document_wa/';
    
    /**
     * constructor
     *
     * @param string $config
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->_ci = & get_instance();
        $this->_ci->load->library('email');
        
        $this->_ci->load->model('Milis_model');
        $this->_ci->load->model('Milis_member_model');
        $this->_ci->load->model('Inbox_model');
        $this->_ci->load->model('Hotline_model');
        $this->_ci->load->model('Send_message_detail_model');
        $this->_ci->load->helper(array('form', 'url'));

        $this->client       = new GuzzleHttp\Client();
        $this->startRes     = time();
        $this->timeNow      = date("Y-m-d H:i:s");

        // $this->_ci->config->load('apiwha');
        // $this->url          = $this->_ci->config->item('APIWeb');
    }

    public function _sendSendgrid($to = null, $subject = null, $message = null, $attach = null)
    {
        if($to && $subject && $message){

            try{
                $this->_ci->email->initialize(array(
                    'protocol' => 'smtp',
                    'smtp_host' => self::sendgrid_host,
                    'smtp_user' => $this->_ci->config->item('smtp_user'),
                    'smtp_pass' => $this->_ci->config->item('smtp_pass'),
                    'smtp_port' => self::sendgrid_port,
                    'mailtype'  => 'html',
                    'priority'  => 1,
                    'crlf'      => "\r\n",
                    'newline'   => "\r\n"
                ));
        
                $this->_ci->email->from($this->_ci->config->item('smtp_email_from'), $this->_ci->config->item('smtp_email_name'));
                $this->_ci->email->to($to);
                $this->_ci->email->cc($this->_ci->config->item('smtp_email_cc'));
                // $this->email->bcc('them@their-example.com');
                $this->_ci->email->subject($subject);
                $this->_ci->email->message($message);
                if($attach){
                    $this->_ci->email->attach($attach);
                }
                if ( ! $this->_ci->email->send())
                {
                    return false;
                }
                else{
                    return true;
                }

            }catch(exception $e){
                return false;
            }
        }else{
            return false;
        }
    }

    public function _sendWablas($array)
    {
        try {
            $response = "";
            switch ($array['type']){
                case "text":
                    $response = $this->client->request( 'POST', self::wablas_url_message,
                        [   
                            'headers'       => ['Accept' => 'application/json','Authorization' => $array['token']],
                            'form_params'   => [
                                'phone'         => $array['phone'], 
                                'message'       => $array['caption']
                            ]
                        ]
                    );
                    break;
                case "image":
                    $response = $this->client->request( 'POST', self::wablas_url_image,
                        [   
                            'headers'       => ['Accept' => 'application/json','Authorization' => $array['token']],
                            'form_params'   => [
                                'phone'         => $array['phone'], 
                                'caption'       => $array['caption'],
                                'image'         => base_url().self::path_image.$array['file']
                            ]
                        ]
                    );
                    break;
                case "video":
                    $response = $this->client->request( 'POST', self::wablas_url_video,
                        [   
                            'headers'       => ['Accept' => 'application/json','Authorization' => $array['token']],
                            'form_params'   => [
                                'phone'         => $array['phone'], 
                                'caption'       => $array['caption'],
                                'video'         => base_url().self::path_image.$array['file']
                            ]
                        ]
                    );
                    break;
                case "document":
                    $response = $this->client->request( 'POST', self::wablas_url_document,
                        [   
                            'headers'       => ['Accept' => 'application/json','Authorization' => $array['token']],
                            'form_params'   => [
                                'phone'         => $array['phone'], 
                                'caption'       => $array['caption'],
                                'document'      => base_url().self::path_image.$array['file']
                            ]
                        ]
                    );
                    break;
            }
            
            $status_code    = $response->getStatusCode(); // 200
            $body           =  json_decode($response->getBody(),true);
            
            $dataInsert = [
                'status'        => $body['status'],//
                'message'       => $body['message'],
                'quota'         => $body['data']['quota'],
                'status_code'   => $status_code,
                //'response'      => $response,
                'created'       => date("Y-m-d H:i:s"),
                'createdby'     => $array['session_email'],
            ];
           
            $id     = $this->_ci->Send_message_detail_model->insert_header($dataInsert);

            foreach ($body['data']['message'] as $key => $value) {
                $data = array(
                    'header_id'     => $id,
                    'from_num'      => $array['hotline'],
                    'dest_num'      => $value['phone'],
                    'message_id'    => $value['id'],
                    'status'        => $value['status'],
                    'created'       => date("Y-m-d H:i:s"),
                    'createdby'     => $array['session_email'],
                    'updated'       => date("Y-m-d H:i:s"),
                    'updatedby'     => $array['session_email'],
                );
                 switch ($array['type']){
                    case 'text': 
                        $data['message_text'] = $value['text'];
                        break;
                    case 'image':
                        $data['message_text']   = $value['caption'];
                        $data['message_image']  = '';
                        $data['doc_name']       = '';
                        break;
                    case 'video':
                        $data['message_text']   = $value['caption'];
                        $data['message_image']  = '';
                        $data['doc_name']       = '';
                        break;
                    case 'document':
                        $data['message_text']   = $value['caption'];
                        $data['message_image']  = '';
                        $data['doc_name']       = '';
                        break;
                }

                $this->_ci->Send_message_detail_model->insert($data);
            }

            $insert2 = array(
                'customer_phone'    => $array['phone'],
                'message'           => $array['caption'],
                'created'           => date("Y-m-d H:i:s"),
                'message_id'	    => $body['data']['message'][0]['id'],
                'group_hotline'	    => $array['hotline'],
                'createdby'         => $array['session_email'],
                'user_phone'        => $array['session_userphone'],
                'flag_status'       => "5",
            );
            // echo json_encode($body);

            if($this->updatestatus_wablas($insert2, $array['session_username']) == true){
                return true;
            }else{
                return false;
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            // print_r($responseBodyAsString);
            return false;
        }
        return array("status"=>false, "result" => "failed");
    }

    private function updatestatus_wablas($array, $session_username){

        try{
            $this->_ci->Inbox_model->insertHotline($array);
            $array['username'] = $session_username;
    
            $this->sendSocket($array);
            $this->sendFCM($array);

            return true;
        }catch (Exception $e){
            return false;
        }
        
    }

    private function sendFCM($data){
        $data = json_encode([
            'to'            =>'/topics/'.$data['group_hotline'],
            'priority'      =>'high',
            "notification"  => [
                "body"  => $data['message'],
                "title" => $data['customer_phone'],
                "icon"  => "ic_launcher"
            ],
            "data" => $data
        ]);
       
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.self::fcm_server_key
        );
        //CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::fcm_url_send);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        print_r($result);
        // if ($result === FALSE) {
        //     die('Oops! FCM Send Error: ' . curl_error($ch));
        // }
        // $this->Loging("send_fcm" , ["result"=>$result]);
        curl_close($ch);
        die();

        // $response = $this->client->request( 'POST', self::fcm_url_send,
        //                 [   
        //                     'headers'       => ['Content-Type:application/json','Authorization:key='.self::fcm_server_key],
        //                     'form_params'   => 
        //                             array([
        //                                 'to'            =>'/topics/'.$data['group_hotline'],
        //                                 'priority'      =>'high',
        //                                 "notification"  => [
        //                                     "body"  => $data['message'],
        //                                     "title" => $data['customer_phone'],
        //                                     "icon"  => "ic_launcher"
        //                                 ],
        //                                 "data" => $data
        //                             ])
        //                 ]
        //             );
    }

    private function sendSocket($data){
        $client = new Client(new Version1X(self::socket_url));
        $client->initialize();

        // send message to connected clients
        $client->emit('post_key', ['socket_session' => '', 'id_account' => $data['customer_phone'], 'datas' => json_encode($data)]);
        $client->emit('sendWA', ['socket_session' => '', 'id_account' => $data['group_hotline'], 'datas' => json_encode($data)]);
        $client->close();
    }

    public function upload_file($type = '', $file = ''){


        $name = time().rand(100,999);
        if($type == 'image'){
            $config['upload_path']      = self::path_image;
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['file_name']        = 'image_'.$name;
        }
        if($type == 'video'){
            $config['upload_path']      = self::path_image;
            $config['allowed_types']    = 'mp4|mpeg';
            $config['file_name']        = 'video_'.$name;
        }
        if($type == 'document'){
            $config['upload_path']      = self::path_image;
            $config['allowed_types']    = 'doc|docx|pdf|odt|csv|ppt|pptx|xls|xlsx|mp3|ogg|gif|jpg|png|jpeg';
            $config['file_name']        = 'document_'.$name;
        }
        
        
        //$config['max_size']             = 100;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        $this->_ci->load->library('upload', $config);
        $this->_ci->upload->do_upload($file);
        return $this->_ci->upload->data();
    }
    
}