<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
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
        $this->load->model("posts_m");
        $this->lang->load('main', $language);
        $this->load->library("pagination");
        $this->load->library("session");

        $this->mainModel = $this->posts_m;
    }

    public function index()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }
        $this->data['title'] = '首页';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'home/index';
        $this->data['mainModel'] = $model = 'tbl_post';

        $this->data['menuId'] = 0;
//        $this->data['userList'] = $this->users_m->getItems();

        $filter = array();
        if ($this->uri->segment(SEGMENT) == '') $this->session->unset_userdata('filter');
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $_POST['search_keyword'] != '' && $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 1000;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);

//        $this->data["tbl_content"] = $this->output_content($this->data['list']);

        $filter = array();
        $this->data['range_from'] = $filter['range_from'] = date('Y-m-01 00:00:00');
        $this->data['range_to'] = $filter['range_to'] = date('Y-m-01 00:00:00', strtotime('+1 months'));
        $filterStr = " tbl_union.completed_at >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_union.completed_at < '{$filter['range_to']}'";

//        $sql = "select author_id, sum(score) * 0.08 as project_score ";
//        $sql .= " from tbl_tasks ";
//        $sql .= " where progress=3 and completed_at>='{$filter['range_from']}' and completed_at<'{$filter['range_to']}' ";
//        $sql .= " group by author_id ";
//        $userScores = $this->db->query($sql)->result();
//        foreach ($userScores as $item) {
//            $this->users_m->edit(array('project_score' => $item->project_score), $item->author_id);
//        }

        $scoreList = $this->users_m->getUserScoreByPage(array(), 0, 1000,
            '',$this->data['range_from'], $this->data['range_to']);
        $userList = $this->users_m->getItems(array(
            'tbl_user.is_calc_score'=>1,
        ));
        $resultList = array();
        foreach ($userList as $userItem) {
            $isExist = false;
            foreach ($scoreList as $scoreItem) {
                if ($scoreItem->id == $userItem->id) {
                    $isExist = true;
                    array_push($resultList, $scoreItem);
                    break;
                }
            }
            if (!$isExist) {
                array_push($resultList, $userItem);
            }
        }
        $this->data["userList"] = $resultList;
        $this->data["subview"] = $apiRoot;

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
        return $output;
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $editable = ($unit->status == 0);
            $progress = $unit->progress;
            $bgStr = 'url(' . base_url('assets/images/project/bg' . ($unit->id % 11 + 1) . '.jpg') . ')';

            $output .= '<div class="content-item"><div style="background:' . $bgStr . ';">';
            $output .= '<div class="btn-transparent" '
                . ' data-id="' . $unit->id . '" '
                . ' onclick="viewItem(this);"></div>';

            $output .= '<div class="project-score">' . $unit->total_score . '</div>';
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
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="deleteItem(this);">删除项目</div>';
                    $output .= '<div class="btn-rect btn-white"'
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="editItem(this);">编辑项目</div>';
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
                        . ' onclick="viewTasks(this);">查看任务</div>';
                    $output .= '<div class="btn-rect btn-white"'
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="editItem(this);">编辑项目</div>';
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
                        . ' data-id="' . $unit->id . '"'
                        . ' onclick="viewTasks(this);">查看任务</div>';
                    $output .= '<div class="btn-rect btn-white"'
                        . ' data-id="' . $unit->id . '"'
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
                        . ' data-id="' . $unit->id . '"'
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
                'author_id' => $user_id,

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
                $editArr['published_at'] = date("Y-m-d H:i:s");
                $editArr['create_time'] = date("Y-m-d H:i:s");
                $result = $this->mainModel->add($editArr);
            } else {
                $updateItem = $this->mainModel->get_where(array('id' => $id));
                if ($updateItem != null) {
                    $progress = $updateItem[0]->progress;
                    if ($progress == 1 || $progress == 2) {
                        $editArr = array(
                            'deadline' => $this->input->post('deadline'),
                            'description' => $this->input->post('description'),
                            'update_time' => date("Y-m-d H:i:s")
                        );
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
            $list = $this->mainModel->delete($id);
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