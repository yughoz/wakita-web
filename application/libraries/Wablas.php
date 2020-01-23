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
    const wablas_url            = 'http://paramitha.wablas.com/';
    const wablas_url_qrcode     = self::wablas_url.'generate/qr.php?url=aHR0cHM6Ly9zaW1vLndhYmxhcy5jb20&';
    const wablas_url_message    = self::wablas_url.'api/send-message';
    const wablas_url_image      = self::wablas_url.'api/send-image';
    const wablas_url_video      = self::wablas_url.'api/send-video';
    const wablas_url_document   = self::wablas_url.'api/send-document';
    const path_wablas_image     = self::wablas_url.'image/';
    const path_wablas_video     = self::wablas_url.'video/';
    const path_wablas_document  = self::wablas_url.'document/';

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
        
        $this->_ci->load->model('API/Milis_model');
        $this->_ci->load->model('API/Milis_member_model');
        $this->_ci->load->model('API/Inbox_model');
        $this->_ci->load->model('API/Hotline_model');
        $this->_ci->load->model('API/Send_message_detail_model');
        $this->_ci->load->helper(array('form', 'url'));

        $this->client       = new GuzzleHttp\Client();
        $this->startRes     = time();
        $this->timeNow      = date("Y-m-d H:i:s");
        $this->company_pid  = $this->_ci->session->userdata('company_pid');

        // $this->_ci->config->load('apiwha');
        // $this->url          = $this->_ci->config->item('APIWeb');
    }

    public function globalvariable($variable){
        switch ($variable){
            case "path_image": return self::path_image; break;
            case "path_video": return self::path_video; break;
            case "path_document": return self::path_document; break;
        }
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
        if($array['private'] == 0){
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
                // print_r($body);
                // die();
    
                // $get_id     = $this->insert_header_message_detail($array, $body, $status_code);
                $get_id     = $this->_ci->wakitalib->get_pid("SMH");
                $this->update_hotline($array, $body, $get_id, $status_code);
                $status1    = $this->update_message_detail($array, $body);
                $status2    = $this->sendSocket($array, $body);
                $status3    = $this->sendFCM($array, $body);
    
                if($status1 == true && $status2 == true && $status3 == true){
                    return true;
                }else{
                    return false;
                }
            } catch (GuzzleHttp\Exception\BadResponseException $e) {
                $response               = $e->getResponse();
                $responseBodyAsString   = $response->getBody()->getContents();
                // print_r($responseBodyAsString);
                //print_r($e);
                return false;
            }
        }else{
                $status1    = $this->update_message_detail_private($array);
                $status2    = $this->sendSocketPrivate($array);
                // $status3    = $this->sendFCM($array, $body);
    
                if($status1 == true && $status2 == true){
                    return true;
                }else{
                    return false;
                }
        }
        
    }

    private function insert_header_message_detail($array, $body, $status_code ){
        $insert = [
            'pid'           => $this->_ci->wakitalib->get_pid("SMH"),
            'status'        => $body['status'],
            'message'       => $body['message'],
            'quota'         => $body['data']['quota'],
            'status_code'   => $status_code,
            //'response'      => $response,
            'created'       => date("Y-m-d H:i:s"),
            'createdby'     => $array['session_email'],
        ];


        $tableHeaderName = $this->_ci->Send_message_detail_model->get_header_table();
        $this->_ci->Send_message_detail_model->set_header_table($tableHeaderName."_".$this->_ci->session->userdata('company_pid'));
        // echo "in here .".$tableHeaderName ." --- ";
        $this->_ci->Send_message_detail_model->insert_header($insert);
        return $insert['pid'];
    }

    private function update_hotline($array, $body, $id, $status_code = ""){
        foreach ($body['data']['message'] as $key => $value) {
            $data = array(
                'pid'           => $this->_ci->wakitalib->get_pid("SMDW"),               
                'company_pid'   => $this->company_pid,
                'header_pid'    => $id,
                'header_status'        => $body['status'],
                'header_message'       => $body['message'],
                'header_quota'         => $body['data']['quota'],
                'header_status_code'   => $status_code,
                'from_num'      => $array['hotline'],
                'dest_num'      => $value['phone'],
                'message_id'    => $value['id'],
                'message_text'  => !empty($value['text']) ? $value ['text'] : $value ['caption'] ?? "",
                'message_image' => $value['image'] ?? "",
                'document_name' => $value['document'] ?? "",
                'video_name'    => $value['video'] ?? "",
                'status'        => $value['status'],
                'created'       => date("Y-m-d H:i:s"),
                'createdby'     => $array['session_email'],
                'updated'       => date("Y-m-d H:i:s"),
                'updatedby'     => $array['session_email'],
            );


            // echo print_r($dataInsert);die();
            $tableName = $this->_ci->Send_message_detail_model->get_table();
            $this->_ci->Send_message_detail_model->set_table($tableName."_".$this->company_pid);

            $this->_ci->Send_message_detail_model->insert($data);
        }
    }

    private function update_message_detail($array, $body){

        $value = $body['data']['message'][0];
        try{
            $data = array(
                'pid'               => $this->_ci->wakitalib->get_pid("HTOUT"),               
                'company_pid'       => $this->company_pid,
                'created'           => date("Y-m-d H:i:s"),
                'createdby'         => $array['session_email'],
                'customer_phone'    => $array['phone'],
                'user_phone'        => $array['session_userphone'],
                'message'           => !empty($value['text']) ? $value ['text'] : $value ['caption'] ?? "",
                'image_name'        => $value ['image'] ?? "",
                'video_name'        => $value ['video'] ?? "",
                'document_name'     => $value ['document'] ?? "", 
                'message_id'	    => $value ['id'] ?? "", 
                'group_hotline'	    => $array['hotline'],
                'flag_status'       => "4",
            );
            // $this->_ci->Inbox_model->insertHotline($data);
            $this->_ci->Inbox_model->insertHotlineCustom($data,'hotline_'.$this->company_pid);
            // echo $this->company_pid;

            // $this->Inbox_model->insertHotlineCustom($insHotline,'hotline_'.$this->company_pid);
            return true;
        }catch(Exception $e){
            //print_r("umd => ".$e);
            return false;
        }
    }

    private function update_message_detail_private($array){

        try{
            $data = array(
                'pid'               => $this->_ci->wakitalib->get_pid("HTP"),               
                'created'           => date("Y-m-d H:i:s"),
                'createdby'         => $array['session_email'],
                'customer_phone'    => $array['phone'],
                'user_phone'        => $array['session_userphone'],
                'message'           => $array['caption'],
                // 'image_name'        => $value ['image'] ?? "",
                // 'video_name'        => $value ['video'] ?? "",
                // 'document_name'     => $value ['document'] ?? "", 
                // 'message_id'	    => $value ['id'] ?? "", 
                'group_hotline'	    => $array['hotline'],
                'flag_status'       => "4",
            );

            $tableP = $this->_ci->Inbox_model->getTablePrivate();
            $this->_ci->Hotline_model->setTable($tableP."_".$this->_ci->session->userdata('company_pid'));

            $this->_ci->Inbox_model->insertHotlinePrivate($data);
            return true;
        }catch(Exception $e){
            //print_r("umd => ".$e);
            return false;
        }
    }

    private function sendSocket($array, $body){
        
        $value = $body['data']['message'][0];
        $data = array(
            'private'           => false,
            'created'           => date("Y-m-d H:i:s"),
            'createdby'         => $array['session_email'],
            'user_send_phone'   => $array['session_userphone'],	//<-- user yang kirim  bisa customer / cs/admin
            'user_send_title'   => $array['session_username'],		//<-- user yang kirim  bisa customer / cs/admin
            'user_send_username'=> $array['session_username'],		//<-- user yang kirim  bisa customer / cs/admin klo customer ditambah customer di depan
            'customer_phone'    => $array['phone'],
            'customer_title'    => "",		        //<-- customer title
            'customer_username' => $array['session_username'],		        //<-- customer title ditambah customer di depan
            'file'              => $value ['document'] ?? "",
            'fileUrl'           => !empty($value ['document']) ? base_url().'API/DirectLink/file/document/'.$value ['document'] :"",
            'flag_status'       => "4",
            'group_hotline'     => $array['hotline'],
            'image'             => $value ['image'] ?? "",
            'imageUrl'          => !empty($value ['image']) ? base_url().'API/DirectLink/file/image/'.$value ['image'] :"",
            'image_name'        => $value ['image'] ?? "",
            'message'           => $array['caption'],
            'message_id'        => $value ['id'],
            'username_phone'    => $array['session_userphone'],
            'username_user'     => $array['session_username'],	//<-- username yang login
            'username_title'    => $array['session_username'],
            'video'             => $value ['video'] ?? "",
            'videoUrl'          => !empty($value ['video']) ? base_url().'API/DirectLink/file/video/'.$value ['video'] :"",
            'destination'       => 'outbox',
            'type'              => ''			//<--- type file gif|jpg|png|jpeg|mp4|mpeg|doc|docx|pdf|odt|csv|ppt|pptx|xls|xlsx|mp3|ogg|
        );
        // print_r($data);

        if(!empty($value ['image'])){
            $data['type'] = pathinfo($value ['image'])['extension'];

        }else if(!empty($value ['video'])){
            $data['type'] = pathinfo($value ['video'])['extension'];
        }else if(!empty($value ['document'])){
            $data['type'] = pathinfo($value ['document'])['extension'];
        }else{
            $data['type'] = "php";
        }

        try{
    
            $client = new Client(new Version1X(self::socket_url));
            $client->initialize();
            // send message to connected clients
            $client->emit('post_key', 
                                    [
                                        'socket_session'    => '', 
                                        'id_account'        => $data['customer_phone'], 
                                        'datas'             => json_encode($data)
                                    ]
                            );
            $client->emit('sendWA', 
                                    [
                                        'socket_session'    => '', 
                                        'id_account'        => $data['group_hotline'], 
                                        'datas'             => json_encode($data)]
                            );
            $client->close();
            return true;
        }catch(Exception $e){
            //print_r("sendSocket => ".$e);
            return false;
        }
    }

    private function sendSocketPrivate($array){
        
        // $value = $body['data']['message'][0];
        $data = array(
            'private'           => true,
            'created'           => date("Y-m-d H:i:s"),
            'createdby'         => $array['session_email'],
            'user_send_phone'   => $array['session_userphone'],	//<-- user yang kirim  bisa customer / cs/admin
            'user_send_title'   => $array['session_username'],		//<-- user yang kirim  bisa customer / cs/admin
            'user_send_username'=> $array['session_username'],		//<-- user yang kirim  bisa customer / cs/admin klo customer ditambah customer di depan
            'customer_phone'    => $array['phone'],
            'customer_title'    => "",		        //<-- customer title
            'customer_username' => $array['session_username'],		        //<-- customer title ditambah customer di depan
            'file'              => "",
            'fileUrl'           => "",
            'flag_status'       => "4",
            'group_hotline'     => $array['hotline'],
            'image'             => "",
            'imageUrl'          => "",
            'image_name'        => "",
            'message'           => $array['caption'],
            'message_id'        => "",
            'username_phone'    => $array['session_userphone'],
            'username_user'     => $array['session_username'],	//<-- username yang login
            'username_title'    => $array['session_username'],
            'video'             => "",
            'videoUrl'          => "",
            'destination'       => 'outbox',
            'type'              => ""			//<--- type file gif|jpg|png|jpeg|mp4|mpeg|doc|docx|pdf|odt|csv|ppt|pptx|xls|xlsx|mp3|ogg|
        );

        try{
            $client = new Client(new Version1X(self::socket_url));
            $client->initialize();

            $client->emit('post_key', 
                                    [
                                        'socket_session'    => '', 
                                        'id_account'        => $data['customer_phone'], 
                                        'datas'             => json_encode($data)
                                    ]
                            );
            $client->emit('sendWA', 
                                    [
                                        'socket_session'    => '', 
                                        'id_account'        => $data['group_hotline'], 
                                        'datas'             => json_encode($data)]
                            );
            $client->close();
            return true;
        }catch(Exception $e){
            //print_r("sendSocket => ".$e);
            return false;
        }
    }

    private function sendFCM($array, $body){

        $data = array(
            'created'           => date("Y-m-d H:i:s"),
            'createdby'         => $array['session_email'],
            'customer_phone'    => $array['phone'],
            'user_phone'        => $array['session_userphone'],
            'message'           => $body['data']['message'][0]['caption'] ?? "",
            'image_name'        => $body['data']['message'][0]['image'] ?? "",
            'video_name'        => $body['data']['message'][0]['video'] ?? "",
            'document_name'     => $body['data']['message'][0]['document'] ?? "", 
            'message_id'	    => $body['data']['message'][0]['id'] ?? "", 
            'group_hotline'	    => $array['hotline'],
            'flag_status'       => "4",
            'username'          => $array['session_username']
        );

        try{
            $response = $this->client->request( 
                'POST', self::fcm_url_send,
                [   
                    'headers'   => ['Content-Type' => 'application/json','Authorization' => 'key='.self::fcm_server_key],
                    'json'      => 
                        [
                            'to'            => '/topics/'.$data['group_hotline'],
                            'priority'      => 'high',
                            'notification'  => [
                                                'body' => $data['message'],
                                                'title' => $data['customer_phone'],
                                                'icon' => 'ic_launcher'
                                            ],
                            'data'          => $data,
                        ]
                ]
            );
            return true;
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $response               = $e->getResponse();
            $responseBodyAsString   = $response->getBody()->getContents();
            // print_r($responseBodyAsString);
            //print_r("sendFcm => ".$e);
            return false;
        }
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

    public function delete_file($path){
        try{
            $this->_ci->load->helper("file");
            unlink($path);
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    
}