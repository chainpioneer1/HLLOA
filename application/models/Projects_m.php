<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Projects_m extends MY_Model
{
    protected $_table_name = 'tbl_projects';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_projects.published_at desc";

    function __construct()
    {
        parent::__construct();
    }

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("concat( '', '', {$this->_table_name}.title) as title");
        $this->db->select("tbl_user.name as worker");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.title like '%{$queryStr}%' "
                . "or {$this->_table_name}.no like '%{$queryStr}%' "
                . "or {$this->_table_name}.total_score like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.worker_id = tbl_user.id", "left")
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
                . "or {$this->_table_name}.no like '%{$queryStr}%' "
                . "or {$this->_table_name}.total_score like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.worker_id = tbl_user.id", "left")
            ->where("{$this->_table_name}.title != ''");
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getGroupItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("concat( '', '', {$this->_table_name}.title) as title1");
        $this->db->select("sum({$this->_table_name}.total_score) as total_score1");
        $this->db->select("group_concat({$this->_table_name}.total_score) as total_score_val");
        $this->db->select("group_concat( tbl_user.name ) as worker");
        $this->db->select("group_concat( tbl_user.id ) as worker_ids");
        $this->db->select("group_concat({$this->_table_name}.id ) as projIds");
        $this->db->select(
            "if( min({$this->_table_name}.progress) = max({$this->_table_name}.progress)," .
            "min({$this->_table_name}.progress), 1) as progress1"
        );
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.worker_id = tbl_user.id", "left")
            ->where("{$this->_table_name}.title != ''");
        $this->db->group_by($this->_table_name . '.pid');
        $this->db->order_by($this->_order_by);
        $this->db->order_by($this->_table_name.'.progress', 'desc');
        $subQuery = $this->db->get_compiled_select();

        $this->db->select('*');
        $this->db->select('progress1 as progress');
        $this->db->select('title1 as title');
        $this->db->select('total_score1 as total_score');
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( tbl_union.title1 like '%{$queryStr}%' "
                . "or tbl_union.no like '%{$queryStr}%' "
                . "or tbl_union.total_score1 like '%{$queryStr}%' "
                . "or tbl_union.worker like '%{$queryStr}%' )"
            );
        }
        $this->db->from("($subQuery) as tbl_union");
        $this->db->limit($cntPerPage, $pageId);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_group_count($arr = array(), $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("concat( '', '', {$this->_table_name}.title) as title1");
        $this->db->select("sum({$this->_table_name}.total_score) as total_score1");
        $this->db->select("group_concat({$this->_table_name}.total_score) as total_score_val");
        $this->db->select("group_concat( tbl_user.name ) as worker");
        $this->db->select(
            "if( min({$this->_table_name}.progress) = max({$this->_table_name}.progress)," .
            "min({$this->_table_name}.progress), 1) as progress1"
        );
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.worker_id = tbl_user.id", "left")
            ->where("{$this->_table_name}.title != ''");
        $this->db->group_by($this->_table_name . '.pid');
        $this->db->order_by($this->_order_by);
        $subQuery = $this->db->get_compiled_select();

        $this->db->select('*');
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( tbl_union.title1 like '%{$queryStr}%' "
                . "or tbl_union.no like '%{$queryStr}%' "
                . "or tbl_union.total_score1 like '%{$queryStr}%' "
                . "or tbl_union.worker like '%{$queryStr}%' )"
            );
        }
        $this->db->from("($subQuery) as tbl_union");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getItems($arr = array())
    {
        return $this->getItemsByPage($arr, 0, 10000);
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
