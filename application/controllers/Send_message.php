<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Send_message extends CI_Controller
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

        $this->apiToken     = "2iNvy9zUUVSwMXSO71SIvdNwjE2c7DrfV6Kn3tCRcOvrkMnvl74kraCUbhZAHZZO";
        // $this->wablasClient = new WablasClient($apiToken);
        $this->url          = $this->config->item('APIWeb');
        // $this->url          = 'https://wablas.com/api';
        $this->client       = new GuzzleHttp\Client();
        $this->company_pid  = $this->session->userdata('company_pid');
        $this->startRes = time();

    }

    public function index()
    {
        // echo $this->config->item('keys')[0]['numbers'];die();
        $data = array(
            'button' => 'Create',
            'action' => site_url('send_message/create_action'),
		    'id' => set_value(''),
		    // 'header_id' => set_value(''),
		    'from_num' => set_value(''),
		    'dest_num' => set_value(''),
		    'message_id' => set_value(''),
		    'message_text' => set_value(''),
		    'status' => set_value(''),
		    'created' => set_value(''),
		    'createdby' => set_value(''),
		    'updated' => set_value(''),
		    'updatedby' => set_value(''),
		);
        $this->template->load('template','send_message/send_message_detail_list', $data);
    } 
    
    public function json() {
        header('Content-Type: application/json');

        $tableName = $this->Send_message_detail_model->get_table();
        $this->Send_message_detail_model->set_table($tableName."_".$this->company_pid);
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

    public function sendMessage($number,$message, $type = 'random') {
        $this->Loging("sendMessage_response_wablas" , $this->url);
       
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
            $res['status_code'] = $response->getStatusCode(); // 200
            // $response = $response->getReasonPhrase(); // OK
            // echo $response->getProtocolVersion(); // 1.1
            $res['body'] =  json_decode($response->getBody(),true);
            $this->Loging("response_wablas" , $res);

            $dataInsert = [
                'status'        => $res['body']['status'],
                'message'       => $res['body']['message'],
                'quota'         => $res['body']['data']['quota'],
                'status_code'   => $res['status_code'],
                // 'response' => $response,
                'created'       => date("Y-m-d H:i:s"),
                'createdby'     => $this->session->userdata('email'),
            ];
            // echo print_r($dataInsert);die();
            $id = $this->Send_message_detail_model->insert_header($dataInsert);
            
            foreach ($res['body']['data']['message'] as $key => $value) {
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
          } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            // print_r($responseBodyAsString);
          }

        return false;
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
            // echo print_r($res['body']);die();

            $dataInsert = [
                'status' => $res['body']['status'],
                'message' => $res['body']['message'],
                'quota'    => $res['body']['data']['quota'],
                'status_code' => $res['status_code'],
                // 'response' => $response,
                'created' => date("Y-m-d H:i:s"),
                'createdby' => $this->session->userdata('email'),
            ];
            // echo print_r($dataInsert);die();
            $id = $this->Send_message_detail_model->insert_header($dataInsert);
            
            foreach ($res['body']['data']['message'] as $key => $value) {
                $data = array(
                'header_id' => $id,
                'from_num' => $this->config->item('keys')[0]['numbers'],
                'dest_num' => $value['phone'],
                'message_id' => $value['id'],
                'message_text' => $value['caption'] ?? "",
                'message_image'         => $value['image'] ?? "",
                'status' =>     $value['status'],
                'created' => date("Y-m-d H:i:s"),
                'type'      => $data['type_file'],
                'doc_name'      => $data['doc_name'],
                'createdby' => $this->session->userdata('email'),
                'updated' => date("Y-m-d H:i:s"),
                'updatedby' => $this->session->userdata('email'),
                );
                $this->Send_message_detail_model->insert($data);
            }
            // echo json_encode($body);
          } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $res['response'] = $e->getResponse();
            $res['responseBodyAsString'] = $res['response']->getBody()->getContents();
            $this->Loging("response_wablas_error" , $res);
            // print_r($responseBodyAsString);
          }

        return false;
    }

    public function sendImgLocal($number,$message,$fileData, $type = 'random') {
        $filename   = $fileData['images']['tmp_name'];
        $handle     = fopen($filename, "r");
        $file       = fread($handle, filesize($filename));
        // $foto       = $this->upload_foto();
        // echo print_r($fileData);die();
       try {
            $response = $this->client->request( 'POST', 
                                           $this->url."/send-image-from-local", 
                                          [ 
                                            'headers' => [
                                                                'Accept' => 'application/json',
                                                                'Authorization' => $this->apiToken,
                                                            ],
                                            'form_params' 
                                                => [
                                                'phone'     => $number,
                                                'caption'   => $message,
                                                'file'      => base64_encode($file),
                                                'data'      => json_encode($fileData['images'])
                                            ] 
                                          ]
                                        );
            #guzzle repose for future use
            $status_code = $response->getStatusCode(); // 200
            // $response = $response->getReasonPhrase(); // OK
            // echo $response->getProtocolVersion(); // 1.1
            // echo $response->getBody();die();
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
            
            foreach ($body['data']['message'] as $key => $value) {
                $data = array(
                'header_id'     => $id,
                'from_num'      => $this->config->item('keys')[0]['numbers'],
                'dest_num'      => $value['phone'],
                'message_id'    => $value['id'],
                'message_text'  => $value['caption'],
                'message_image'         => $value['image'],
                'status'        => $value['status'],
                'type'          => "image_local",
                'created'       => date("Y-m-d H:i:s"),
                'createdby'     => $this->session->userdata('email'),
                'updated'       => date("Y-m-d H:i:s"),
                'updatedby'     => $this->session->userdata('email'),
                );
                $idImg = $this->Send_message_detail_model->insert($data);
                $this->upload_foto($idImg);
            }
            // echo json_encode($body);
          } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            // print_r($responseBodyAsString);
          }

        return false;
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
    		// 'header_id' => $this->input->post('header_id',TRUE),
    		'from_num' => $this->config->item('keys')[0]['numbers'],
    		'dest_num' => $this->input->post('dest_num',TRUE),
    		'message_id' => $this->input->post('message_id',TRUE),
    		'message_text' => $this->input->post('message_text',TRUE),
    		'status' => $this->input->post('status',TRUE),
    		'created' => date("Y-m-d H:i:s"),
    		'createdby' => $this->session->userdata('email'),
    		'updated' => date("Y-m-d H:i:s"),
    		'updatedby' => $this->session->userdata('email'),
	    );

            $this->sendMessage($data['dest_num'],$data['message_text']);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success",
            ]);die();
        }
    }
    
    public function create_img_action() 
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
            // 'header_id' => $this->input->post('header_id',TRUE),
            'from_num' => $this->config->item('keys')[0]['numbers'],
            'number' => $this->input->post('dest_num',TRUE),
            'message_id' => $this->input->post('message_id',TRUE),
            'message' => $this->input->post('message_text',TRUE),
            'status' => $this->input->post('status',TRUE),
            'type_file' => $this->input->post('type_file',TRUE),
            'type_image' => $this->input->post('type_image',TRUE),
            'imageUrl' => $this->input->post('url',TRUE),
            'created' => date("Y-m-d H:i:s"),
            'createdby' => $this->session->userdata('email'),
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => $this->session->userdata('email'),
        );

        $fileName = time().rand(100,999);
        if ($data['type_file'] == "image_file") {
            if ($data['type_image'] == "url") {
                // $this->sendImg($data['dest_num'],$data['message_text'],$data['url']);
            } else{
                $resultUpload = $this->upload_foto($fileName);
                $data['imageUrl'] = base_url('assets/foto_wa')."/".$resultUpload['file_name'];
                $data['doc_name'] = $resultUpload['file_name'];
                // echo print_r($data);die();
                // [file_name] => 1565594227542.jpg
                // [file_type] => image/jpeg
                // [file_path] => /var/www/html/wablas/assets/foto_wa/
                // [full_path] => /var/www/html/wablas/assets/foto_wa/1565594227542.jpg
                // [raw_name] => 1565594227542
                // [orig_name] => 1565594227542.jpg
                // [client_name] => harvest_moon_back_to_nature_colored_ver__by_sam_bluefunnybear-dcej4a5.jpg
                // [file_ext] => .jpg
                // [file_size] => 258.57
                // [is_image] => 1
                // [image_width] => 1024
                // [image_height] => 707
                // [image_type] => jpeg
                // [image_size_str] => width="1024" height="707"
                // $this->sendImgLocal($data['dest_num'],$data['message_text'],$_FILES);
            }
            # code...
        } else {
            // echo "3211";die();
            $resultUpload = $this->upload_doc($fileName);
            // print_r($resultUpload);die();
            $data['imageUrl'] = base_url('assets/doc_wa')."/".$resultUpload['file_name'];
            $data['doc_name'] = $resultUpload['file_name'];
        }
            
            $this->sendImg($data);

            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success create_img_action",
            ]);die();
        }
    }
    
    function test(){
        echo 'asassa';
    }
    public function create_img_local_action() 
    {

        // error_reporting(E_ALL);
        // define ('MAX_FILE_SIZE', 204800);

        // echo print_r($_FILES);
        // $files=$_FILES['files']['name'];
        // $nama=$_FILES['files']['name'];
        // echo $nama;
        // $this->_rules();

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
            'dest_num' => $this->input->post('dest_num',TRUE),
            'message_id' => $this->input->post('message_id',TRUE),
            'message_text' => $this->input->post('message_text',TRUE),
            'status' => $this->input->post('status',TRUE),
            'url' => $this->input->post('url',TRUE),
            'created' => date("Y-m-d H:i:s"),
            'createdby' => $this->session->userdata('email'),
            'updated' => date("Y-m-d H:i:s"),
            'updatedby' => $this->session->userdata('email'),
        );


            $this->sendImgLocal($data['dest_num'],$data['message_text'],$_FILES);
            // echo base64_encode($file);
            echo json_encode([
                "code" => "success",
                "message" => "Create Record Success create_img_action",
            ]);die();
        // }
    }

     function upload_foto($imgName = ''){
        $config['upload_path']          = './assets/foto_wa';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        if (!empty($imgName)) {
           $config['file_name']         = $imgName;
        }
        //$config['max_size']             = 100;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        $this->load->library('upload', $config);
        $this->upload->do_upload('images');
        return $this->upload->data();
    }


     function upload_doc($imgName = ''){
        $config['upload_path']          = './assets/doc_wa';
        $config['allowed_types']        = 'doc|pdf|jpg|png|jpeg';
        if (!empty($imgName)) {
           $config['file_name']         = $imgName;
        }
        //$config['max_size']             = 100;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        $this->load->library('upload', $config);
        $this->upload->do_upload('images');
        return $this->upload->data();
    }
    
    public function _rules() 
    {
	// $this->form_validation->set_rules('header_id', 'header id', 'trim|required');
	// $this->form_validation->set_rules('from_num', 'from num', 'trim|required');
	$this->form_validation->set_rules('dest_num', 'dest num', 'trim|required');
	// $this->form_validation->set_rules('message_id', 'message id', 'trim|required');
	$this->form_validation->set_rules('message_text', 'message text', 'trim|required');
	// $this->form_validation->set_rules('status', 'status', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
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

/* End of file Send_message.php */
/* Location: ./application/controllers/Send_message.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-08 15:45:08 */
/* http://harviacode.com */