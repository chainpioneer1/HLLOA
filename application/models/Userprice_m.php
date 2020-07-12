<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Userprice_m extends MY_Model
{
    protected $_table_name = 'tbl_user_price';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_user_price.id asc";

    function __construct()
    {
        parent::__construct();
    }

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("tbl_user.name");
        $this->db->select("tbl_user_part.id as part_id");
        $this->db->select("tbl_user_part.title as part");
        $this->db->select("tbl_user_position.id as position_id");
        $this->db->select("tbl_user_position.title as position");
        $this->db->select("tbl_user_rank.id as rank_id");
        $this->db->select("tbl_user_rank.title as rank");
        $this->db->select("tbl_user_role.title as role");
        $this->db->select("tbl_user_role.permission as permission");
        $this->db->select("{$this->_table_name}.*");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.refdate like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.user_id = tbl_user.id", "left")
            ->join("tbl_user_role", "tbl_user.role_id = tbl_user_role.id", "left")
            ->join("tbl_user_position", "tbl_user.position_id = tbl_user_position.id", "left")
            ->join("tbl_user_rank", "tbl_user.rank_id = tbl_user_rank.id", "left")
            ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");
//        $this->db->where("tbl_user.is_calc_score", 1);

        $this->db->order_by($this->_order_by);
//        $this->db->limit($cntPerPage, $pageId);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_count($arr = array(), $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.id");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.refdate like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user", "{$this->_table_name}.user_id = tbl_user.id", "left")
            ->join("tbl_user_role", "tbl_user.role_id = tbl_user_role.id", "left")
            ->join("tbl_user_position", "tbl_user.position_id = tbl_user_position.id", "left")
            ->join("tbl_user_rank", "tbl_user.rank_id = tbl_user_rank.id", "left")
            ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");

        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getItems($arr = array())
    {
        return $this->getItemsByPage($arr, 0, 100000);
    }

    public function getUserSalaryByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '', $range_from, $range_to)
    {
        $this->db->from('tbl_tasks');
        $subQuery1 = $this->db->get_compiled_select();

        $this->db->from("tbl_tasks_complete");
        $subQuery2 = $this->db->get_compiled_select();
        $unionQuery = "(";
        for ($i = 0; $i < 100; $i++) {
            $from = $this->add_date($range_from, $i);
            if ($from >= $range_to) break;
            $to = $this->add_date($range_from, $i + 1);
            $dateQry = " tbl_union.completed_at >= '{$from}' ";
            $dateQry .= " and tbl_union.completed_at < '{$to}' ";
            $this->db->where($dateQry);
            $this->db->select("tbl_user.id");
            $this->db->select("tbl_user.name");
            $this->db->select("tbl_user.avatar");
            $this->db->select("tbl_user.role_id");
            $this->db->select("tbl_user.position_id");
            $this->db->select("tbl_user.rank_id");
            $this->db->select("(sum(tbl_union.score)) as user_score");
            $this->db->select("concat( year(tbl_union.completed_at), '-', month(tbl_union.completed_at)) as task_completed");
            $this->db->select("tbl_user_part.title as part");
            $this->db->select("tbl_user_position.part_id");
            $this->db->select("tbl_user_position.title as position");
            $this->db->select("tbl_user_rank.title as rank");
            $this->db->select("tbl_user_rank.gangwei_price");
            $this->db->select("tbl_user_rank.jixiao_price");
            $this->db->select("tbl_user_rank.standard_factor");
            $this->db->select("tbl_user_rank.rank_factor");
            $this->db->select("tbl_user_price.refdate");
            $this->db->select("tbl_user_price.e");
            $this->db->select("tbl_user_price.f");
            $this->db->select("tbl_user_price.g");
            $this->db->select("tbl_user_price.h");
            $this->db->select("tbl_user_price.i");
            $this->db->select("tbl_user_price.j");
            $this->db->select("tbl_user_price.k");
            $this->db->select("tbl_user_price.l");
            $this->db->select("tbl_user_price.m");
            $this->db->select("tbl_user_price.n");
            $this->db->select("tbl_user_price.o");
            $this->db->select("tbl_user_price.p");
            $this->db->select("tbl_user_price.q");
            $this->db->select("tbl_user_price.r");
            $this->db->select("tbl_user_price.s");
            $this->db->select("tbl_user_price.workdays");
            $this->db->where($arr);
            if ($queryStr != '') {
                $this->db->where(
                    "( tbl_user.account like '%{$queryStr}%' "
                    . "or tbl_user.name like '%{$queryStr}%' "
                    . "or tbl_user.email like '%{$queryStr}%' "
                    . "or tbl_user.phone like '%{$queryStr}%' "
                    . "or tbl_union.title like '%{$queryStr}%' "
                    . "or tbl_user_part.title like '%{$queryStr}%' "
                    . "or tbl_user_position.title like '%{$queryStr}%' "
                    . "or tbl_user_rank.title like '%{$queryStr}%' )"
                );
            }
            $this->db->from('tbl_user')
                ->join("($subQuery1 union $subQuery2) as tbl_union", "tbl_user.id = tbl_union.worker_id", "left")
                ->join("tbl_user_position", "tbl_user.position_id = tbl_user_position.id", "left")
                ->join("tbl_user_rank", "tbl_user.rank_id = tbl_user_rank.id", "left")
                ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left")
                ->join("tbl_user_price",
                    "tbl_user_price.user_id = tbl_user.id and " .
                    "tbl_user_price.refdate = '" . substr($from, 0, 10) . "'", "left");
//            $this->db->where("tbl_user.is_calc_score", 1);

            $this->db->group_by("tbl_user.id");
            if ($i > 0) $unionQuery .= " union ";
            $unionQuery .= " (" . $this->db->get_compiled_select() . ") ";
        }
        $unionQuery .= ") as tbl_all";
//        var_dump($unionQuery);
        $this->db->select('*');
        $this->db->from($unionQuery);
        $this->db->order_by("tbl_all.task_completed desc");
        $this->db->order_by("tbl_all.user_score desc");
        $this->db->limit($cntPerPage, $pageId);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_salary_count($arr = array(), $queryStr = '', $range_from, $range_to)
    {
        $this->db->from('tbl_tasks');
        $subQuery1 = $this->db->get_compiled_select();

        $this->db->from("tbl_tasks_complete");
        $subQuery2 = $this->db->get_compiled_select();
        $unionQuery = "(";
        for ($i = 0; $i < 100; $i++) {
            $from = $this->add_date($range_from, $i);
            if ($from >= $range_to) break;
            $to = $this->add_date($range_from, $i + 1);
            $dateQry = " tbl_union.completed_at >= '{$from}' ";
            $dateQry .= " and tbl_union.completed_at < '{$to}' ";
            $this->db->where($dateQry);
            $this->db->select("(sum(tbl_union.score)) as user_score");
            $this->db->select("concat( year(tbl_union.completed_at), '-', month(tbl_union.completed_at)) as task_completed");
            $this->db->where($arr);
            if ($queryStr != '') {
                $this->db->where(
                    "( tbl_user.account like '%{$queryStr}%' "
                    . "or tbl_user.name like '%{$queryStr}%' "
                    . "or tbl_user.email like '%{$queryStr}%' "
                    . "or tbl_user.phone like '%{$queryStr}%' "
                    . "or tbl_union.title like '%{$queryStr}%' "
                    . "or tbl_user_part.title like '%{$queryStr}%' "
                    . "or tbl_user_position.title like '%{$queryStr}%' "
                    . "or tbl_user_rank.title like '%{$queryStr}%' )"
                );
            }
            $this->db->from('tbl_user')
                ->join("($subQuery1 union $subQuery2) as tbl_union", "tbl_user.id = tbl_union.worker_id", "left")
                ->join("tbl_user_position", "tbl_user.position_id = tbl_user_position.id", "left")
                ->join("tbl_user_rank", "tbl_user.rank_id = tbl_user_rank.id", "left")
                ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");
//            $this->db->where("tbl_user.is_calc_score", 1);
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
            $this->db->group_by("tbl_user.id");
            if ($i > 0) $unionQuery .= " union \n";
            $unionQuery .= " (" . $this->db->get_compiled_select() . ")";
        }
        $unionQuery .= ") as tbl_all";
        $this->db->select('*');
        $this->db->from($unionQuery);
        $this->db->order_by("tbl_all.task_completed desc");
        $this->db->order_by("tbl_all.user_score desc");
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
