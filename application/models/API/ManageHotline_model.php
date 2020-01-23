<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageHotline_model extends CI_Model
{

    public $table = 'milis';
    public $table2 = 'tbl_invoice';
    public $table3 = 'hotline';
    public $table_server = 'ms_hotline';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
        $this->config->load('companyProfile');
        $this->dbServer = $this->load->database('server_admin', TRUE);
    }

    // datatables
    function jsonBU() {
        $this->datatables->select('a.id,a.name,a.device_id,a.device_name,a.domain_api,a.token,a.phone_number,a.created,a.createdby,a.updated,a.updatedby');
        $this->datatables->from('milis as a');
        //add this line for join
        $this->datatables->add_column('action', '<a href="#" class="btn btn-danger btn-sm" onclick="editModal($1);return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a> '.' <a href="'.base_url().'ManageHotlineMember/member/$1" class="btn btn-danger btn-sm" ><i class="fa fa-eye" aria-hidden="true"></i> </a>'. ' <a href="#" class="btn btn-danger btn-sm" onclick="delete_conf($1);return false;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>', 'id');
        return $this->datatables->generate();
    }
    // datatables
    function json() { 
        $this->datatables->set_database("server_admin");
        $this->datatables->select('a.pid,a.name,a.wa_status,phone_number,a.device_id,a.device_name,a.domain_api,a.token,a.phone_number,a.created,a.createdby,a.updated,a.updatedby');
        $this->datatables->from($this->table_server.' as a');
        $this->datatables->where("company_id",$this->session->userdata('company_pid'));
        //add this line for join
        $this->datatables->add_column('action', '<a href="#" onclick="barcodeModal(\'$2\');return false;"  class="btn btn-info btn-sm" ><i class="fa fa-qrcode" aria-hidden="true"></i> </a> <a href="'.base_url().'ManageHotlineMember/member/$1" class="btn btn-danger btn-sm" ><i class="fa fa-eye" aria-hidden="true"></i> </a> ', 'phone_number,token');
        return $this->datatables->generate();
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
    // get data by id
    function get_by_where($where)
    {
        $this->dbServer->where($where);
        return $this->dbServer->get($this->table_server)->row();
    }

    function getTableCompanyFromHotline($hotline_num)
    {
        return $this->table;
    }

    function get_all_where($where)
    {
        $this->dbServer->order_by('pid', 'asc');
        $this->dbServer->where($where);
        return $this->dbServer->get($this->table_server)->result();
    }

    function detail_list($where,$start) {
        $this->db->select($this->table3.".*,tbl_user.full_name as username,name_replace as username_title");
        // $this->db->from('hotline');
        $this->db->join('tbl_user', $this->table3.'.createdby = email',"LEFT");
        $this->db->join('mst_contact', 'mst_contact.phone = customer_phone',"LEFT");
        $this->db->where($where);
        $this->db->order_by('created', 'DESC');
        $this->db->limit(10,$start);

        return $this->db->get($this->table3)->result();
    }
    
    function count_all($where) {
        // $this->db->select($this->table.".*,tbl_user.full_name as username");
        // $this->db->from('hotline');
        // $this->db->join('tbl_user', $this->table.'.createdby = email',"LEFT");
        // $this->db->count_all_results('my_table'); 
        $this->db->where($where);
        $this->db->from($this->table3);
        return $this->db->count_all_results();
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
        $this->dbServer->where("phone_number", $number);
        $data =  $this->dbServer->get($this->table_server)->row();
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