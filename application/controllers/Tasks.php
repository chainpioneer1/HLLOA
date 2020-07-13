<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller
{

    protected $mainModel;

    function __construct()
    {
        parent::__construct();

        $language = 'chinese';
        $this->load->model("signin_m");
        $this->load->model("userpart_m");
        $this->load->model("userposition_m");
        $this->load->model("userrank_m");
        $this->load->model("userrole_m");
        $this->load->model("projects_m");
        $this->load->model("tasks_m");
        $this->lang->load('main', $language);
        $this->load->library("pagination");
        $this->load->library("session");

        $this->mainModel = $this->tasks_m;
    }

    public function index()
    {
        $this->manage();
    }

    public function manage($menu = 1, $project = 0, $progress = 0)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '任务大厅';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'tasks/manage';
        $this->data['mainModel'] = $model = 'tbl_tasks';
        $this->data["subview"] = $apiRoot;

        $this->data['userList'] = $this->users_m->getItems();

        $this->data['menu'] = $menu;
        $this->data['project'] = $project;
        $this->data['progress'] = $progress;

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        if ($project != 0) $filter[$model . '.project_id'] = $project;
        $filter[$model . '.progress'] = $progress;

        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', array($model . '.progress' => 0));
        }
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 12;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $apiRoot .= "/$menu/$project/$progress";
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 6);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $list = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);
        $this->data["list"] = $list;
        $this->data['progressCnt'] = array(
            $this->mainModel->get_count(array($model . '.progress' => 0), $queryStr),
            $this->mainModel->get_count(array($model . '.progress' => 1), $queryStr),
            $this->mainModel->get_count(array($model . '.progress' => 2), $queryStr),
            $this->mainModel->get_count(array($model . '.progress' => 3), $queryStr)
        );
        $this->data["tbl_content"] = $this->output_content($this->data['list']);


        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content($items)
    {
        $userId = $this->session->userdata("_userid");
        $output = '';
        $priorityStr = ['正常', '重要', '紧急'];
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $editable = ($unit->status == 0);
            $progress = $unit->progress;
            $bgStr = 'url(' . base_url('assets/images/task/bg-task.png') . ')';

            $output .= '<div class="content-item"><div style="background-image:' . $bgStr . ';">';
            $output .= '<div class="btn-transparent" '
                . ' data-id="' . $unit->id . '" '
                . ' onclick="viewItem(this);"></div>';

            $output .= '<div class="project-priority" data-type="' . $unit->priority . '">' . $priorityStr[$unit->priority] . '</div>';
            $output .= '<div class="project-score">' . $unit->score . '</div>';
            $output .= '<div class="project-title">' . $unit->title . '</div>';

            if ($progress >= 0) {
                $output .= '<div>';
                $output .= '<label>任务负责人</label>';
                $output .= '<label>' . $unit->worker . '</label>';
                $output .= '</div>';
            }
            $output .= '<div>';
            $output .= '<label>所属项目</label>';
            $output .= '<label>' . $unit->project . '</label>';
            $output .= '</div>';

            $output .= '<div>';
            $output .= '<label>发布时间</label>';
            $output .= '<label>' . $unit->published_at . '</label>';
            $output .= '</div>';

            $output .= '<div>';
            $output .= '<label>截止时间</label>';
            $output .= '<label>' . $unit->deadline . '</label>';
            $output .= '</div>';
            switch ($progress) {
                case 0:
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-blue" '
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="acceptItem(this);">接收任务</div>';
                    $output .= '</div>';
                    break;
                case 11:
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-blue" '
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="provideItem(this);">提交任务</div>';
                    $output .= '</div>';
                    break;
                case 2:
                case 3:
                    break;
            }

            $output .= '</div></div>';
        endforeach;
        return $output;
    }

    public function mine($menu = 4, $project = 0, $progress = 1)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '我的任务';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'tasks/mine';
        $this->data['mainModel'] = $model = 'tbl_tasks';
        $this->data["subview"] = $apiRoot;

        $this->data['userList'] = $this->users_m->getItems();
        $user_id = $this->session->userdata("_userid");

        $this->data['menu'] = $menu;
        $this->data['project'] = $project;
        $this->data['progress'] = $progress;

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        if ($project != 0) $filter[$model . '.project_id'] = $project;
        $filter[$model . '.progress'] = $progress;
        $filter[$model . '.worker_id'] = $user_id;

        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', array($model . '.progress' => 0));
        }
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 12;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $apiRoot .= "/$menu/$project/$progress";
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 6);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);
        $this->data['progressCnt'] = array(
            $this->mainModel->get_count(array("$model.progress" => 0, "$model.worker_id" => $user_id), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 1, "$model.worker_id" => $user_id), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 2, "$model.worker_id" => $user_id), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 3, "$model.worker_id" => $user_id), $queryStr)
        );
        $this->data["tbl_content"] = $this->output_content_mine($this->data['list']);


        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_mine($items)
    {
        $userId = $this->session->userdata("_userid");
        $output = '';
        $priorityStr = ['正常', '重要', '紧急'];
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $editable = ($unit->status == 0);
            $progress = $unit->progress;
            $bgStr = 'url(' . base_url('assets/images/task/bg-task.png') . ')';

            $output .= '<div class="content-item"><div style="background-image:' . $bgStr . ';">';
            $output .= '<div class="btn-transparent" '
                . ' data-id="' . $unit->id . '" '
                . ' onclick="viewItem(this);"></div>';

            $output .= '<div class="project-priority" data-type="' . $unit->priority . '">' . $priorityStr[$unit->priority] . '</div>';
            $output .= '<div class="project-score">' . $unit->score . '</div>';
            $output .= '<div class="project-title">' . $unit->title . '</div>';

            if ($progress >= 0) {
                $output .= '<div>';
                $output .= '<label>任务负责人</label>';
                $output .= '<label>' . $unit->worker . '</label>';
                $output .= '</div>';
            }
            $output .= '<div>';
            $output .= '<label>所属项目</label>';
            $output .= '<label>' . $unit->project . '</label>';
            $output .= '</div>';

            $output .= '<div>';
            $output .= '<label>发布时间</label>';
            $output .= '<label>' . $unit->published_at . '</label>';
            $output .= '</div>';

            $output .= '<div>';
            $output .= '<label>截止时间</label>';
            $output .= '<label>' . $unit->deadline . '</label>';
            $output .= '</div>';
            switch ($progress) {
                case 0:
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-blue" '
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="acceptItem(this);">接收任务</div>';
                    $output .= '</div>';
                    break;
                case 1:
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-blue" '
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="provideItem(this);">提交任务</div>';
                    $output .= '</div>';
                    break;
                case 2:
                case 3:
                    break;
            }

            $output .= '</div></div>';
        endforeach;
        return $output;
    }

    public function viewlist($menu = 1, $project = 0, $progress = -1)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '任务列表';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'tasks/viewlist';
        $this->data['mainModel'] = $model = 'tbl_tasks';
        $this->data["subview"] = $apiRoot;

        $this->data['userList'] = $this->users_m->getItems();

        $this->data['menu'] = $menu;
        $this->data['project'] = $project;
        $this->data['progress'] = $progress;

        if ($menu == 2) $this->data['title'] = '项目大厅 ＞ 任务列表';
        else if ($menu == 5) $this->data['title'] = '我的项目 ＞ 任务列表';
        else if ($menu == 6) $this->data['title'] = '项目管理 ＞ 任务列表';


        $filter = array();
        if ($this->uri->segment(5) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $startNo = 0;
        if ($this->uri->segment(6) != '') $startNo = $this->uri->segment(6);

        if ($project != 0) $filter[$model . '.project_id'] = $project;
        if ($progress != -1) $filter[$model . '.progress'] = $progress;
        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_project_id'] != '' && $filter[$model . '.project_id'] = $_POST['_project_id'];
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', $filter);
        }
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $this->data['apiRoot'] = $apiRoot .= "/$menu/$project/$progress";
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 6);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $list = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);
        $projectTitle = '';
        foreach ($list as $item) {
            $project_worker = $this->users_m->get_where(array('id' => $item->project_worker_id));
            if ($project_worker != null) $project_worker = $project_worker[0]->name;
            else $project_worker = '';
            $item->project_worker = $project_worker;
            if($item->project) $projectTitle = $item->project;
        }
        $this->data['projectTitle'] = $projectTitle;
        $this->data["list"] = $list;
        $this->data['progressCnt'] = array(
            $this->mainModel->get_count(array("$model.progress" => 0, "$model.project_id" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 1, "$model.project_id" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 2, "$model.project_id" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 3, "$model.project_id" => $project), $queryStr)
        );
        $scoreSum = $this->mainModel->getScoreSum($filter, $queryStr)[0]->scoreSum;
        $this->data["tbl_content"] = $this->output_content_viewlist($this->data['list'], $startNo, $scoreSum);


        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function downloadViewlist($menu = 1, $project = 0, $progress = -1)
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );

        $this->data['title'] = '任务列表';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'tasks/viewlist';
        $this->data['mainModel'] = $model = 'tbl_tasks';
        $this->data["subview"] = $apiRoot;

        $this->data['userList'] = $this->users_m->getItems();

        $this->data['menu'] = $menu;
        $this->data['project'] = $project;
        $this->data['progress'] = $progress = -1;

        if ($menu == 2) $this->data['title'] = '项目大厅 ＞ 任务列表';
        else if ($menu == 5) $this->data['title'] = '我的项目 ＞ 任务列表';
        else if ($menu == 6) $this->data['title'] = '项目管理 ＞ 任务列表';

        $filter = array();
        if ($this->uri->segment(5) == '') $this->session->unset_userdata('filter');
//        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        if ($project != 0) $filter[$model . '.project_id'] = $project;
        if ($progress != -1) $filter[$model . '.progress'] = $progress;
        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_project_id'] != '' && $filter[$model . '.project_id'] = $_POST['_project_id'];
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', $filter);
        }

        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $list = $this->mainModel->getItemsByPage($filter, 0, 1000, $queryStr);
        foreach ($list as $item) {
            $project_worker = $this->users_m->get_where(array('id' => $item->project_worker_id));
            if ($project_worker != null) $project_worker = $project_worker[0]->name;
            else $project_worker = '';
            $item->project_worker = $project_worker;
        }

        $this->data["list"] = $list;

        $ret['data'] = $resultList = $list;
        $ret['status'] = 'success';
        echo json_encode($ret);
    }

    public function output_content_viewlist($items, $startNo = 0, $scoreSum = 0)
    {
        $userId = $this->session->userdata("_userid");
        $progressStr = ["未接收", "进行中", "待验收", "已完成"];
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $startNo++;
            $editable = ($unit->status == 0);
            $output .= '<tr>';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->no . '</td>';
            $output .= '<td>' . $unit->title . '</td>';
            $output .= '<td>' . $unit->worker . '</td>';
            $output .= '<td>' . $unit->score . '</td>';
            $output .= '<td>' . $unit->project . '</td>';
            $output .= '<td>' . $unit->project_worker . '</td>';
            $output .= '<td>' . $progressStr[$unit->progress] . '</td>';
            $output .= '<td>' . ($unit->published_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->started_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->provided_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->completed_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->deadline ?: '- -') . '</td>';
            $output .= '<td>';
            $output .= '<div class="btn-rect btn-green" onclick="viewItem(this);"'
                . ' data-id="' . $unit->id . '" '
                . '>查看</div>';
            $output .= '</td>';
            $output .= '</tr>';
        endforeach;
        $output .= '<tr class="total">'
            . '<td colspan="4">总计</td>'
            . '<td>' . (intval($scoreSum * 100) / 100) . '</td>'
            . '<td colspan="9"></td>'
            . '</tr>';

        return $output;
    }

    public function viewlists($menu = 1, $project = 0, $progress = -1)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '任务列表';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'tasks/viewlists';
        $this->data['mainModel'] = $model = 'tbl_tasks';
        $this->data["subview"] = $apiRoot;

        $this->data['userList'] = $this->users_m->getItems();

        $this->data['menu'] = $menu;
        $this->data['project'] = $project;
        $this->data['progress'] = $progress;

        if ($menu == 2) $this->data['title'] = '项目大厅 ＞ 任务列表';
        else if ($menu == 5) $this->data['title'] = '我的项目 ＞ 任务列表';
        else if ($menu == 6) $this->data['title'] = '项目管理 ＞ 任务列表';


        $filter = array();
        if ($this->uri->segment(5) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $startNo = 0;
        if ($this->uri->segment(6) != '') $startNo = $this->uri->segment(6);

        if ($project != 0) $filter['tbl_projects.pid'] = $project;
        if ($progress != -1) $filter[$model . '.progress'] = $progress;
        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_project_id'] != '' && $filter[$model . '.project_id'] = $_POST['_project_id'];
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', $filter);
        }
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $this->data['apiRoot'] = $apiRoot .= "/$menu/$project/$progress";
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 6);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $list = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);
        foreach ($list as $item) {
            $project_worker = $this->users_m->get_where(array('id' => $item->project_worker_id));
            if ($project_worker != null) $project_worker = $project_worker[0]->name;
            else $project_worker = '';
            $item->project_worker = $project_worker;
        }
        $this->data["list"] = $list;
        $this->data['progressCnt'] = array(
            $this->mainModel->get_count(array("$model.progress" => 0, "tbl_projects.pid" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 1, "tbl_projects.pid" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 2, "tbl_projects.pid" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 3, "tbl_projects.pid" => $project), $queryStr)
        );
        $scoreSum = $this->mainModel->getScoreSum($filter, $queryStr)[0]->scoreSum;
        $this->data["tbl_content"] = $this->output_content_viewlist($this->data['list'], $startNo, $scoreSum);


        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function useraction($menu = 1, $worker = 0)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }
        $progress = 3;
        $this->data['title'] = '绩效中心';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'tasks/useraction';
        $this->data['mainModel'] = $model = 'tbl_tasks';
        $this->data["subview"] = $apiRoot;

        $this->data['userList'] = $this->users_m->getItems();

        $this->data['menu'] = $menu;
        if ($menu == 0) {
            $this->data['title'] = '首页';
            $progress = -1;
        }
        $this->data['worker'] = $worker;
        $this->data['progress'] = $progress;

        $filter = array();

        if ($progress != -1) $filter[$model . '.progress'] = $progress;
        if ($worker == 0) {
            redirect(base_url('users/action'));
            return;
        }
        $filter[$model . '.worker_id'] = $worker;

        $userId = $this->session->userdata('_userid');
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');
        $isBoss = $this->userpart_m->get_where(array('boss_id' => $userId));

        $filter = array();

        $filter['range_from'] = date('Y-m-01 00:00:00');
        $filter['range_to'] = date('Y-m-01 00:00:00', strtotime('+1 months'));
        $startNo = 0;
        if ($this->uri->segment(5) != '') $startNo = $this->uri->segment(5);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $_POST['range_from'] != '' && $filter['range_from'] = $_POST['range_from'];
            $_POST['range_to'] != '' && $filter['range_to'] = $_POST['range_to'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        }

        $queryStr = array(
            'range_from' => $filter['range_from'],
            'range_to' => $filter['range_to'],
        );
        $filterStr = " tbl_union.completed_at >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_union.completed_at < '{$filter['range_to']}'";

        $filterStr .= " and ( tbl_union.worker_id = " . $worker . " ) ";
        if ($progress != -1) $filterStr .= " and tbl_union.progress = " . $progress;

        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['range_from']);
        unset($filter['range_to']);

        if ($this->uri->segment(5) == '') $this->session->unset_userdata('filter');

        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_action_count($filterStr, $queryStr);
        $this->data['apiRoot'] = $apiRoot .= "/$menu/$worker/";
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 5);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $list = $this->mainModel->getActionItemsByPage($filterStr, $ret['pageId'], $ret['cntPerPage'], $queryStr);
//        var_dump($filterStr);
        $resultList = array();
        foreach ($list as $item) {
            $project_worker = $this->users_m->get_where(array('id' => $item->project_worker_id));
            if ($project_worker != null) $project_worker = $project_worker[0]->name;
            else $project_worker = '';
            $item->project_worker = $project_worker;
            if ($item->task_title == '管理:') {
                $item->task_title .= $item->project;
                $item->id .= 'g';
            }
            $item->title = $item->task_title;
            $item->score = $item->user_score;
        }
        $this->data["list"] = $list;
        $this->data['progressCnt'] = array(0, 0, 0, 0
//            $this->mainModel->get_count(array("$model.progress" => 0, "$model.project_id" => $project), $queryStr),
//            $this->mainModel->get_count(array("$model.progress" => 1, "$model.project_id" => $project), $queryStr),
//            $this->mainModel->get_count(array("$model.progress" => 2, "$model.project_id" => $project), $queryStr),
//            $this->mainModel->get_count(array("$model.progress" => 3, "$model.project_id" => $project), $queryStr)
        );
        $this->data["tbl_content"] = $this->output_content_useraction($this->data['list'], $startNo);


        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_useraction($items, $startNo = 0)
    {
        $userId = $this->session->userdata("_userid");
        $progressStr = ["未接收", "进行中", "待验收", "已完成"];
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $startNo++;
            $editable = ($unit->status == 0);
            $isRed = ($unit->deadline < $unit->provided_at);
            $output .= '<tr '
                . ($isRed ? ' style="background-color: rgba(255,0,0,.1);" ' : '')
                . '>';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->no . '</td>';
            $output .= '<td>' . $unit->title . '</td>';
            $output .= '<td>' . $unit->worker . '</td>';
            $output .= '<td>' . $unit->project . '</td>';
            $output .= '<td>' . $unit->project_worker . '</td>';
            $output .= '<td>' . $unit->score . '</td>';
            $output .= '<td>' . $progressStr[$unit->progress] . '</td>';
            $output .= '<td>' . ($unit->published_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->started_at ?: '- -') . '</td>';
            $output .= '<td '
                . ($isRed ? ' style="color: rgba(255,0,0,1);" ' : '')
                . '>' . ($unit->provided_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->completed_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->deadline ?: '- -') . '</td>';
            $output .= '<td>';
            $output .= '<div class="btn-rect btn-orange" onclick="viewItem(this);"'
                . ' data-id="' . $unit->id . '" '
                . '>查看</div>';
            $output .= '</td>';
            $output .= '</tr>';
        endforeach;
        return $output;
    }

    public function editable($menu = 1, $project = 0, $progress = -1)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '任务列表';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'tasks/editable';
        $this->data['mainModel'] = $model = 'tbl_tasks';
        $this->data["subview"] = $apiRoot;

        $this->data['userList'] = $this->users_m->getItems();

        $this->data['menu'] = $menu;
        $this->data['project'] = $project;
        $this->data['progress'] = $progress;

        if ($menu == 5) $this->data['title'] = '我的项目 ＞ 任务列表';

        $filter = array();
        if ($this->uri->segment(5) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        if ($project != 0) {
            $filter[$model . '.project_id'] = $project;
            $this->data['taskList'] = $this->tasks_m->getItems(array($model . '.project_id' => $project));
        } else {
            $this->data['taskList'] = $this->tasks_m->getItems();
        }
        if ($progress != -1) $filter[$model . '.progress'] = $progress;
        $startNo = 0;
        if ($this->uri->segment(6) != '') $startNo = $this->uri->segment(6);
        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_project_id'] != '' && $filter[$model . '.project_id'] = $_POST['_project_id'];
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', $filter);
        }
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $this->data['apiRoot'] = $apiRoot .= "/$menu/$project/$progress";
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 6);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $list = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);
        foreach ($list as $item) {
            $project_worker = $this->users_m->get_where(array('id' => $item->project_worker_id));
            if ($project_worker != null) $project_worker = $project_worker[0]->name;
            else $project_worker = '';
            $item->project_worker = $project_worker;
        }
        $this->data["list"] = $list;
        $this->data['progressCnt'] = array(
            $this->mainModel->get_count(array("$model.progress" => 0, "$model.project_id" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 1, "$model.project_id" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 2, "$model.project_id" => $project), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 3, "$model.project_id" => $project), $queryStr)
        );
        $scoreSum = $this->mainModel->getScoreSum($filter, $queryStr)[0]->scoreSum;
        $this->data["tbl_content"] = $this->output_content_editable($this->data['list'], $startNo, $scoreSum);
        $this->data["projectItem"] = array();
        if ($project > 0) {
            $this->data["projectItem"] = $this->projects_m->get_where(array('id' => $project));
            if (!$this->data['taskList'])
                $this->projects_m->edit(array('progress' => 0, 'started_at' => null), $project);
        }

        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_editable($items, $startNo = 0, $scoreSum = 0)
    {
        $userId = $this->session->userdata("_userid");
        $progressStr = ["未接收", "进行中", "待验收", "已完成"];
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $startNo++;
            $editable = ($unit->progress == 0 || $unit->progress == 1);
            $deletable = $unit->progress == 0;
            $completable = $unit->progress == 2;
            $output .= '<tr>';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->no . '</td>';
            $output .= '<td>' . $unit->title . '</td>';
            $output .= '<td>' . $unit->worker . '</td>';
            $output .= '<td>' . $unit->score . '</td>';
            $output .= '<td>' . $unit->project . '</td>';
            $output .= '<td>' . $unit->project_worker . '</td>';
            $output .= '<td>' . $progressStr[$unit->progress] . '</td>';
            $output .= '<td>' . ($unit->published_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->started_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->provided_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->completed_at ?: '- -') . '</td>';
            $output .= '<td>' . ($unit->deadline ?: '- -') . '</td>';
            $output .= '<td>';
            if ($deletable) {
                $output .= '<div class="btn-rect btn-red ' . ($deletable ? '' : 'btn-disabled') . '" '
                    . ($deletable ? ' onclick="deleteItem(this);" ' : '')
                    . ($deletable ? ' data-id="' . $unit->id . '" ' : '')
                    . '>删除</div>';
            }
            if ($editable) {
                $output .= '<div class="btn-rect btn-green ' . ($editable ? '' : 'btn-disabled') . '" '
                    . ($editable ? ' onclick="editItem(this);" ' : '')
                    . ($editable ? ' data-id="' . $unit->id . '" ' : '')
                    . '>编辑</div>';
            }
            if ($completable) {
                $output .= '<div class="btn-rect btn-green ' . ($completable ? '' : 'btn-disabled') . '" '
                    . ($completable ? ' onclick="completeItem(this);" ' : '')
                    . ($completable ? ' data-id="' . $unit->id . '" ' : '')
                    . '>验收</div>';
            }
            $output .= '<div class="btn-rect btn-orange" onclick="viewItem(this);"'
                . ' data-id="' . $unit->id . '" '
                . '>查看</div>';
            $output .= '</td>';
            $output .= '</tr>';
        endforeach;
        $output .= '<tr class="total">'
            . '<td colspan="4">总计</td>'
            . '<td>' . (intval($scoreSum * 100) / 100) . '</td>'
            . '<td colspan="9"></td>'
            . '</tr>';
        return $output;
    }

    public function updateItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole()) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        $user_id = $this->session->userdata('_userid');
        if ($_POST) {
            $id = $this->input->post('id');
            $project_id = $this->input->post('project_id');
            $worker_id = $this->input->post('worker_id');
            $editArr = array(
                'author_id' => $user_id,

                'no' => $this->input->post('no'),
                'title' => $this->input->post('title'),
                'project_id' => $project_id,
                'worker_id' => $worker_id,
                'deadline' => $this->input->post('deadline'),
                'score' => $this->input->post('score'),
                'priority' => $this->input->post('priority'),
                'description' => $this->input->post('description'),
                'progress' => (($worker_id) ? 1 : 0),
                'status' => 1,
                'update_time' => date("Y-m-d H:i:s")
            );
            if ($worker_id) {
                $editArr['started_at'] = date("Y-m-d H:i:s");
            } else {
                $editArr['started_at'] = null;
                $editArr['worker_id'] = null;
            }

            $result = 0;
            if ($id == 0) {
                $this->projects_m->edit(array(
                    'started_at' => date('Y-m-d H:i:s'),
                    'update_time' => date('Y-m-d H:i:s'),
                    'progress' => 1
                ), $project_id);
                $editArr['published_at'] = date("Y-m-d H:i:s");
                $editArr['create_time'] = date("Y-m-d H:i:s");
                $result = $id = $this->mainModel->add($editArr);
            } else {
                $updateItem = $this->mainModel->get_where(array('id' => $id));
                if ($updateItem != null) {
                    $progress = $updateItem[0]->progress;
                    if ($progress == 1 || $progress == 2) {
                        $editArr = array(
                            'worker_id' => $worker_id,
                            'score' => $this->input->post('score'),
                            'priority' => $this->input->post('priority'),
                            'deadline' => $this->input->post('deadline'),
                            'description' => $this->input->post('description'),
                            'update_time' => date("Y-m-d H:i:s")
                        );
                        if (!$worker_id) {
                            $editArr['progress'] = 0;
                            $editArr['started_at'] = null;
                        }
                    }
                    $result = $this->mainModel->edit($editArr, $id);
                }
            }
            if ($result > 0) {
                $ret['item'] = $this->mainModel->get_where(array('id' => $id));
            }
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }

        echo json_encode($ret);
    }

    public function publishItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole()) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $status = $_POST['status'];
            if ($id < 0) {
                $filter = array();
                $this->session->userdata('filter') != null && $filter = $this->session->userdata('filter');
                $pageId = 0;
                if (isset($_POST['pageId'])) $pageId = $_POST['pageId'];
                $perPage = PERPAGE;
                $lists = $this->mainModel->getItemsByPage($filter, $pageId, $perPage);
                foreach ($lists as $item) $this->mainModel->publish($item->id, $status);
            } else {
                $this->mainModel->publish($id, $status);
            }
            $ret['data'] = $ret['data'] = '操作成功';//$this->output_content($items);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    public function deleteItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole()) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $taskItem = $this->mainModel->get_where(array('id' => $id));
            $list = $this->mainModel->delete($id);
            $allTasks = $this->mainModel->get_where(array('project_id' => $taskItem->project_id));
            if (!$allTasks) {
                $this->projects_m->edit(array('progress' => 0, 'started_at' => null), $taskItem->project_id);
            }
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    public function acceptItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole()) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $userId = $this->session->userdata("_userid");
            $result = $this->mainModel->edit(array(
                'progress' => 1,
                'worker_id' => $userId,
                'started_at' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ), $id);
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    public function provideItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole()) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $userId = $this->session->userdata("_userid");
            $result = $this->mainModel->edit(array(
                'progress' => 2,
                'provided_at' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ), $id);
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    public function completeItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole()) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $userId = $this->session->userdata("_userid");
            $result = $this->mainModel->edit(array(
                'progress' => 3,
                'completed_at' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ), $id);
            $taskItem = $this->mainModel->get_where(array('id' => $id));
            $taskItem = $taskItem[0];
            $taskItem->title = "管理:" . $taskItem->title;
            $taskItem->worker_id = $taskItem->author_id;
            $taskItem->score *= 0.08;
            $this->mainModel->addManageTask($taskItem);
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    public function rejectItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole()) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $userId = $this->session->userdata("_userid");
            $result = $this->mainModel->edit(array(
                'progress' => 1,
                'provided_at' => null,
                'update_time' => date('Y-m-d H:i:s')
            ), $id);
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function checkRole($id = -1)
    {
        if (!$this->signin_m->isloggedin()) return false;

        if ($id == -1) return true;

        $permission = $this->session->userdata('_permission');
        if ($permission != NULL) {
            $permissionData = (array)(json_decode($permission));
            $accessInfo = $permissionData['m' . $id];
            if ($accessInfo == '1') return true;
            else return false;
        }
        return false;
    }
}

?>