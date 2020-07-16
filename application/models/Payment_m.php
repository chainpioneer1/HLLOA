<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_m extends MY_Model
{
    protected $_table_name = 'tbl_payment';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_payment.create_time desc";

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

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '',
                                   $rangeFrom = '', $rangeTo = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("tbl_projects.id as project_id");
        $this->db->select("tbl_projects.no as project_no");
        $this->db->select("tbl_projects.title as project");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.title like '%{$queryStr}%' "
                . "or {$this->_table_name}.bank_account like '%{$queryStr}%' "
                . "or {$this->_table_name}.bank_name like '%{$queryStr}%' "
                . "or {$this->_table_name}.bank_user like '%{$queryStr}%' "
                . "or tbl_projects.title like '%{$queryStr}%' "
                . "or tbl_projects.no like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_projects", "{$this->_table_name}.project_id = tbl_projects.id", "left");
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
//        $this->db->order_by('tbl_user_part.title', 'asc');
        $this->db->order_by($this->_order_by);
        $this->db->limit($cntPerPage, $pageId);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_count($arr = array(), $queryStr = '',
                              $rangeFrom = '', $rangeTo = '')
    {
        $this->db->select("{$this->_table_name}.id");
        $this->db->where($arr);
        if ($queryStr != '') {
            $this->db->where(
                "( {$this->_table_name}.title like '%{$queryStr}%' "
                . "or {$this->_table_name}.bank_account like '%{$queryStr}%' "
                . "or {$this->_table_name}.bank_name like '%{$queryStr}%' "
                . "or {$this->_table_name}.bank_user like '%{$queryStr}%' "
                . "or tbl_projects.title like '%{$queryStr}%' "
                . "or tbl_projects.no like '%{$queryStr}%' )"
            );
        }
        $this->db->from($this->_table_name)
            ->join("tbl_projects", "{$this->_table_name}.project_id = tbl_projects.id", "left");
//        $this->db->where("tbl_user_position.status", 1)
//            ->where("tbl_user_part.status", 1)
//            ->where("tbl_user_rank.status", 1);
//        $this->db->order_by('tbl_user_part.title', 'asc');
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
