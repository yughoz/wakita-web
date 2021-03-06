<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inbox_model extends CI_Model
{

    public $table = 'inbox';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,message_id,fromMe,pushName,phone,message,timestamp,receiver,groupId');
        $this->datatables->from('inbox');
        //add this line for join
        //$this->datatables->join('table2', 'inbox.field = table2.field');
        $this->datatables->add_column('action', '<a href="#" class="btn btn-danger btn-sm" onclick="editModal($1);return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>'. ' <a href="#" class="btn btn-danger btn-sm" onclick="delete_conf($1);return false;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>', 'id');
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
     // get all
    function get_all_where($where)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->where($where);
        return $this->db->get($this->table)->result();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('message_id', $q);
	$this->db->or_like('fromMe', $q);
	$this->db->or_like('pushName', $q);
	$this->db->or_like('phone', $q);
	$this->db->or_like('message', $q);
	$this->db->or_like('timestamp', $q);
	$this->db->or_like('receiver', $q);
	$this->db->or_like('groupId', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('message_id', $q);
	$this->db->or_like('fromMe', $q);
	$this->db->or_like('pushName', $q);
	$this->db->or_like('phone', $q);
	$this->db->or_like('message', $q);
	$this->db->or_like('timestamp', $q);
	$this->db->or_like('receiver', $q);
	$this->db->or_like('groupId', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    function insertHotline($data)
    {
        $this->db->insert('hotline', $data);
    }

    function insertHotlinePrivate($data)
    {
        $this->db->insert('hotline_private', $data);
    }
    // get data by id
    function get_hotline($phone)
    {
        $this->db->where('customer_phone', $phone);
        $this->db->order_by('created', 'DESC');
        return $this->db->get('hotline')->row();
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

}

/* End of file Inbox_model.php */
/* Location: ./application/models/Inbox_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-09 15:10:00 */
/* http://harviacode.com */