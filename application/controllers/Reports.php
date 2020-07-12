<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller
{

    protected $mainModel;

    function __construct()
    {
        parent::__construct();

        $language = 'chinese';
        $this->load->model("signin_m");
        $this->load->model("report_m");
        $this->load->model("userpart_m");
        $this->load->model("userposition_m");
        $this->load->model("userrank_m");
        $this->load->model("userrole_m");
        $this->lang->load('main', $language);
        $this->load->library("pagination");
        $this->load->library("session");

        $this->mainModel = $this->report_m;
    }

    public function index()
    {
        $this->manage();
    }

    public function manage()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '日报大厅';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'reports/manage';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_report';

        $this->data['menu'] = 14;

        $userId = $this->session->userdata('_userid');
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');
        $isBoss = $this->userpart_m->get_where(array('boss_id' => $userId));

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $filter['range_from'] = date('Y-m-d 00:00:00'/*, strtotime('-1 days')*/);
        $filter['range_to'] = date('Y-m-d 00:00:00', strtotime('+1 days'));
        $startNo = 0;
        if ($this->uri->segment(3) != '') $startNo = $this->uri->segment(3);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $filter['queryStr'] = $_POST['search_keyword'];
            $filter['tbl_user_part.id'] = $_POST['search_part'];
            $_POST['range_from'] != '' && $filter['range_from'] = $_POST['range_from'];
            $_POST['range_to'] != '' && $filter['range_to'] = $_POST['range_to'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $dateFrom = date_create($filter['range_from']);
        $dateTo = date_create($filter['range_to']);
        $diff = date_diff($dateFrom, $dateTo)->format('%a');

        $this->data['perPage'] = $perPage = 1;
        $this->data['cntPage'] = $cntPage = $diff;
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $filterDateFrom = date_create($dateTo->format('Y-m-d H:i:s'));
        $filterDateTo = date_create($dateTo->format('Y-m-d H:i:s'));
        date_add($filterDateFrom, date_interval_create_from_date_string('-' . ($startNo + 1) . ' days'));
        date_add($filterDateTo, date_interval_create_from_date_string('-' . $startNo . ' days'));
        $this->data['curFilterDate'] = $filterDateFrom = $filterDateFrom->format('Y-m-d H:i:s');
        $filterDateTo = $filterDateTo->format('Y-m-d H:i:s');
//        var_dump($filterDateFrom);
//        var_dump($filterDateTo);
        $queryStr = $filter['queryStr'] . '';
        $filterStr = " tbl_report.create_time >= '{$filterDateFrom}' ";
        $filterStr .= " and tbl_report.create_time < '{$filterDateTo}' ";
//        $filterStr = "'1' = '1' ";
        if ($filter['tbl_user_part.id']) $filterStr .= " and tbl_user_part.id = {$filter['tbl_user_part.id']} ";
//        if (isset($filter['tbl_user.id'])) $filterStr .= " and tbl_user.id = {$filter['tbl_user.id']} ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['search_part'] = $filter['tbl_user_part.id'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filterStr, 0, 0, $queryStr);
        $this->data["userList"] = $list = $this->users_m->getItems(
            "tbl_user.id != 1 "
            . " and tbl_user.status = 1 "
//            . " and tbl_user_part.title != '综合管理部'"
        );
        $this->data['perPageUsers'] = count($list) * $cntPage;
        $this->data["partList"] = $userList = $this->userpart_m->getItems(
//            "tbl_user_part.title != '总经理' " .
//            " and tbl_user_part.title != '市场部' " .
//            " and tbl_user_part.title != '综合管理部'"
        );
        $resultList = $list;
        $this->data['holidays'] = $holidays = $this->httpReq(
            "http://opendata.baidu.com/api.php?resource_id=6018&format=json&query=" . date("Y-m")
        );
//        $this->data["tbl_content"] = $this->output_content($resultList, $startNo);
        if (!$this->checkRole(14)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function viewlist($uId)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'reports/viewlist';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_report';

        $this->data['menu'] = 14;

        $userId = $uId;
        $this->data['curUser'] = $curUser = $this->users_m->getItems(array('tbl_user.id' => $uId));
        unset($curUser[0]->account);
        unset($curUser[0]->password);
        unset($curUser[0]->permission);
        $this->data['title'] = '日报大厅 ＞ ' . $curUser[0]->name . '日报列表';
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');
        $isBoss = $this->userpart_m->get_where(array('boss_id' => $userId));

        $filter = array();
        if ($this->uri->segment(4) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $startNo = 0;
        if ($this->uri->segment(4) != '') $startNo = $this->uri->segment(4);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $_POST['range_from'] != '' && $filter['range_from'] = $_POST['range_from'];
            $_POST['range_to'] != '' && $filter['range_to'] = $_POST['range_to'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $queryStr = $filter['queryStr'] . '';
        $filterStr = " tbl_user.id = '{$uId}' ";
        if (isset($filter['range_from'])) $filterStr .= " and tbl_report.create_time >= '{$filter['range_from']}' ";
        if (isset($filter['range_to'])) $filterStr .= " and tbl_report.create_time < '{$filter['range_to']}' ";

        $this->data['perPage'] = $perPage = 30;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filterStr, 0, 0, $queryStr);;
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 4);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        $this->data['apiRoot'] .= "/$uId";
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filterStr, 0, 0, $queryStr);

        $this->data['holidays'] = $holidays = $this->httpReq(
            "http://opendata.baidu.com/api.php?resource_id=6018&format=json&query=" . date("Y-m")
        );

        if (!$this->checkRole(14)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function mine()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '个人中心 ＞ 我的日报';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'reports/mine';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_report';

        $this->data['menu'] = -1;

        $userId = $this->session->userdata('_userid');
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');
        $isBoss = $this->userpart_m->get_where(array('boss_id' => $userId));

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $filter['range_from'] = date('Y-m-d 00:00:00', strtotime('-1 months'));
        $filter['range_to'] = date('Y-m-d 00:00:00', strtotime('+1 days'));
        $startNo = 0;
        if ($this->uri->segment(3) != '') $startNo = $this->uri->segment(3);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $filter['queryStr'] = $_POST['search_keyword'];
            $filter['tbl_user_part.id'] = $_POST['search_part'];
            $_POST['range_from'] != '' && $filter['range_from'] = $_POST['range_from'];
            $_POST['range_to'] != '' && $filter['range_to'] = $_POST['range_to'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        $filterStr = " tbl_report.author_id = '{$userId}' ";
        $filterStr .= " and tbl_report.create_time >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_report.create_time < '{$filter['range_to']}' ";
//        $filterStr = "'1' = '1' ";
        if (isset($filter['tbl_user_part.id'])) $filterStr .= " and tbl_user_part.id = {$filter['tbl_user_part.id']} ";
//        if (isset($filter['tbl_user.id'])) $filterStr .= " and tbl_user.id = {$filter['tbl_user.id']} ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['search_part'] = $filter['tbl_user_part.id'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filterStr, 0, 0, $queryStr);

//        $filterStr = "author_id = {$userId} " .
//            " and date(create_time) = date('" . date('Y-m-d H:i:s') . "')";
//        $this->data['todayReport'] = $this->mainModel->get_where($filterStr);
//        if ($this->data['todayReport'] == null) $this->data['todayReport'] = array();
        $this->data['lastReport'] = array();
        if ($list) $this->data['lastReport'] = array($list[0]);
        $this->data["partList"] = $userList = $this->userpart_m->getItems();
        $resultList = $list;
//        $this->data["tbl_content"] = $this->output_content($resultList, $startNo);

        $this->data['holidays'] = $holidays = $this->httpReq(
            "http://opendata.baidu.com/api.php?resource_id=6018&format=json&query=" . date("Y-m")
        );

        if (!$this->checkRole(14)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function profile()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '个人中心 ';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'users/profile';
        $this->data['mainModel'] = $mainModel = 'tbl_user';

        $user_id = $this->session->userdata('_userid');

        $this->session->unset_userdata('filter');
        $this->data["userInfo"] = $this->mainModel->getItems(array(
            $mainModel . '.id' => $user_id
        ));

        $this->data["subview"] = $apiRoot;

        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function change()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '个人中心 ';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'users/change';
        $this->data['mainModel'] = $mainModel = 'tbl_user';

        $user_id = $this->session->userdata('_userid');

        $this->session->unset_userdata('filter');
        $this->data["userInfo"] = $this->mainModel->getItems(array(
            $mainModel . '.id' => $user_id
        ));

        $this->data["subview"] = $apiRoot;

        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
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
        if ($_POST) {
            $isAddNew = $this->input->post('isAddNew');
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('_userid');
            $datas = $this->input->post('data');

            $dataArr = array();
            if ($isAddNew == 1) {
                foreach ($datas as $item) {
                    array_push($dataArr, array(
                        'title' => $item,
                        'status' => 0,
                        'desc' => ''
                    ));
                }
                if ($id == 0) {
                    $filterStr = "author_id = {$user_id} " .
                        " and date(create_time) = date('" . date('Y-m-d H:i:s') . "')";
                } else {
                    $filterStr = "id = {$id} ";
                }
                $result = $this->mainModel->get_where($filterStr);
                $id = $result[0]->id;
            } else {
                $status = $this->input->post('statusVal');
                $status = explode(',', $status);
                $desc = $this->input->post('desc');
                $result = $this->mainModel->get_where(array('id' => $id));
                $data = json_decode($result[0]->data);
                $i = 0;
                foreach ($data as $item) {
                    $item->status = intval($status[$i]);
                    $item->desc = '';
                    if (true || $item->status == 1) $item->desc = $desc[$i];
                    $i++;
                }
                $dataArr = $data;
            }
            $editArr = array(
                'author_id' => $user_id,
                'data' => json_encode($dataArr),
                'status' => 1,
                'update_time' => date('Y-m-d H:i:s')
            );

            if ($result) {
                $result = $this->mainModel->edit($editArr, $id);
            } else {
                $editArr['create_time'] = date("Y-m-d H:i:s");
                $result = $this->mainModel->add($editArr);
            }
            if ($result > 0) {
                $ret['item'] = $this->mainModel->get_where(array('id' => $id));
            }
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }

        echo json_encode($ret);
    }

    public function resetItem()
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
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('_userid');

            if ($id == 1 && $user_id != 1) {
                $ret['data'] = '用户权限错误';
                echo json_encode($ret);
                return;
            }
            if ($id < 0) {
                $filter = array();
                $this->session->userdata('filter') != null && $filter = $this->session->userdata('filter');
                $pageId = 0;
                if (isset($_POST['pageId'])) $pageId = $_POST['pageId'];
                $perPage = PERPAGE;
                $lists = $this->mainModel->getItemsByPage($filter, $pageId, $perPage);
                foreach ($lists as $item)
                    $this->mainModel->edit(array(
                        'password' => $this->mainModel->hash('1')
                    ), $item->id);
            } else {
                $this->mainModel->edit(array(
                    'password' => $this->mainModel->hash('1')
                ), $id);
            }
            $ret['data'] = $ret['data'] = '操作成功';
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
            $user_id = $this->session->userdata('_userid');

            if ($id == 1 || $user_id != 1) {
                $ret['data'] = '用户权限错误';
                echo json_encode($ret);
                return;
            }
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

    public function httpReq($url, $method = '', $postfields = null, $headers = array(), $debug = false)
    {
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case 'POST':
            case 'post':
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);

            echo '=====info=====' . "\r\n";
            print_r(curl_getinfo($ci));

            echo '=====$response=====' . "\r\n";
            print_r($response);
        }
        curl_close($ci);
        return $response;
    }
}

?>