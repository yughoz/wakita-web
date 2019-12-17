<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageChat_model extends CI_Model
{

    public $table = 'milis';
    public $table2 = 'tbl_invoice';
    public $id = 'id';
    public $order = 'DESC';
    public $table_server = 'ms_hotline';
    public $view = 'vw_group_milis';

    function __construct()
    {
        parent::__construct();
    }


    function setView($view)
    {
        $this->view = $view;
        // echo $this->view." ___ ...";
    }

 
    function getView()
    {
        return $this->view;
    }

    // datatables
    function list_json() {
        $this->datatables->set_database("server_admin");
        $this->datatables->select('a.pid,a.name,a.wa_status,phone_number,a.device_id,a.device_name,a.domain_api,a.token,a.phone_number,a.created,a.createdby,a.updated,a.updatedby');
        $this->datatables->from($this->table_server.' as a');
        $this->datatables->where("company_id",$this->config->item('company_id'));
        $this->datatables->where_in("a.pid",$this->session->userdata('pidHotlineArr'));
        $this->datatables->add_column('action',anchor(site_url('ManageChat/hotline/$1'),'<i class="fa fa-wechat" aria-hidden="true"></i>', array('class' => 'btn btn-warning btn-sm')), 'phone_number');
        return $this->datatables->generate();
    }

    function group_json($where = []) {
        $this->db->select('max(pid),pid,name_wa,name_replace,customer_phone,message,flag_status,created,createdby,image_name,group_hotline'); 
        // $this->db->from('hotline');
        $this->db->where($where);
        $this->db->group_by('customer_phone');
        $this->db->order_by('pid', 'DESC');
        // $this->db->order_by('flag_status', 'ASC');

        return $this->db->get($this->view)->result();
    }

    function detail_json($array){

		$this->db->select('*');
		$this->db->from('union_chats');	
		// $this->db->group_by(array("from", "destination"));
		$this->db->order_by("create_date", "decs");

		$where = "from='".$array['to']."' OR destination='".$array['to']."'";
		$this->db->where([
			'number_contact'=> $array['to']
		]);
		$this->db->where(['number_home' => $array['hotline']]);	

		$query = $this->db->get();
		// echo $this->db->last_query();die();
        $inbox_data = $query->result();

        if ($inbox_data != null) {
			
			$data['data'] = $inbox_data;
			$data['status'] = 1;
			
		} else {
			
			$data['status'] = 0;
			
        }
        
        return $data;
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
        $this->db->or_like('name', $q);
        $this->db->or_like('device_id', $q);
        $this->db->or_like('device_name', $q);
        $this->db->or_like('domain_api', $q);
        $this->db->or_like('token', $q);
        $this->db->or_like('phone_number', $q);
        $this->db->or_like('created', $q);
        $this->db->or_like('createdby', $q);
        $this->db->or_like('updated', $q);
        $this->db->or_like('updatedby', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('name', $q);
        $this->db->or_like('device_id', $q);
        $this->db->or_like('device_name', $q);
        $this->db->or_like('domain_api', $q);
        $this->db->or_like('token', $q);
        $this->db->or_like('phone_number', $q);
        $this->db->or_like('created', $q);
        $this->db->or_like('createdby', $q);
        $this->db->or_like('updated', $q);
        $this->db->or_like('updatedby', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function insert2($data)
    {
        $this->db->insert($this->table2, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    // get token
    function get_token($number)
    {
        $this->db->where("phone_number", $number);
        $data =  $this->db->get($this->table)->row();
        if ($data) {
            return $data->token;
        }
        return $data;
    }

}

/* End of file Milis_model.php */
/* Location: ./application/models/Milis_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-14 12:08:38 */
/* http://harviacode.com */