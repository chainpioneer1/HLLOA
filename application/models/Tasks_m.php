<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tasks_m extends MY_Model
{
    protected $_table_name = 'tbl_tasks';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_tasks.create_time desc";

    function __construct()
    {
        parent::__construct();
    }

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("concat( '', '', {$this->_table_name}.title) as title");
        $this->db->select("tbl_user.name as worker");
        $this->db->select("tbl_projects.title as project");
        $this->db->select("tbl_projects.total_score as total_score");
        $this->db->select("tbl_projects.author_id as project_author_id");
        $this->db->select("tbl_projects.worker_id as project_worker_id");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.title like '%{$queryStr}%' "
                . "or {$this->_table_name}.no like '%{$queryStr}%' "
                . "or {$this->_table_name}.score like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.worker_id = tbl_user.id", "left")
            ->join("tbl_projects", "{$this->_table_name}.project_id = tbl_projects.id", "left")
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
                . "or {$this->_table_name}.score like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.worker_id = tbl_user.id", "left")
            ->join("tbl_projects", "{$this->_table_name}.project_id = tbl_projects.id", "left")
            ->where("{$this->_table_name}.title != ''");
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getScoreSum($arr = array(), $queryStr = '')
    {
        $this->db->select("sum({$this->_table_name}.score) as scoreSum");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.title like '%{$queryStr}%' "
                . "or {$this->_table_name}.no like '%{$queryStr}%' "
                . "or {$this->_table_name}.score like '%{$queryStr}%' "
                . "or tbl_user.name like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.worker_id = tbl_user.id", "left")
            ->join("tbl_projects", "{$this->_table_name}.project_id = tbl_projects.id", "left")
            ->where("{$this->_table_name}.title != ''");
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->result();
    }

    public function getActionItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("*");
        $this->db->select("concat('管理:','') as task_title");
        $this->db->select("sum(score) as user_score");
        $this->db->select("max(completed_at) as complete_time");
        $this->db->from("tbl_tasks_complete");
        if($queryStr != '') {
            $this->db->where("completed_at >= '{$queryStr['range_from']}' ".
                " and completed_at < '{$queryStr['range_to']}'");
        }
        $this->db->group_by("project_id");
        $subQuery2 = $this->db->get_compiled_select();

        $this->db->select('*');
        $this->db->select('title as task_title');
        $this->db->select('score as user_score');
        $this->db->select('completed_at as complete_time');
        $this->db->from('tbl_tasks');
        $subQuery1 = $this->db->get_compiled_select();

        $this->db->select("tbl_union.*");
        $this->db->select("concat( '', '', tbl_union.title) as title");
        $this->db->select("tbl_user.name as worker");
        $this->db->select("tbl_projects.title as project");
        $this->db->select("tbl_projects.total_score as total_score");
        $this->db->select("tbl_projects.author_id as project_author_id");
        $this->db->select("tbl_projects.worker_id as project_worker_id");
        if ($arr != array()) $this->db->where($arr);
//        if ($queryStr != '') {
//            $this->db->where(
//                "( tbl_union.title like '%{$queryStr}%' "
//                . "or tbl_union.no like '%{$queryStr}%' "
//                . "or tbl_union.score like '%{$queryStr}%' "
//                . "or tbl_user.name like '%{$queryStr}%' )"
//            );
//        }
        $this->db->from("(($subQuery1) union ($subQuery2)) as tbl_union")
            ->join("tbl_user", "tbl_union.worker_id = tbl_user.id", "left")
            ->join("tbl_projects", "tbl_union.project_id = tbl_projects.id", "left");
        $this->db->where("tbl_union.title != ''");
//        var_dump($this->db->get_compiled_select());
        $this->db->order_by("tbl_union.create_time", "desc");
        $this->db->limit($cntPerPage, $pageId);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_action_count($arr = array(), $queryStr = '')
    {
        $this->db->select("*");
        $this->db->select("sum(score) as user_score");
        $this->db->select("max(completed_at) as complete_time");
        $this->db->from("tbl_tasks_complete");
        if($queryStr != '') {
            $this->db->where("completed_at >= '{$queryStr['range_from']}' ".
                " and completed_at < '{$queryStr['range_to']}'");
        }
        $this->db->group_by("project_id");
        $subQuery2 = $this->db->get_compiled_select();

        $this->db->select('*');
        $this->db->select('score as user_score');
        $this->db->select('completed_at as complete_time');
        $this->db->from('tbl_tasks');
        $subQuery1 = $this->db->get_compiled_select();

        $this->db->select("tbl_union.id");
        $this->db->where($arr);
//        if ($queryStr != '') {
//            $this->db->where(
//                "( tbl_union.title like '%{$queryStr}%' "
//                . "or tbl_union.no like '%{$queryStr}%' "
//                . "or tbl_union.score like '%{$queryStr}%' "
//                . "or tbl_user.name like '%{$queryStr}%' )"
//            );
//        }
        $this->db->from("(($subQuery1) union ($subQuery2)) as tbl_union")
            ->join("tbl_user", "tbl_union.worker_id = tbl_user.id", "left")
            ->join("tbl_projects", "tbl_union.project_id = tbl_projects.id", "left")
            ->where("tbl_union.title != ''");
//        var_dump($this->db->get_compiled_select());
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

    public function addManageTask($arr)
    {
        $this->db->insert('tbl_tasks_complete', $arr);
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
