<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hotline_private_model extends CI_Model
{

    public $table = 'hotline_private';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,customer_phone,message,flag_status,created,createdby');
        $this->datatables->from('hotline_private');
        //add this line for join
        //$this->datatables->join('table2', 'hotline.field = table2.field');
        $this->datatables->add_column('action', '<a href="#" class="btn btn-danger btn-sm" onclick="editModal($1);return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>'. ' <a href="#" class="btn btn-danger btn-sm" onclick="delete_conf($1);return false;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>', 'id');
        return $this->datatables->generate();
    }

    // datatables
    function group_json($where = []) {
        $this->db->select('max(vw_group_milis.id),vw_group_milis.id,customer_phone,SUBSTR(message, 1, 30) as message,flag_status,vw_group_milis.created,vw_group_milis.createdby,vw_group_milis.group_hotline,image_name,name_replace as customer_title,SUBSTR(name_replace, 1, 14) as username_title_sort');  
        // $this->db->from('hotline');
        $this->db->where($where);
        $this->db->group_by('customer_phone');
        $this->db->order_by('vw_group_milis.id', 'DESC'); 
        // $this->db->order_by('flag_status', 'ASC'); 
        // $this->db->join('mst_contact', 'mst_contact.phone = customer_phone',"LEFT");
        return $this->db->get('vw_group_milis')->result();
    } 
    // datatables
    function group_json_search($where = [],$where_like = []) {
        $this->db->select('hotline.id,customer_phone,SUBSTR(message, 1, 30) as message,flag_status,hotline.created,hotline.createdby,hotline.group_hotline,image_name,name_replace as customer_title,SUBSTR(name_replace, 1, 14) as username_title_sort');  
        // $this->db->from('hotline');
        $this->db->where($where);
        $this->db->like($where_like);

        // $this->db->group_by('customer_phone');
        $this->db->order_by('hotline.id', 'DESC'); 
        // $this->db->order_by('flag_status', 'ASC'); 
        $this->db->join('mst_contact', 'mst_contact.phone = customer_phone',"LEFT");
        return $this->db->get('hotline')->result();
    } 


    // function group_json($where = []) {
    //     $this->db->select('max(id),id,customer_phone,message,flag_status,created,createdby,image_name,group_hotline'); 
    //     // $this->db->from('hotline');
    //     $this->db->where($where);
    //     $this->db->group_by('customer_phone');
    //     $this->db->order_by('id', 'DESC');
    //     // $this->db->order_by('flag_status', 'ASC');

    //     return $this->db->get('vw_group_milis')->result();
    // }
    // datatables

    function getQueryNumber($where,$id){
        $this->db->select("*,(@cnt := @cnt + 1) AS rowNumber");
        $this->db->where($where);
        $this->db->join('(SELECT @cnt := 0) AS dummy','1=1');
        $this->db->order_by('created', 'DESC');
        $this->db->get('hotline')->result();

        return $this->db->last_query();
    }


    function getRowNumber($limitQuery,$where_condition,$id){
        $sql = "SELECT rowNumber from (  ".$limitQuery." ) as t where id= ".$id;
        // $this->db->select("*");
        // $this->db->where($where);
        // $this->db->join('(SELECT @cnt := 0) AS dummy','1=1');
        
        // echo $sql;die();
        return $this->db->query($sql, $where_condition)->row();;
    }
    
	function detail_list($where, $start, $table = 'hotline_private',$limit = 10) {
        $this->db->select($table.".*,tbl_user.full_name as username,name_replace as username_title,(@cnt := @cnt + 1) AS rowNumber");
        // $this->db->from('hotline_private');
        $this->db->join('tbl_user', $table.'.createdby = email',"LEFT");
        $this->db->join('mst_contact', 'mst_contact.phone = customer_phone',"LEFT");
        $this->db->join('(SELECT @cnt := 0) AS dummy','1=1');
        $this->db->where($where);
        $this->db->order_by('created', 'DESC');
        $this->db->limit($limit,$start);

        return $this->db->get($table)->result();
    }

    function count_all($where, $table = 'hotline_private') {
        // $this->db->select($this->table.".*,tbl_user.full_name as username");
        // $this->db->from('hotline');
        // $this->db->join('tbl_user', $this->table.'.createdby = email',"LEFT");
        // $this->db->count_all_results('my_table'); 
        $this->db->where($where);
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }
         // get all
    function get_all_where($where)
    {
        $this->db->order_by($this->id, 'asc');
        $this->db->where($where);
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
    	$this->db->or_like('customer_phone', $q);
    	$this->db->or_like('message', $q);
    	$this->db->or_like('flag_status', $q);
    	$this->db->or_like('created', $q);
    	$this->db->or_like('createdby', $q);
    	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
    	$this->db->or_like('customer_phone', $q);
    	$this->db->or_like('message', $q);
    	$this->db->or_like('flag_status', $q);
    	$this->db->or_like('created', $q);
    	$this->db->or_like('createdby', $q);
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

/* End of file Hotline_model.php */
/* Location: ./application/models/Hotline_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-08-15 11:41:24 */
/* http://harviacode.com */