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

    public function refreshTasks()
    {
        $this->db->select('id, no, title, planner_id, author_id, worker_id');
        $this->db->select('price_detail');
        $this->db->from('tbl_projects');
        $this->db->where("progress = '3'");
        $allProjects = $this->db->get()->result();

        $curMonth = date('Y-m');
        $this->db->select("id, no, title, project_id, author_id");
        $this->db->select("sum(score) as month_score");
        $this->db->from($this->_table_name);
//        $this->db->where("substr(published_at, 1,7) = '{$curMonth}'");
        $this->db->where("info is null");
//        $this->db->group_by("substr(published_at, 1,7)");
        $this->db->group_by("project_id");
        $allTasks = $this->db->get()->result();

        $this->db->select("id, project_id, score");
        $this->db->from($this->_table_name);
//        $this->db->where("substr(create_time, 1,7) = '{$curMonth}'");
        $this->db->where("info = '__manage__'");
        $allManTasks = $this->db->get()->result();

        foreach ($allProjects as $project) {
            // get current month project score;
            $priceDetail = json_decode($project->price_detail);
            $curMonthScore = 0;
            $curMonthScoreOut = 0;
            foreach ($priceDetail as $item) {
//                if (substr($item->created, 0, 7) != $curMonth) continue;
                $curMonthScore += $item->price*1;
                if(isset($item->price_other))
                    $curMonthScoreOut += $item->price_other *1;
            }
            $curMonthScore  = ($curMonthScore *.6 - $curMonthScoreOut)/ 150;

            // get current month task total score;
            $monthTasks = array_values(array_filter($allTasks, function ($task) use ($project) {
                return $task->project_id == $project->id;
            }, ARRAY_FILTER_USE_BOTH));
            $curMonthTaskScore = 0;
            foreach ($monthTasks as $item) {
                $curMonthTaskScore += $item->month_score;
            }
            // calc management task score
            $curManScore = round(($curMonthScore - $curMonthTaskScore) * .3 * 100) / 100;

//            $curManScore = $curMonthTaskScore;
            // update management task score
            $monthManTask = array_values(array_filter($allManTasks, function ($task) use ($project) {
                return $task->project_id == $project->id;
            }, ARRAY_FILTER_USE_BOTH));
            $arr = array(
                'no' => $project->no . '_M',
                'title' => '管理:' . $project->title,
                'project_id' => $project->id,
                'author_id' => $project->worker_id,
                'worker_id' => $project->worker_id,
                'score' => $curManScore,
                'info' => '__manage__',
                'progress' => 3,
                'status' => 1,
                'completed_at' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            );
            if ($monthManTask == null) {
                $arr['published_at'] = date('Y-m-d H:i:s');
                $arr['create_time'] = date('Y-m-d H:i:s');
                $this->insert($arr);
            } else {
                if (floatval($curManScore) == floatval($monthManTask[0]->score)) continue;
                $this->edit($arr, $monthManTask[0]->id);
            }
        }
    }

    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr = '')
    {
        $this->db->select("{$this->_table_name}.*");
        $this->db->select("concat( '', '', {$this->_table_name}.title) as title");
        $this->db->select("tbl_user.name as worker");
        $this->db->select("tbl_projects.title as project");
        $this->db->select("tbl_projects.total_score as total_score");
        $this->db->select("tbl_projects.price_detail as project_price");
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
        $this->db->select("{$this->_table_name}.project_id");
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
        $this->db->group_by('tbl_tasks.project_id');
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
        if ($queryStr != '') {
            $this->db->where("completed_at >= '{$queryStr['range_from']}' " .
                " and completed_at < '{$queryStr['range_to']}'");
        }
        $this->db->where('id < 0');
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
        if ($queryStr != '') {
            $this->db->where("completed_at >= '{$queryStr['range_from']}' " .
                " and completed_at < '{$queryStr['range_to']}'");
        }
        $this->db->where('id < 0');
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
