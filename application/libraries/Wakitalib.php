<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wakitalib {

    private $ci;

    public function __construct()
    {
      $this->ci =& get_instance();
    }

    public function set_database($db_name)
    {
      $db_data = $this->ci->load->database($db_name, TRUE);
      $this->ci->db = $db_data;
    }

 	var $urlTarget = "";
    function pid($code = "")
    {
    	$pid_depan=$code;
        $datetime5= new datetime();
        $datetime5= $datetime5->format('Ymd');
        $mt5 = explode(' ', microtime());
        $mls5 = ((int)$mt5[1])*1000+((int)round($mt5[0] * 1000));
        $mls5 = substr($mls5, -3);
        $pid_full=$pid_depan.$datetime5.$mls5;
    	return $pid_full;
    }
    function create_link($url)
    {
    	// echo "ini url : ".$url;
    	$pars = parse_url($url);
    	$path = $this->urlTarget.$pars['path'];

        $pid = $this->get_pid_id('tbl_user',$table_code,'id_settlement_voucher',1);

    	// echo $this->urlTarget;
    	// echo $pars['path'];die();

    	return $path;
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

    // $pid=$this->get_pid_id('ms_settlement_voucher',$table_code,'id_settlement_voucher',1);
    public function get_pid_id($table,$table_code,$id_table_name = "pid",$for = 1)
    {
        $date=date('YmdHis');
        $var=$table_code.$date;
        /*get Last PID*/
        $this->ci->db->order_by($id_table_name, 'desc');
        $this->ci->db->like($id_table_name, $var, 'LEFT');
        $dat_pid=$this->ci->db->get($table, 1);

        $last_pid='001';
        if ($dat_pid->num_rows()>0) {
        $last_pid_full=$dat_pid->row_array()[$id_table_name];
        $last_pid=substr($last_pid_full, -3);

        $last_pid=intval($last_pid)+1;

        if (strlen($last_pid)==1) {
          $last_pid='00'.$last_pid;
        }elseif (strlen($last_pid)==2) {
          $last_pid='0'.$last_pid;
        }
        }
        $pid=$table_code.$date.$last_pid;
        // return $pid;
        $id_table='';
        if ($id_table_name!='') {
            $id_current_day=$table_code.date('Ymd');
            $this->ci->db->like($id_table_name, $id_current_day, 'LEFT');
            $this->ci->db->order_by($id_table_name, 'desc');
            $dat_id=$this->ci->db->get($table, 1);
            $last_id='001';
        if ($dat_id->num_rows()>0) {
            $last_id_full=$dat_id->row()->$id_table_name;
            $last_id=substr($last_id_full, -3);
            $last_id=intval($last_id)+1;
            if (strlen($last_id)==1) {
            $last_id='00'.$last_id;
            }elseif (strlen($last_id)==2) {
            $last_id='0'.$last_id;
            }
        }
            $id_table=$table_code.date('Ymd').$last_id;
        }

        if ($for==1) {
        return $pid;
        }else{
        return $id_table;
        }
    }
}
?>