<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller
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
        $this->load->model("userprice_m");
        $this->load->model("payment_m");
        $this->load->model("projects_m");
        $this->lang->load('main', $language);
        $this->load->library("pagination");
        $this->load->library("session");

        $this->mainModel = $this->payment_m;
    }

    public function index()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '财务管理 ＞ 公司收支录入';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'payment/index';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_payment';

        $this->data['menu'] = 18;

        $userId = $this->session->userdata('_userid');
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $filter['range_from'] = date('Y-m-01 00:00:00');
        $filter['range_to'] = date('Y-m-01 00:00:00', strtotime('+1 months'));
        $startNo = 0;
        if ($this->uri->segment(3) != '') $startNo = $this->uri->segment(3);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['range_from'] != '' && $filter['range_from'] = $_POST['range_from'];
            $_POST['range_to'] != '' && $filter['range_to'] = $_POST['range_to'];
            $_POST['search_type'] != '' && $filter['tbl_payment.type'] = $_POST['search_type'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        $filterStr = " tbl_payment.create_time >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_payment.create_time < '{$filter['range_to']}' ";
        if (isset($filter['tbl_payment.type'])) $filterStr .= " and tbl_payment.type = '{$filter['tbl_payment.type']}' ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filterStr, $queryStr,
            $this->data['range_from'], $this->data['range_to']);
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data['projectList'] = $this->projects_m->getItems();
        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filterStr,
            $ret['pageId'], $ret['cntPerPage'],
            $queryStr, $this->data['range_from'], $this->data['range_to']);
        $resultList = $list;
        $this->data["tbl_content"] = $this->output_content($resultList, $startNo);

        if (!$this->checkRole(18)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content($items, $startNo = 0)
    {
        $userId = $this->session->userdata("_userid");
        $statusStr = ["主营业务收入", "其他业务收入", "项目成本支出", "费用支出"];
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $startNo++;
            $editable = ($unit->status == 0);
            $output .= '<tr data-id="' . $unit->id . '">';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->title . '</td>';
            $output .= '<td>' . $unit->bank_account . '</td>';
            $output .= '<td>' . $unit->bank_name . '</td>';
            $output .= '<td>' . $unit->bank_user . '</td>';
            $output .= '<td>' . $unit->price . '</td>';
            $output .= '<td>' . $unit->project . '</td>';
            $output .= '<td>' . $unit->project_no . '</td>';
            $output .= '<td>' . $unit->paid_date . '</td>';
            $output .= '<td>' . $statusStr[$unit->type] . '</td>';
            $output .= '<td>' . $unit->description . '</td>';
            $output .= '<td>' . $unit->create_time . '</td>';
            $output .= '<td>';
            $output .= '<div class="btn-rect btn-orange" onclick="editItem(this);"'
                . ' data-id="' . $unit->id . '" '
                . '>编辑</div>';
            $output .= '<div class="btn-rect btn-red" onclick="deleteItem(this);"'
                . ' data-id="' . $unit->id . '" '
                . '>删除</div>';
            $output .= '</td>';
            $output .= '</tr>';
        endforeach;

        return $output;
    }

    public function downloadMainList()
        $this->data['title'] = '财务管理 ＞ 公司收支录入';

    public function companydata($progress = 0)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '财务管理 ＞ 公司收支统计';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'payment/companydata';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_payment';

        $this->data['menu'] = 19;
        $this->data['progress'] = $progress;

        $filter = array();
        switch ($progress) {
            case 0: // this month
                $filter['range_from'] = date('Y-m-01 00:00:00');
                $filter['range_to'] = date('Y-m-01 00:00:00', strtotime('+1 months'));
                break;
            case 1: // previous month
                $filter['range_from'] = date('Y-m-01 00:00:00', strtotime('-1 months'));
                $filter['range_to'] = date('Y-m-01 00:00:00');
                break;
            case 2: // this quarter
                $toMonth = sprintf("%02d", ceil(date('n') / 3) * 3 + 1);
                $fromMonth = sprintf("%02d",$toMonth-3);
                $filter['range_from'] = date("Y-{$fromMonth}-01 00:00:00");
                $filter['range_to'] = date("Y-{$toMonth}-01 00:00:00");
                break;
            case 3: // this year
                $filter['range_from'] = date('Y-01-01 00:00:00');
                $filter['range_to'] = date('Y-01-01 00:00:00', strtotime('+12 months'));
                break;
        }
        $filterStr = "tbl_projects.progress = 1 ";
        $filterStr .= " and tbl_projects.published_at >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_projects.published_at < '{$filter['range_to']}' ";
        $projectCnt = $this->projects_m->get_count($filterStr);
        $filterStr = "paid_date >= '{$filter['range_from']}' ";
        $filterStr .= "and paid_date < '{$filter['range_to']}' ";
        $companyData = $this->mainModel->getCompanyData($filterStr);
        $this->data["list"] = $list = array(
            'project_cnt' => $projectCnt,
            'company_data' => $companyData
        );

        if (!$this->checkRole(18)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function projectdata()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '财务管理 ＞ 项目收支统计';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'payment/projectdata';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_payment';

        $this->data['menu'] = 20;

        $userId = $this->session->userdata('_userid');
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $filter['range_from'] = date('Y-m-01 00:00:00');
        $filter['range_to'] = date('Y-m-01 00:00:00', strtotime('+1 months'));
        $startNo = 0;
        if ($this->uri->segment(3) != '') $startNo = $this->uri->segment(3);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['range_from'] != '' && $filter['range_from'] = $_POST['range_from'];
            $_POST['range_to'] != '' && $filter['range_to'] = $_POST['range_to'];
            $_POST['search_type'] != '' && $filter['tbl_payment.type'] = $_POST['search_type'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        $filterStr = " ( tbl_payment.type = 2 or tbl_payment.type = 3 ) ";
        $filterStr .= " and tbl_payment.create_time >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_payment.create_time < '{$filter['range_to']}' ";
        if (isset($filter['tbl_payment.type'])) $filterStr .= " and tbl_payment.type = '{$filter['tbl_payment.type']}' ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filterStr, $queryStr,
            $this->data['range_from'], $this->data['range_to']);
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filterStr,
            $ret['pageId'], $ret['cntPerPage'],
            $queryStr, $this->data['range_from'], $this->data['range_to']);
        $resultList = $list;
        $this->data["tbl_content"] = $this->output_content_project($resultList, $startNo);

        if (!$this->checkRole(20)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_project($items, $startNo = 0)
    {
        $userId = $this->session->userdata("_userid");
        $statusStr = ["主营业务收入", "其他业务收入", "项目成本支出", "费用支出"];
        $progressStr = ["未开始", "进行中", "待验收", "已完成"];
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $startNo++;
            $editable = ($unit->status == 0);
            $output .= '<tr data-id="' . $unit->id . '">';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->project_no . '</td>';
            $output .= '<td>' . $unit->project . '</td>';
            $output .= '<td>' . $unit->project_price . '</td>';
            $output .= '<td>' . $unit->contract_price . '</td>';
            $output .= '<td>' . 0 . '</td>';
            $output .= '<td>' . $unit->price . '</td>';
            $output .= '<td>' . $unit->project_deadline . '</td>';
            $output .= '<td>' . $unit->project_completed_at . '</td>';
            $output .= '<td>' . $progressStr[$unit->project_progress] . '</td>';
            $output .= '<td>' . 0 . '</td>';
            $output .= '<td>' . 0 . '</td>';
            $output .= '<td>';
            $output .= '<div class="btn-rect btn-green" onclick="viewItem(this);"'
                . ' data-id="' . $unit->id . '" '
                . '>查看详情</div>';
//            $output .= '<div class="btn-rect btn-red" onclick="deleteItem(this);"'
//                . ' data-id="' . $unit->id . '" '
//                . '>删除</div>';
            $output .= '</td>';
            $output .= '</tr>';
        endforeach;

        return $output;
    }

    public function downloadProjectData()
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );

        $this->data['title'] = '财务管理 ＞ 项目收支统计';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'payment/projectdata';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_payment';

        $this->data['menu'] = 20;

        $userId = $this->session->userdata('_userid');
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $filter['range_from'] = date('Y-m-01 00:00:00');
        $filter['range_to'] = date('Y-m-01 00:00:00', strtotime('+1 months'));
        $startNo = 0;
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['range_from'] != '' && $filter['range_from'] = $_POST['range_from'];
            $_POST['range_to'] != '' && $filter['range_to'] = $_POST['range_to'];
            $_POST['search_type'] != '' && $filter['tbl_payment.type'] = $_POST['search_type'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        $filterStr = " ( tbl_payment.type = 2 or tbl_payment.type = 3 ) ";
        $filterStr .= " and tbl_payment.create_time >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_payment.create_time < '{$filter['range_to']}' ";
        if (isset($filter['tbl_payment.type'])) $filterStr .= " and tbl_payment.type = '{$filter['tbl_payment.type']}' ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filterStr,
            0, 10000,
            $queryStr, $this->data['range_from'], $this->data['range_to']);
        $this->data["list"] = $list;

        $ret['data'] = $resultList = $list;
        $ret['status'] = 'success';
        echo json_encode($ret);
    }

    public function updateItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (!$this->checkRole(18)) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        $user_id = $this->session->userdata('_userid');
        if ($_POST) {
            $id = $this->input->post('id');

            $editArr = array(
                'title' => $this->input->post('title'),
                'author_id' => $user_id,
                'bank_account' => $this->input->post('bank_account'),
                'bank_name' => $this->input->post('bank_name'),
                'bank_user' => $this->input->post('bank_user'),
                'price' => $this->input->post('price'),
                'type' => $this->input->post('type'),
                'project_id' => $this->input->post('project_id'),
                'paid_date' => $this->input->post('paid_date'),
                'status' => 1,
                'description' => $this->input->post('description'),
                'update_time' => date("Y-m-d H:i:s")
            );
            if ($id == 0) {
                $editArr['create_time'] = date("Y-m-d H:i:s");
                $result = $this->mainModel->add($editArr);
            } else {
                $result = $this->mainModel->edit($editArr, $id);
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
        if (!$this->checkRole(18)) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $user_id = $this->session->userdata('_userid');

            $result = $this->mainModel->delete($id);
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