<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageBlasting_model extends CI_Model
{

    public $table   = 'send_message_detail';
    public $id      = 'id';
    public $order   = 'DESC';
    

    function __construct()
    {
        parent::__construct();
        $this->dbServer = $this->load->database('server_admin', TRUE);
    }

    function get_hotline($company)
    {
        // $this->datatables->set_database("server_admin");
        $this->dbServer->select('*');
        $this->dbServer->where('company_id', $company);
        $this->dbServer->where('is_active', 1);
        // $this->dbServer->where('is_active', 1);
        return $this->dbServer->get('ms_hotline')->row();
    }

    function get_all_contact(){
        $this->db->select('id,name_wa,name_replace,phone,group_hotline');
        return $this->db->get('mst_contact')->result();
    }

    // datatables
    function json() {
        $this->datatables->select('id,header_id,from_num,dest_num,message_id,message_text,status,created,createdby,updated,updatedby');
        $this->datatables->from('send_message_detail');
        //add this line for join
        //$this->datatables->join('table2', 'send_message_detail.field = table2.field');
        $this->datatables->add_column('action', '<a href="#" class="btn btn-danger btn-sm" onclick="editModal($1);return false;"><i class="fa fa-eye" aria-hidden="true"></i> </a>', 'id');
        return $this->datatables->generate();
    }

    function json_getcontact() {
        $this->datatables->select('id,name_wa,name_replace,phone,group_hotline');
        $this->datatables->from('mst_contact');
        //add this line for join
        //$this->datatables->join('table2', 'send_message_detail.field = table2.field');
        $this->datatables->add_column('action', '<input type="checkbox" id="chk_$1" onclick="editModal($1);">', 'phone');
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
    function get_by_msg_id($id)
    {
        $this->db->where('message_id', $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
        $this->db->or_like('header_id', $q);
        $this->db->or_like('from_num', $q);
        $this->db->or_like('dest_num', $q);
        $this->db->or_like('message_id', $q);
        $this->db->or_like('message_text', $q);
        $this->db->or_like('status', $q);
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
        $this->db->or_like('header_id', $q);
        $this->db->or_like('from_num', $q);
        $this->db->or_like('dest_num', $q);
        $this->db->or_like('message_id', $q);
        $this->db->or_like('message_text', $q);
        $this->db->or_like('status', $q);
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

    // insert data header
    function insert_header($data)
    {
        $this->db->insert('send_message_header', $data);
        return $this->db->insert_id();
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // update data
    function updateWhere($data, $where)
    {
        $this->db->where($where);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}