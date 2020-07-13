<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contracts_m extends MY_Model
{
    protected $_table_name = 'tbl_contracts';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_contracts.signed_date desc";

    function __construct()
    {
        parent::__construct();
    }

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("tbl_projects.title as project");
        $this->db->select("tbl_projects.id as project_id");
        $this->db->select("tbl_user.name as project_worker");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.title like '%{$queryStr}%' "
                . "or {$this->_table_name}.no like '%{$queryStr}%' "
                . "or tbl_projects.title like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_projects", "{$this->_table_name}.id = tbl_projects.contract_id", "left")
            ->join("tbl_user", "tbl_projects.worker_id = tbl_user.id", "left");
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
        $this->db->order_by($this->_order_by);
        $this->db->limit($cntPerPage, $pageId);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_count($arr = array(), $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.id");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.title like '%{$queryStr}%' "
                . "or {$this->_table_name}.no like '%{$queryStr}%' "
                . "or tbl_projects.title like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_projects", "{$this->_table_name}.id = tbl_projects.contract_id", "left")
            ->join("tbl_user", "tbl_projects.worker_id = tbl_user.id", "left");
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getItems($arr = array())
    {
        return $this->getItemsByPage($arr, 0, 100000);
    }

    public function add($arr)
    {
        $this->db->insert($this->_table_name, $arr);
        return $this->db->insert_id();
    }

    public function get_where($arr = array())
    {
//        $array = array();
//        foreach ($arr as $key => $value) {
//            $array[$this->_table_name . '.' . $key] = $value;
//        }
        $this->db->from($this->_table_name);
        $this->db->where($arr)
            ->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_where_in($key, $arr = array())
    {
        if ($arr == array()) return $arr;

        $this->db->from($this->_table_name);
        $this->db->where_in("{$this->_table_name}.{$key}", $arr)
            ->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->result();
    }

    public function edit($arr, $item_id)
    {
        $this->db->where($this->_primary_key, $item_id);
        $this->db->update($this->_table_name, $arr);
        return $this->db->affected_rows();
    }

    public function delete($item_id)
    {
        $this->db->where($this->_primary_key, $item_id);
        $this->db->delete($this->_table_name);
        return $this->db->affected_rows();
    }

    public function publish($item_id, $status = 1)
    {
        return $this->edit(array('status' => $status), $item_id);
    }

}
