<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Debug extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // is_login();
        date_default_timezone_set('Asia/Jakarta');

        $this->config->load('apiwha');
        $this->load->library('wakitalib');
        $this->load->model('API/Contact_model');

    }
 
    public function index()
    {
      // echo "43312";
      // echo $this->wakitalib->pid("USR");
      for ($i=0; $i < 23; $i++) {
        sleep(1);
        $pid = $this->wakitalib->get_pid_id('tbl_user',"MSUser",'id_users',1);
        echo $pid ;  
        echo "<br>\n";
        # code...
      }

      // echo "<br>\n";
      // $pid = $this->wakitalib->get_pid_id('tbl_user',"TUSER",'id_users',1);
      // echo $pid ;  

    } 


    public function pid()
    {
      $this->wakitalib->set_database('server_admin');
      $datas = $this->wakitalib->get_pid_id('tbl_subscription',"TSB",'subscription_id',1);  
      
      echo $datas;
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

/* End of file Hotline.php */
/* Location: ./application/controllers/Hotline.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-15 11:41:24 */
/* http://harviacode.com */