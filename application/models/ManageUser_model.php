<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageUser_model extends CI_Model
{

    public $table = 'tbl_user';
    public $id = 'id_users';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
        $this->dbServer = $this->load->database('server_admin', TRUE);
        $this->load->library('wakitalib');
        $this->config->load('companyProfile');

    }

    // datatables
    function json() {
        $this->datatables->select('id_users,full_name,email,nama_level,is_aktif,phone');
        $this->datatables->from('tbl_user');
        $this->datatables->add_column('is_aktif', '$1', 'rename_string_is_aktif(is_aktif)');
        //add this line for join
        $this->datatables->join('tbl_user_level', 'tbl_user.id_user_level = tbl_user_level.id_user_level');
        $this->datatables->add_column('action',anchor(site_url('ManageUser/update/$1'),'<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('class' => 'btn btn-warning btn-sm'))." 
                ".anchor(site_url('ManageUser/delete/$1'),'<i class="fa fa-trash-o" aria-hidden="true"></i>','class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_users');
        return $this->datatables->generate();
    }

    // datatables
    function jsonMember($milis_arr) {
        $this->datatables->select('id_users,full_name,email,phone');
        $this->datatables->from('tbl_user');
        // $this->datatables->where_not_in('id_users',$milis_arr);
        if (!empty($milis_arr)) {
            $this->datatables->where_not_in('id_users',$milis_arr);
        }
        $this->datatables->add_column('selecting', '<a href="#" class="btn btn-danger btn-sm" onclick="selectingFunc($1);return false;"><i class="fa fa-check-circle" aria-hidden="true"></i> </a>', 'id_users');
        // $this->datatables->where('user_id',NULL);
// 

        return $this->datatables->generate();
        // echo $this->datatables->last_query();die();
        // echo print_r( $this->db->get()->result());
        // $data['data'] =  $this->db->query("
        //                             SELECT * FROM tbl_user 
        //                             WHERE id_users not in (SELECT user_id
        //                             FROM `vw_milis_member`
        //                             WHERE milis_id = ".$milis_id.")")->result_array();
        // foreach ($data['data'] as $key => $value) {
        //     $data['data'][$key]['selecting'] = '<a href="#" class="btn btn-danger btn-sm" onclick="selectingFunc('.$value['id_users'].');return false;"><i class="fa fa-check-circle" aria-hidden="true"></i> </a>';
        // }
        // $data['draw']              = intval($this->input->post('draw'));
        // $data['recordsTotal' ]     = count($data['data']);
        // $data['recordsFiltered']   = count($data['data']);

        // return $data;
        // return "" ;
        // return  $this->db->get()->result();
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
        $this->db->like('id_users', $q);
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
        $this->db->like('id_users', $q);
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

    // check data
    function check_insert($param)
    {
        $pid = $this->wakitalib->get_pid_id('tbl_user',"MSUser",'id_users',1);
        $data = [
                'pid' => $pid,
                'id_user_local' => $param['pid'],
                'email' => $param['email'],
                'phone' => $param['phone'],
                'company_id'    => $this->config->item('company_id'),
            ];
        $this->dbServer->where('email', $param['email']);
        $this->dbServer->or_where('phone', $param['phone']);
        if (!empty($this->dbServer->get('ms_users')->row())) {
            return false;
        }
        return $this->dbServer->insert("ms_users", $data);
    }

    function getMSuser($pid)
    {
        $this->dbServer->where('id_user_local', $pid);
        // $this->dbServer->or_where('phone', $where['phone']);
        return $this->dbServer->get('ms_users')->row();
    }


    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }


    // update data
    function updateMST($pid, $param,$oldData)
    {
        // $pid = $this->wakitalib->get_pid_id('tbl_user',"MSUser",'id_users',1);
        $data = [
                'email' => $param['email'],
                'phone' => $param['phone'],
            ];
        $this->dbServer->where('email', $param['email']);
        $this->dbServer->or_where('phone', $param['phone']);
        $checkData = $this->dbServer->get('ms_users')->row();
        if (!empty($checkData)) {
            if ($checkData->pid == $oldData->pid) {
                $return = true;
            } else {
                $return = false;
            }
        } else {
            $return = true;
        }

        if ($return === true) {
            $this->dbServer->where('pid', $pid);
            $this->dbServer->update('ms_users', $data);
                // echo print_r($checkData);die();
        }

        return $return;


    }

    // delete data
    function delete($id,$pid = "")
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
        if (!empty($pid)) {
            // die("123123");
            $this->dbServer->where('id_user_local', $pid);
            $this->dbServer->delete('ms_users');
        }
    }

}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-10-04 06:32:22 */
/* http://harviacode.com */