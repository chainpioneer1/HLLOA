<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report_m extends MY_Model
{
    protected $_table_name = 'tbl_report';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_report.create_time desc";

    function __construct()
    {
        parent::__construct();
    }

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("concat( '', '', tbl_user.name) as name");
        $this->db->select("tbl_user.avatar");
        $this->db->select("tbl_user_part.title as part");
        $this->db->select("tbl_user_part.id as part_id");
        $this->db->select("tbl_user_position.title as position");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where("("
                . " tbl_user.name like '%{$queryStr}%' "
                . "or tbl_user.email like '%{$queryStr}%' "
                . "or tbl_user.phone like '%{$queryStr}%' "
                . ")"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.author_id = tbl_user.id", "left")
            ->join("tbl_user_position", "tbl_user.position_id = tbl_user_position.id", "left")
            ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");
        $this->db->where("tbl_user.status", 1);
        $this->db->where("tbl_user.id != 1");

//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
        $this->db->order_by($this->_order_by);
//        $this->db->limit($cntPerPage, $pageId);
//        var_dump($this->db->get_compiled_select());
        $query = $this->db->get();
        return $query->result();
    }

    public function get_count($arr = array(), $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.id");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( tbl_user.name like '%{$queryStr}%' "
                . "or tbl_user.email like '%{$queryStr}%' "
                . "or tbl_user.phone like '%{$queryStr}%' "
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.author_id = tbl_user.id", "left")
            ->join("tbl_user_position", "tbl_user.position_id = tbl_user_position.id", "left")
            ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");
        $this->db->where("tbl_user.status", 1);
        $this->db->where("tbl_user.id != 1");
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function add_date($orgDate, $mth)
    {
        $cd = strtotime($orgDate);
        $retDAY = date('Y-m-d H:i:s',
            mktime(0, 0, 0,
                date('m', $cd) + $mth,
                date('d', $cd),
                date('Y', $cd))
        );
        return $retDAY;
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
