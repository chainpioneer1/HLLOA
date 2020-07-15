<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller
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
        $this->load->model("contracts_m");
        $this->load->model("projects_m");
        $this->load->model("tasks_m");
        $this->lang->load('main', $language);
        $this->load->library("pagination");
        $this->load->library("session");

        $this->mainModel = $this->projects_m;
    }

    public function index()
    {
        $this->manage();
    }

    public function plan($menu = 15, $progress = 0)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '项目统筹';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'projects/plan';
        $this->data['mainModel'] = $model = 'tbl_projects';
        $this->data["subview"] = $apiRoot;

        $this->data['menu'] = $menu;
        $this->data['userList'] = $this->users_m->getItems();
        $this->data['contractList'] = $this->contracts_m->getItems();

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $this->data['progress'] = $filter['tbl_union.progress1'] = $progress;
        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['search_part'] != '' && $filter['tbl_union.part_id'] = $_POST['search_part'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', array($model . '.progress' => 0));
        }
        $queryStr = $filter['queryStr'] . '';
        if (isset($filter['tbl_projects.pid']))
            unset($filter['tbl_projects.pid']);
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 12;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_group_count($filter, $queryStr);
        $apiRoot .= "/$menu/$progress";
        $this->data['apiRoot'] = $apiRoot;
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 5);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data['list'] = $list = $this->mainModel->getGroupItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);
        $this->data['progressCnt'] = array(
            $this->mainModel->get_group_count(array('tbl_union.progress1' => 0), $queryStr),
            $this->mainModel->get_group_count(array('tbl_union.progress1' => 1), $queryStr),
            $this->mainModel->get_group_count(array('tbl_union.progress1' => 2), $queryStr),
            $this->mainModel->get_group_count(array('tbl_union.progress1' => 3), $queryStr)
        );
        $this->data["tbl_content"] = $this->output_content_plan($list);

        $projIds = array();
        foreach ($list as $item) {
            $ids = explode(',', $item->projIds);
            foreach ($ids as $id) {
                array_push($projIds, $id);
            }
        }
        $this->data['taskList'] = $this->tasks_m->get_where_in('project_id', $projIds);

        if (!$this->checkRole(6)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_plan($items)
    {
        $userId = $this->session->userdata("_userid");
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $editable = ($unit->status == 0);
            $progress = $unit->progress;
            $bgStr = 'url(' . base_url('assets/images/project/bg' . ($unit->id % 11 + 1) . '.jpg') . ')';

            $output .= '<div class="content-item"><div style="background-image:' . $bgStr . ';">';
            $output .= '<div class="btn-transparent" '
                . ' data-pid="' . $unit->pid . '" '
                . ' onclick="viewItem(this);"></div>';

            $output .= '<div class="project-score">' . $unit->total_score . '</div>';
            $output .= '<div class="project-title">' . $unit->title . '</div>';

            $output .= '<div>';
            $output .= '<label>项目负责人</label>';
            $output .= '<label>' . str_replace(',', ';', $unit->worker) . '</label>';
            $output .= '</div>';
            switch ($progress) {
                case 0:
                    $output .= '<div>';
                    $output .= '<label>发布时间</label>';
                    $output .= '<label>' . $unit->published_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-pid="' . $unit->pid . '"'
                        . ' onclick="deleteItem(this);">删除项目</div>';
                    $output .= '<div class="btn-rect btn-white"'
                        . ' data-pid="' . $unit->pid . '"'
                        . ' onclick="editItem(this);">编辑项目</div>';
                    $output .= '</div>';
                    break;
                case 1:
                    $output .= '<div>';
                    $output .= '<label>新建时间</label>';
                    $output .= '<label>' . $unit->create_time . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
//                    $output .= '<div class="btn-rect btn-white" '
//                        . ' data-pid="' . $unit->pid . '"'
//                        . ' onclick="viewTasks(this);">查看任务</div>';
                    $output .= '<div class="btn-rect btn-white"'
                        . ' data-pid="' . $unit->pid . '"'
                        . ' style="width: 200px;" '
                        . ' onclick="editItem(this);">编辑项目</div>';
                    $output .= '</div>';
                    break;
                case 2:
                    $output .= '<div>';
                    $output .= '<label>新建时间</label>';
                    $output .= '<label>' . $unit->create_time . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
//                    $output .= '<div class="project-btns">';
//                    $output .= '<div class="btn-rect btn-white" '
//                        . ' data-pid="' . $unit->pid . '"'
//                        . ' onclick="viewTasks(this);">查看任务</div>';
//                    $output .= '<div class="btn-rect btn-white"'
//                        . ' data-pid="' . $unit->pid . '"'
//                        . ' onclick="completeItem(this);">确认验收</div>';
//                    $output .= '</div>';
                    break;
                case 3:
                    $output .= '<div>';
                    $output .= '<label>新建时间</label>';
                    $output .= '<label>' . $unit->create_time . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
//                    $output .= '<div class="project-btns">';
//                    $output .= '<div class="btn-rect btn-white" '
//                        . ' data-pid="' . $unit->pid . '"'
//                        . ' style="width: 200px;" '
//                        . ' onclick="viewTasks(this);">查看任务</div>';
//                    $output .= '</div>';
                    break;
            }

            $output .= '</div></div>';
        endforeach;
        return $output;
    }

    public function manage($menu = 6, $progress = 0)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '项目管理';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'projects/manage';
        $this->data['mainModel'] = $model = 'tbl_projects';
        $this->data["subview"] = $apiRoot;

        $this->data['menu'] = $menu;
        $this->data['userList'] = $this->users_m->getItems();

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $this->data['progress'] = $filter['tbl_union.progress1'] = $progress;
        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['search_part'] != '' && $filter['tbl_union.part_id'] = $_POST['search_part'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', array($model . '.progress' => 0));
        }
        $queryStr = $filter['queryStr'] . '';
        if (isset($filter['tbl_projects.pid']))
            unset($filter['tbl_projects.pid']);
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 12;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_group_count($filter, $queryStr);
        $apiRoot .= "/$menu/$progress";
        $this->data['apiRoot'] = $apiRoot;
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 5);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data['list'] = $list = $this->mainModel->getGroupItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);
        $this->data['progressCnt'] = array(
            $this->mainModel->get_group_count(array('tbl_union.progress1' => 0), $queryStr),
            $this->mainModel->get_group_count(array('tbl_union.progress1' => 1), $queryStr),
            $this->mainModel->get_group_count(array('tbl_union.progress1' => 2), $queryStr),
            $this->mainModel->get_group_count(array('tbl_union.progress1' => 3), $queryStr)
        );
        $this->data["tbl_content"] = $this->output_content($list);

        $projIds = array();
        foreach ($list as $item) {
            $ids = explode(',', $item->projIds);
            foreach ($ids as $id) {
                array_push($projIds, $id);
            }
        }
        $this->data['taskList'] = $this->tasks_m->get_where_in('project_id', $projIds);

        if (!$this->checkRole(6)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content($items)
    {
        $userId = $this->session->userdata("_userid");
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $editable = ($unit->status == 0);
            $progress = $unit->progress;
            $bgStr = 'url(' . base_url('assets/images/project/bg' . ($unit->id % 11 + 1) . '.jpg') . ')';

            $output .= '<div class="content-item"><div style="background-image:' . $bgStr . ';">';
            $output .= '<div class="btn-transparent" '
                . ' data-pid="' . $unit->pid . '" '
                . ' onclick="viewItem(this);"></div>';

            $output .= '<div class="project-score">' . $unit->total_score . '</div>';
            $output .= '<div class="project-title">' . $unit->title . '</div>';

            $output .= '<div>';
            $output .= '<label>项目负责人</label>';
            $output .= '<label>' . str_replace(',', ';', $unit->worker) . '</label>';
            $output .= '</div>';
            switch ($progress) {
                case 0:
                    $output .= '<div>';
                    $output .= '<label>发布时间</label>';
                    $output .= '<label>' . $unit->published_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white"'
                        . ' data-pid="' . $unit->pid . '"'
                        . ' style="width: 200px;" '
                        . ' onclick="editItem(this);">指派人员</div>';
                    $output .= '</div>';
                    break;
                case 1:
                    $output .= '<div>';
                    $output .= '<label>开始时间</label>';
                    $output .= '<label>' . $unit->started_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-pid="' . $unit->pid . '"'
                        . ' style="width: 200px;" '
                        . ' onclick="viewTasks(this);">查看任务</div>';
//                    $output .= '<div class="btn-rect btn-white"'
//                        . ' data-pid="' . $unit->pid . '"'
//                        . ' onclick="editItem(this);">编辑项目</div>';
                    $output .= '</div>';
                    break;
                case 2:
                    $output .= '<div>';
                    $output .= '<label>开始时间</label>';
                    $output .= '<label>' . $unit->started_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-pid="' . $unit->pid . '"'
                        . ' onclick="viewTasks(this);">查看任务</div>';
                    $output .= '<div class="btn-rect btn-white"'
                        . ' data-pid="' . $unit->pid . '"'
                        . ' onclick="completeItem(this);">确认验收</div>';
                    $output .= '</div>';
                    break;
                case 3:
                    $output .= '<div>';
                    $output .= '<label>开始时间</label>';
                    $output .= '<label>' . $unit->started_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-pid="' . $unit->pid . '"'
                        . ' style="width: 200px;" '
                        . ' onclick="viewTasks(this);">查看任务</div>';
                    $output .= '</div>';
                    break;
            }

            $output .= '</div></div>';
        endforeach;
        return $output;
    }

    public function hall($menu = 2, $progress = 0)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '项目大厅';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'projects/hall';
        $this->data['mainModel'] = $model = 'tbl_projects';
        $this->data["subview"] = $apiRoot;

        $this->data['menu'] = $menu;
        $this->data['userList'] = $this->users_m->getItems();

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $this->data['progress'] = $filter[$model . '.progress'] = $progress;
        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['search_part'] != '' && $filter['tbl_user.part_id'] = $_POST['search_part'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', array($model . '.progress' => 0));
        }
        $queryStr = $filter['queryStr'] . '';
        if (isset($filter['tbl_tasks.project_id']))
            unset($filter['tbl_tasks.project_id']);
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 12;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $apiRoot .= "/$menu/$progress";
        $this->data['apiRoot'] = $apiRoot;
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 5);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);
        $this->data['progressCnt'] = array(
            $this->mainModel->get_count(array($model . '.progress' => 0), $queryStr),
            $this->mainModel->get_count(array($model . '.progress' => 1), $queryStr),
            $this->mainModel->get_count(array($model . '.progress' => 2), $queryStr),
            $this->mainModel->get_count(array($model . '.progress' => 3), $queryStr)
        );
        $projIds = array();
        foreach ($list as $item) array_push($projIds, $item->id);
        $this->data['taskList'] = $taskList = $this->tasks_m->get_where_in('project_id', $projIds);

        $this->data["tbl_content"] = $this->output_content_hall($this->data['list'], $taskList);

        if (!$this->checkRole(2)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_hall($items, $taskList = array())
    {
        $userId = $this->session->userdata("_userid");
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $editable = ($unit->status == 0);
            $progress = $unit->progress;
            $bgStr = 'url(' . base_url('assets/images/project/bg' . ($unit->id % 11 + 1) . '.jpg') . ')';
            $task_score = 0;
            foreach ($taskList as $taskItem) {
                if ($taskItem->project_id != $unit->id) continue;
                $task_score += floatval($taskItem->score);
            }
            $task_score = 0;

            $output .= '<div class="content-item"><div style="background-image:' . $bgStr . ';">';
            $output .= '<div class="btn-transparent" '
                . ' data-id="' . $unit->id . '" '
                . ' onclick="viewItem(this);"></div>';

            $output .= '<div class="project-score">' . round(($unit->total_score - $task_score) * 100) / 100 . '</div>';
            $output .= '<div class="project-title">' . $unit->title . '</div>';

            $output .= '<div>';
            $output .= '<label>项目负责人</label>';
            $output .= '<label>' . $unit->worker . '</label>';
            $output .= '</div>';
            switch ($progress) {
                case 0:
                    $output .= '<div>';
                    $output .= '<label>发布时间</label>';
                    $output .= '<label>' . $unit->published_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    break;
                case 1:
                case 2:
                case 3:
                    $output .= '<div>';
                    $output .= '<label>开始时间</label>';
                    $output .= '<label>' . $unit->started_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-id="' . $unit->id . '" '
                        . ' style="width: 200px;" '
                        . ' onclick="viewTasks(this);">查看任务</div>';
                    $output .= '</div>';
                    break;
            }

            $output .= '</div></div>';
        endforeach;
        return $output;
    }

    public function mine($menu = 5, $progress = 1)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '我的项目';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'projects/mine';
        $this->data['mainModel'] = $model = 'tbl_projects';
        $this->data["subview"] = $apiRoot;

        $this->data['menu'] = $menu;
        $this->data['progress'] = $progress;

        $this->data['userList'] = $this->users_m->getItems();
        $user_id = $this->session->userdata("_userid");

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $filter[$model . '.progress'] = $progress;
        $filter[$model . '.worker_id'] = $user_id;
        if ($_POST) {
            $this->session->unset_userdata('filter');
//            $_POST['_progress'] != '' && $filter[$model . '.progress'] = $_POST['_progress'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['search_part'] != '' && $filter['tbl_user.part_id'] = $_POST['search_part'];
            $this->session->set_userdata('filter', $filter);
        } else {
//            $this->session->set_userdata('filter', array($model . '.progress' => 0));
        }
        $queryStr = $filter['queryStr'] . '';
        if (isset($filter['tbl_tasks.project_id']))
            unset($filter['tbl_tasks.project_id']);
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 12;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $apiRoot .= "/$menu/$progress";
        $this->data['apiRoot'] = $apiRoot;
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 5);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);

        $this->data['progressCnt'] = array(
            $this->mainModel->get_count(array("$model.progress" => 0, "$model.worker_id" => $user_id), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 1, "$model.worker_id" => $user_id), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 2, "$model.worker_id" => $user_id), $queryStr),
            $this->mainModel->get_count(array("$model.progress" => 3, "$model.worker_id" => $user_id), $queryStr)
        );

        $projIds = array();
        foreach ($list as $item) array_push($projIds, $item->id);
        $this->data['taskList'] = $taskList = $this->tasks_m->get_where_in('project_id', $projIds);

        $this->data["tbl_content"] = $this->output_content_mine($this->data['list'], $taskList);

        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_mine($items, $taskList = array())
    {
        $userId = $this->session->userdata("_userid");
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $editable = ($unit->status == 0);
            $progress = $unit->progress;
            $bgStr = 'url(' . base_url('assets/images/project/bg' . ($unit->id % 11 + 1) . '.jpg') . ')';

            $task_score = 0;
            foreach ($taskList as $taskItem) {
                if ($taskItem->project_id != $unit->id) continue;
                $task_score += floatval($taskItem->score);
            }
            $projScore = round(($unit->total_score - $task_score) * 100) / 100;

            $output .= '<div class="content-item"><div style="background-image:' . $bgStr . ';">';
            $output .= '<div class="btn-transparent" '
                . ' data-id="' . $unit->id . '" '
                . ' onclick="viewItem(this);"></div>';

            $output .= '<div class="project-score">' . (($projScore != 0) ? $projScore : 0) . '</div>';
            $output .= '<div class="project-title">' . $unit->title . '</div>';

            $output .= '<div>';
            $output .= '<label>项目负责人</label>';
            $output .= '<label>' . $unit->worker . '</label>';
            $output .= '</div>';
            switch ($progress) {
                case 0:
                    $output .= '<div>';
                    $output .= '<label>发布时间</label>';
                    $output .= '<label>' . $unit->published_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-id="' . $unit->id . '" '
                        . ' style="width: 200px;" '
                        . ' onclick="editItem(this);">新增任务</div>';
                    $output .= '</div>';
                    break;
                case 1:
                    $output .= '<div>';
                    $output .= '<label>开始时间</label>';
                    $output .= '<label>' . $unit->started_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="editTasks(this);">任务管理'
                        . '<div class="task-alert"></div>'
                        . '</div>';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-id="' . $unit->id . '" '
                        . ' onclick="editItem(this);">新增任务</div>';
//                    $output .= '<div class="btn-rect btn-white"'
//                        . ' data-id="' . $unit->id . '"'
//                        . ' onclick="provideItem(this);">提交项目</div>';
                    $output .= '</div>';
                    break;
                case 2:
                case 3:
                    $output .= '<div>';
                    $output .= '<label>开始时间</label>';
                    $output .= '<label>' . $unit->started_at . '</label>';
                    $output .= '</div>';
                    $output .= '<div>';
                    $output .= '<label>截止时间</label>';
                    $output .= '<label>' . $unit->deadline . '</label>';
                    $output .= '</div>';
                    $output .= '<div class="project-btns">';
                    $output .= '<div class="btn-rect btn-white" '
                        . ' data-id="' . $unit->id . '"'
                        . ' style="width: 200px;" '
                        . ' onclick="viewTasks(this);">查看任务</div>';
                    $output .= '</div>';
                    break;
            }

            $output .= '</div></div>';
        endforeach;
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
            $editArr = array(
                'no' => $this->input->post('no'),
                'title' => $this->input->post('title'),
                'worker_id' => $this->input->post('worker_id'),
                'init_price' => $this->input->post('init_price'),
                'work_price' => $this->input->post('work_price'),
                'deadline' => $this->input->post('deadline'),
                'total_score' => $this->input->post('total_score'),
                'description' => $this->input->post('description'),
                'progress' => 0,
                'status' => 1,
                'update_time' => date("Y-m-d H:i:s")
            );
            $id = $this->input->post('id');
            $result = 0;
            if ($id == 0) {
                $editArr['planner_id'] = $user_id;
                $editArr['no'] = $this->input->post('no');
                $editArr['title'] = $this->input->post('title');
                $editArr['contract_id'] = $this->input->post('contract_id');
                $editArr['deadline'] = $this->input->post('deadline');
                $editArr['price_detail'] = '[]';
                $editArr['published_at'] = date("Y-m-d H:i:s");
                $editArr['create_time'] = date("Y-m-d H:i:s");
                $editArr['update_time'] = date("Y-m-d H:i:s");
                $result = $this->mainModel->add($editArr);
                $id = $result;
                $this->mainModel->edit(array('pid' => $id), $id);
            } else {
                $updateItem = $this->mainModel->get_where(array('id' => $id));
                if ($updateItem != null) {
                    $progress = $updateItem[0]->progress;
                    if ($progress == 1 || $progress == 2) {
                        $editArr = array(
                            'author_id' => $user_id,
                            'deadline' => $this->input->post('deadline'),
                            'description' => $this->input->post('description'),
                            'update_time' => date("Y-m-d H:i:s")
                        );
                    }
                    $result = $this->mainModel->edit($editArr, $id);
                }
            }
            if ($result > 0) {
                $ret['item'] = $this->mainModel->get_where(array(
                    'id' => $id
                ));
            }
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }

        echo json_encode($ret);
    }

    public function updateProject()
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
            $editArr = array();
            $id = $this->input->post('pid');
            $result = 0;
            if ($id == 0) {
                $editArr['planner_id'] = $user_id;
                $editArr['no'] = $this->input->post('no');
                $editArr['title'] = $this->input->post('title');
                $editArr['contract_id'] = $this->input->post('contract_id');
                $editArr['deadline'] = $this->input->post('deadline');
                $editArr['total_score'] = '0';
                $editArr['price_detail'] = '[]';
                $editArr['published_at'] = date("Y-m-d H:i:s");
                $editArr['create_time'] = date("Y-m-d H:i:s");
                $editArr['update_time'] = date("Y-m-d H:i:s");
                $result = $this->mainModel->add($editArr);
                $id = $result;
                $this->mainModel->edit(array('pid' => $id), $id);
            } else {
                $updateItem = $this->mainModel->get_where(array('pid' => $id));
                if ($updateItem != null) {
                    $progress = $updateItem[0]->progress;
                    if ($progress == 0) {
                        $editArr['no'] = $this->input->post('no');
                        $editArr['title'] = $this->input->post('title');
                        $editArr['contract_id'] = $this->input->post('contract_id');
                        $editArr['deadline'] = $this->input->post('deadline');
                        $editArr['update_time'] = date("Y-m-d H:i:s");
                    }
                    $result = $this->mainModel->edit($editArr, $id);
                }
            }
            if ($result > 0) {
                $ret['item'] = $this->mainModel->get_where(array(
                    'id' => $id
                ));
            }
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }

        echo json_encode($ret);
    }

    public function updatePriceDetail()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole(15)) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        $user_id = $this->session->userdata('_userid');
        if ($_POST) {
            $pid = $this->input->post('pid');
            $updateItem = $this->mainModel->get_where(array('pid' => $pid));
            $priceDetail = json_decode($updateItem[0]->price_detail);
            array_push($priceDetail, array(
                'price' => $this->input->post('price'),
                'description' => $this->input->post('description'),
                'created' => date('Y-m-d H:i:s')
            ));
            $totalScore = 0;
            $totalPrice = 0;
            foreach($priceDetail as $item){
                $totalScore += $item->price / 150;
                $totalPrice += $item->price;
            }

            $priceDetail = json_encode($priceDetail);
            foreach ($updateItem as $item) {
                $this->mainModel->edit(array(
                    'price_detail' => $priceDetail,
                    'init_price' => $totalPrice,
                    'total_score' => $totalScore,
                ), $item->id);
            }
            $ret['data'] = $priceDetail;
            $ret['status'] = 'success';
        }

        echo json_encode($ret);
    }

    public function updateItems()
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

            $workers = $this->input->post('worker_id');
//            $totalScores = $this->input->post('total_score_val');
//            $totalScores = explode(';', $totalScores);
            $editArr = array(
                'author_id' => $user_id,

//                'no' => $this->input->post('no'),
//                'title' => $this->input->post('title'),
//                'init_price' => $this->input->post('init_price'),
//                'work_price' => $this->input->post('work_price'),
//                'deadline' => $this->input->post('deadline'),
                'description' => $this->input->post('description'),
                'progress' => 0,
                'status' => 1,
                'update_time' => date("Y-m-d H:i:s")
            );

            $pid = $this->input->post('pid');
            $result = 0;
            if ($pid == 0) {
                $editArr['published_at'] = date("Y-m-d H:i:s");
                $editArr['create_time'] = date("Y-m-d H:i:s");
                $pid = $this->mainModel->add($editArr);
                $i = 0;
                foreach ($workers as $worker) {
                    $editArr['pid'] = $pid;
                    $editArr['worker_id'] = $worker;
//                    $editArr['total_score'] = $totalScores[$i];
                    if ($i == 0) $this->mainModel->edit($editArr, $pid);
                    else $this->mainModel->add($editArr);
                    $i++;
                }
            } else {
                $updateItem = $this->mainModel->get_where(array('pid' => $pid));
                if ($updateItem != null) {
                    // update not started projects
                    if ($workers != null) {

                        if (count($updateItem) > count($workers)) {
                            for ($i = count($workers); $i < count($updateItem); $i++) {
                                if ($updateItem[$i]->progress != 0) {
                                    $ret['data'] = '这个项目已进行中。';
                                    $ret['status'] = 'fail';
                                    echo json_encode($ret);
                                    return;
                                }
                            }
                        }

                        $i = 0;
                        foreach ($workers as $worker) {
                            $editArr['pid'] = $pid;
                            $editArr['worker_id'] = $worker;
//                            $editArr['total_score'] = $totalScores[$i];
                            $editArr['published_at'] = date("Y-m-d H:i:s");
                            if ($updateItem[$i]) {
                                $editArr['started_at'] = date("Y-m-d H:i:s");
                                $editArr['progress'] = 1;
                                $progress = $updateItem[$i]->progress;
//                                if (!$editArr['title']) {
//                                    $editArr = array(
////                                        'total_score' => $totalScores[$i],
////                                        'init_price' => $this->input->post('init_price'),
////                                        'work_price' => $this->input->post('work_price'),
////                                        'deadline' => $this->input->post('deadline'),
//                                        'description' => $this->input->post('description'),
//                                        'update_time' => date("Y-m-d H:i:s")
//                                    );
//                                }
                                $result = $this->mainModel->edit($editArr, $updateItem[$i]->id);
                            } else {
                                $editArr['create_time'] = date("Y-m-d H:i:s");
                                $this->mainModel->add($editArr);
                            }
                            $i++;
                        }
                        for ($i = count($workers); $i < count($updateItem); $i++) {
                            $this->mainModel->delete($updateItem[$i]->id);
                        }

                    } else {
                        // update already started projects
                        $i = 0;
                        foreach ($updateItem as $item) {
                            $editArr['pid'] = $pid;
//                            $editArr['total_score'] = $totalScores[$i];
                            $editArr['published_at'] = date("Y-m-d H:i:s");
                            $progress = $updateItem[$i]->progress;
                            if ($progress == 1 || $progress == 2) {
                                $editArr = array(
//                                    'total_score' => $totalScores[$i],
//                                    'init_price' => $this->input->post('init_price'),
//                                    'work_price' => $this->input->post('work_price'),
//                                    'deadline' => $this->input->post('deadline'),
                                    'description' => $this->input->post('description'),
                                    'update_time' => date("Y-m-d H:i:s")
                                );
                            }
                            $result = $this->mainModel->edit($editArr, $updateItem[$i]->id);

                            $i++;
                        }
                    }

                }
            }
            if ($pid > 0) {
                $ret['item'] = $this->mainModel->get_where(array(
                    'pid' => $pid
                ));
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

    public function deleteItems()
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
            $pid = $_POST['pid'];
            $allProjects = $this->mainModel->get_where(array('pid' => $pid));
            foreach ($allProjects as $item) {
                $id = $item->id;
                $list = $this->mainModel->delete($id);
                $taskItems = $this->tasks_m->get_where(array('project_id' => $id));
                if ($taskItems != null) {
                    foreach ($taskItems as $item) {
                        $this->tasks_m->delete($item->id);
                    }
                }
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

    public function completeItems()
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
            $pid = $_POST['pid'];
            $userId = $this->session->userdata("_userid");
            $allProjects = $this->mainModel->get_where(array('pid' => $pid));
            foreach ($allProjects as $project) {
                $this->mainModel->edit(array(
                    'progress' => 3,
                    'completed_at' => date('Y-m-d H:i:s'),
                    'update_time' => date('Y-m-d H:i:s')
                ), $project->id);
            }

            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    public function rejectItems()
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
            $pid = $_POST['pid'];
            $userId = $this->session->userdata("_userid");
            $allProjects = $this->mainModel->get_where(array('pid' => $pid));
            foreach ($allProjects as $project) {
                $result = $this->mainModel->edit(array(
                    'progress' => 1,
                    'provided_at' => null,
                    'update_time' => date('Y-m-d H:i:s')
                ), $project->id);
            }
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

    function removeDuplicated($data = array(), $key = '')
    {
        $_data = array();
        if ($data == null) return array();
        if ($key == '') return array();
        foreach ($data as $v) {
            if (isset($_data[$v->{$key}])) {
                // found duplicate
                continue;
            }
            // remember unique item
            $_data[$v->{$key}] = $v;
        }
        return $_data;
    }

}

?>