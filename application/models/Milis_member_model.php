<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Milis_member_model extends CI_Model
{

    public $table = 'milis_member';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json($milis_id="") {
        $this->datatables->select('milis_member.*,milis_member.id,milis.name as milis_name,tbl_user.full_name as username');
        $this->datatables->from('milis_member');
        //add this line for join
        $this->datatables->where('milis.id',$milis_id);
        $this->datatables->join('milis', 'milis_id = milis.id');
        $this->datatables->join('tbl_user', 'user_id = id_users');
        $this->datatables->add_column('action', ' <a href="#" class="btn btn-danger btn-sm" onclick="delete_conf($1);return false;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>', 'id');

        return $this->datatables->generate();
    }

    // get all
    function get_all($where = [])
    {
        $this->db->order_by($this->id, $this->order);
        if(!empty($where)){
            $this->db->where($where);
        }
        return $this->db->get($this->table)->result();
    }
    // get all
    function getAllCustom($milis_id)
    {
        $this->datatables->select('milis_member.*,milis_member.id,tbl_user.full_name as username,phone');
        $this->db->order_by($this->id, $this->order);
        $this->db->where('milis_id',$milis_id);
        $this->datatables->join('tbl_user', 'user_id = id_users');
        return $this->db->get($this->table)->result();
    }

    // get all
    function getAllCustomWebhook($device_id)
    {
        // $this->datatables->select('vw_milis_member.*,vw_milis_member.id,tbl_user.full_name as username,phone');
        // $this->db->where('device_id',$device_id);
        // $this->datatables->join('tbl_user', 'user_id = id_users');
        // return $this->db->get("vw_milis_member")->result();
        $this->db->select($this->table.'.*,tbl_user.full_name as username,phone');
        $this->db->where('device_id',$device_id);
        $this->db->join('tbl_user', 'user_id = id_users');
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
        $this->db->order_by($this->id, 'asc');
        $this->db->where($where);
        return $this->db->get($this->table)->result();
    }

    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('milis_id', $q);
	$this->db->or_like('user_id', $q);
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
    	$this->db->or_like('milis_id', $q);
    	$this->db->or_like('user_id', $q);
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

}

/* End of file Milis_member_model.php */
/* Location: ./application/models/Milis_member_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-14 12:19:40 */
/* http://harviacode.com */