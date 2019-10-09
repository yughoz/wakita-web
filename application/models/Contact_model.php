<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contact_model extends CI_Model
{

    public $table = 'mst_contact';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id,full_name,email,nama_level,is_aktif,phone');
        $this->datatables->from('mst_contact');
        $this->datatables->add_column('is_aktif', '$1', 'rename_string_is_aktif(is_aktif)');
        //add this line for join
        $this->datatables->join('mst_contact_level', 'mst_contact.id_user_level = mst_contact_level.id_user_level');
        $this->datatables->add_column('action',anchor(site_url('user/update/$1'),'<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm'))." 
                ".anchor(site_url('user/delete/$1'),'<i class="fa fa-trash-o" aria-hidden="true"></i>','class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id');
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
    

    function insert_update($data)
    {
        $where = array(
            "phone"         => $data["phone"],
            "group_hotline" => $data["group_hotline"],
        );
        $this->db->select('*');
        $this->db->where($where);//you can use another field
        $dataContact = $this->db->get($this->table)->row();
        if (!$dataContact) {
            $query = $this->db->insert($this->table, $data);//insert data
        } elseif ($data['name_wa'] != $dataContact->name_wa) {
            $dataContat = [
                "name_wa"       => $data['name_wa'],
                'updated'       => date("Y-m-d H:i:s"),
                'updatedby'     => "API_webhook",
            ];
            $this->db->where($where);
            $this->db->update($this->table, $dataContat);
            return $dataContact->name_replace;
        }

        return $dataContact->name_replace;
    }

    function insert_update_web($data)
    {
        $where = array(
            "phone"         => $data["phone"],
            "group_hotline" => $data["group_hotline"],
        );
        $this->db->select('*');
        $this->db->where($where);//you can use another field
        $dataContact = $this->db->get($this->table)->row();
        if (!$dataContact) {
            $query = $this->db->insert($this->table, $data);//insert data
            echo $query;
        } else {
            $dataContact = [
                "name_replace"       => $data['name_replace'],
                'updated'       => date("Y-m-d H:i:s"),
                'updatedby'     => "API_webhook",
            ];
            $this->db->where($where);
            $this->db->update($this->table, $dataContact);
            return $dataContact['name_replace'];
        }

        return $data['name_replace'];
    }

    // get data by id
    function get_by($where)
    {
        $this->db->where($where);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
    	$this->db->or_like('full_name', $q);
    	$this->db->or_like('email', $q);
    	$this->db->or_like('password', $q);
    	$this->db->or_like('images', $q);
    	$this->db->or_like('id_user_level', $q);
    	$this->db->or_like('is_aktif', $q);
    	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
    	$this->db->or_like('full_name', $q);
    	$this->db->or_like('email', $q);
    	$this->db->or_like('password', $q);
    	$this->db->or_like('images', $q);
    	$this->db->or_like('id_user_level', $q);
    	$this->db->or_like('is_aktif', $q);
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


    // update data
    function updateWhere($where, $data)
    {

        $this->db->select('*');
        $this->db->where($where);//you can use another field
        $dataContact = $this->db->get($this->table)->row();
        if (!$dataContact) {
            $query = $this->db->insert($this->table, $data);//insert data
        } else {
            $data = [
                "name_replace" => $data['name_replace']
            ];
            $this->db->where($where);
            $this->db->update($this->table, $data);
        }
    }
    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-10-04 06:32:22 */
/* http://harviacode.com */