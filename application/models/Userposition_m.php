<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Userposition_m extends MY_Model
{
    protected $_table_name = 'tbl_user_position';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_user_part.title asc, tbl_user_position.title asc";

    function __construct()
    {
        parent::__construct();
    }

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("concat( '', '', {$this->_table_name}.title) as title");
        $this->db->select("tbl_user_part.title as part");
        $this->db->select("tbl_user.name as boss");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.title like '%{$queryStr}%' "
                . "or tbl_user_part.title like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user_part", "{$this->_table_name}.part_id = tbl_user_part.id", "left")
            ->join("tbl_user", "tbl_user_part.boss_id = tbl_user.id", "left")
            ->where("{$this->_table_name}.title != ''");
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
                . "or tbl_user_part.title like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user_part", "{$this->_table_name}.part_id = tbl_user_part.id", "left")
            ->join("tbl_user", "tbl_user_part.boss_id = tbl_user.id", "left")
            ->where("{$this->_table_name}.title != ''");
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
