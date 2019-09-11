<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Milis_model extends CI_Model
{

    public $table = 'milis';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,name,created,createdby,updated,updatedby');
        $this->datatables->from('milis');
        //add this line for join
        //$this->datatables->join('table2', 'milis.field = table2.field');
        $this->datatables->add_column('action', '<a href="#" class="btn btn-danger btn-sm" onclick="editModal($1);return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a> '.' <a href="'.base_url().'/Milis_member/member/$1" class="btn btn-danger btn-sm" ><i class="fa fa-eye" aria-hidden="true"></i> </a>'. ' <a href="#" class="btn btn-danger btn-sm" onclick="delete_conf($1);return false;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>', 'id');
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
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('name', $q);
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