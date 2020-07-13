<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller
{

    protected $mainModel;

    function __construct()
    {
        parent::__construct();

        $language = 'chinese';
        $this->load->model("signin_m");
        $this->load->model("contracts_m");
        $this->load->model("userpart_m");
        $this->load->model("userposition_m");
        $this->load->model("userrank_m");
        $this->load->model("userrole_m");
        $this->load->model("userprice_m");
        $this->lang->load('main', $language);
        $this->load->library("pagination");
        $this->load->library("session");

        $this->mainModel = $this->contracts_m;
    }

    public function index()
    {
        $this->contract();
    }

    public function contract()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '财务管理 ＞ 合同管理';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'payment/contract';
        $this->data['mainModel'] = 'tbl_contracts';

        $filter = array();
        $startNo = 0;
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        else $startNo = $this->uri->segment(3);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['search_status'] != '' && $filter['tbl_contracts.status'] = $_POST['search_status'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);

        $this->data["tbl_content"] = $this->output_content($this->data['list'], $startNo);

        $this->data["subview"] = $apiRoot;

        if (!$this->checkRole(11)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content($items, $startNo = 0)
    {
        $userId = $this->session->userdata("_userid");
        $statusStr = ["未签", "已签", "已完成", "终止"];
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $startNo++;
            $editable = ($unit->status < 2);
            $output .= '<tr>';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->no . '</td>';
            $output .= '<td>' . $unit->title . '</td>';
            $output .= '<td>' . $unit->total_price . '</td>';
            $output .= '<td>' . $unit->paid_price . '</td>';
            $output .= '<td>' . $unit->client_name . '</td>';
            $output .= '<td>' . $unit->project_worker . '</td>';
            $output .= '<td>' . $unit->expire_date . '</td>';
            $output .= '<td>' . $unit->signed_date . '</td>';
            $output .= '<td>' . $statusStr[$unit->status] . '</td>';
            $output .= '<td>';
            if (false && $userId != 1 && $unit->id == 1) {

            } else {
                if ($editable) {
                    $output .= '<div class="btn-rect btn-orange" onclick="editItem(this);"'
                        . ' data-id="' . $unit->id . '" '
                        . '>编辑</div>';
                }
                $output .= '<div class="btn-rect btn-green" onclick="viewItem(this);"'
                    . ' data-id="' . $unit->id . '" '
                    . '>查看详情</div>';
            }
            $output .= '</td>';
            $output .= '</tr>';
        endforeach;
        return $output;
    }

    public function action()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '绩效中心';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'users/action';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_user';

        $this->data['menu'] = 3;

        $userId = $this->session->userdata('_userid');
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');
        $isBoss = $this->userpart_m->get_where(array('boss_id' => $userId));

        $filter = array();
        if ($this->uri->segment(3) == '') $this->session->unset_userdata('filter');
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

//        if ($roleId == 1 || $roleId == 2) { // user is admin or official manager
//
//        } else if ($isBoss != null) { // user is boss
//            $filter['tbl_user_part.id'] = $partId;
//        } else { // user is project manager or general user
//            $filter['tbl_user.id'] = $userId;
//        }
//        $this->data['positionList'] = $this->userposition_m->getItems();

        $filter['range_from'] = date('Y-m-01 00:00:00');
        $filter['range_to'] = date('Y-m-01 00:00:00', strtotime('+1 months'));
        $startNo = 0;
        if ($this->uri->segment(3) != '') $startNo = $this->uri->segment(3);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $filter['queryStr'] = $_POST['search_keyword'];
            $_POST['range_from'] != '' && $filter['range_from'] = $_POST['range_from'];
            $_POST['range_to'] != '' && $filter['range_to'] = $_POST['range_to'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
//        $filterStr = " tbl_union.completed_at >= '{$filter['range_from']}' ";
//        $filterStr .= " and tbl_union.completed_at < '{$filter['range_to']}' ";
        $filterStr = "'1' = '1' ";
        if (isset($filter['tbl_user_part.id'])) $filterStr .= " and tbl_user_part.id = {$filter['tbl_user_part.id']} ";
        if (isset($filter['tbl_user.id'])) $filterStr .= " and tbl_user.id = {$filter['tbl_user.id']} ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

//        $sql = "select author_id, sum(score) * 0.08 as project_score ";
//        $sql .= " from tbl_tasks ";
//        $sql .= " where progress=3 and completed_at>='{$filter['range_from']}' and completed_at<'{$filter['range_to']}' ";
//        $sql .= " group by author_id ";
//        $userScores = $this->db->query($sql)->result();
//        foreach ($userScores as $item){
//            $this->users_m->edit(array('project_score' => $item->project_score), $item->author_id);
//        }

        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_score_count($filterStr,
            $queryStr, $this->data['range_from'], $this->data['range_to']);

        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
//        log_message('info', '------- filterStr: '.var_export($filterStr, true));
//        log_message('info', '------- this->data: '.var_export($this->data, true));
        $this->data["list"] = $list = $this->mainModel->getUserScoreByPage($filterStr, $ret['pageId'], $ret['cntPerPage'],
            $queryStr, $this->data['range_from'], $this->data['range_to']);
//        $this->data["userList"] = $userList = $this->mainModel->getItems();
        $resultList = $list;
//        foreach ($userList as $userItem){
//            $isExist = false;
//            foreach ($list as $scoreItem){
//                if($scoreItem->id == $userItem->id){
//                    $isExist = true;
//                    array_push($resultList, $scoreItem);
//                    break;
//                }
//            }
//            if(!$isExist){
//                array_push($resultList, $userItem);
//            }
//        }
        $this->data["tbl_content"] = $this->output_content_action($resultList, $startNo);

        if (!$this->checkRole(3)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function downloadAction()
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );

        $this->data['apiRoot'] = $apiRoot = 'users/action';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_user';

        $this->data['menu'] = 3;

        $userId = $this->session->userdata('_userid');

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
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
//        $filterStr = " tbl_union.completed_at >= '{$filter['range_from']}' ";
//        $filterStr .= " and tbl_union.completed_at < '{$filter['range_to']}' ";
        $filterStr = "'1' = '1' ";
        if (isset($filter['tbl_user_part.id'])) $filterStr .= " and tbl_user_part.id = {$filter['tbl_user_part.id']} ";
        if (isset($filter['tbl_user.id'])) $filterStr .= " and tbl_user.id = {$filter['tbl_user.id']} ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data["list"] = $list = $this->mainModel->getUserScoreByPage($filterStr, 0, 1000,
            $queryStr, $this->data['range_from'], $this->data['range_to']);
        $ret['data'] = $resultList = $list;
        $ret['status'] = 'success';
        echo json_encode($ret);
    }

    public function output_content_action($items, $startNo = 0)
    {
        $userId = $this->session->userdata("_userid");
        $statusStr = ["离职", "在职"];
        $output = '';
        $i = 0;
        foreach ($items as $unit):
            $i++;
            $startNo++;
            $editable = ($unit->status == 0);
            $avatar = base_url('assets/images/icon-profile.png');
            $colorStyle = '';
            $score = $unit->user_score;
            $standard = $unit->standard_factor;
            $profit = floatval($unit->rank_factor);
            if ($score > $standard - 5) {
                $profit *= floatval($score - $standard);
            } else {
                $profit *= floatval($score - ($standard - 5));
            }

            if ($unit->avatar) {
                $avatar = base_url() . $unit->avatar;
                $colorStyle = 'background-color: white;';
            }
            $output .= '<tr>';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td><div class="user-avatar" '
                . ' style="background-image:url(' . $avatar . ');' . $colorStyle . '">'
                . '</div></td>';
            $output .= '<td>' . $unit->name . '</td>';
            $output .= '<td>' . $unit->position . '</td>';
            $output .= '<td>' . $unit->part . '</td>';
            $output .= '<td>' . $unit->task_completed . '</td>';
            $output .= '<td>' . $score . '</td>';
//            $output .= '<td>' . $standard . '</td>';
//            $output .= '<td>' . $profit . '</td>';
            $output .= '<td>';
            $output .= '<div class="btn-rect btn-orange" onclick="viewItem(this);"'
                . ' data-id="' . $unit->id . '" '
                . '>本月任务详情</div>';
            $output .= '</td>';
            $output .= '</tr>';
        endforeach;
        return $output;
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
            $id = $this->input->post('id');
            $user_id = $this->session->userdata('_userid');
            if ($id == 1 && $user_id != 1) {
                $ret['data'] = '用户权限错误';
                echo json_encode($ret);
                return;
            }
            $editArr = array(
                'account' => $this->input->post('account'),
                'name' => $this->input->post('name'),
                'role_id' => $this->input->post('role_id'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'position_id' => $this->input->post('position_id'),
                'rank_id' => $this->input->post('rank_id'),
                'description' => $this->input->post('description'),
                'status' => $this->input->post('status'),
                'is_calc_score' => $this->input->post('is_calc_score'),
                'entry_date' => $this->input->post('entry_date'),
                'update_time' => date("Y-m-d H:i:s")
            );
            if ($this->input->post('status') == 0)
                $editArr['leave_date'] = $this->input->post('leave_date');
            else
                $editArr['leave_date'] = null;
            if ($id == 0) {
                $editArr['avatar'] = 'assets/images/icon-profile.png';
                $editArr['password'] = $this->mainModel->hash('1');
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

    public function updateAvatar()
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
            if ($user_id != $id) {
                $ret['data'] = '用户权限错误';
                echo json_encode($ret);
                return;
            }
            $editArr = array(
                'update_time' => date("Y-m-d H:i:s")
            );
            if ($_FILES["imgFile"]["name"] != '') {
                if ($id == 0) {
                    $editArr['create_time'] = date("Y-m-d H:i:s");
                    $id = $this->mainModel->add($editArr);
                }
                $config['upload_path'] = "./uploads/profile";
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path']);
                }
                $config['allowed_types'] = '*';
                $filename = 'profile_' . $id;
                $fileFormat = strtolower($this->input->post('imgFileFormat'));
                $this->load->library('upload', $config);

                $fileData = '';
                if ($_FILES["imgFile"]["name"] != '') {
                    $nameSuffix = '';
                    $config['file_name'] = $filename . $nameSuffix . '.' . $fileFormat;
                    if (file_exists(substr($config['upload_path'], 2) . '/' . $config['file_name'])) {
                        unlink(substr($config['upload_path'], 2) . '/' . $config['file_name']);
                    }
                    $this->upload->initialize($config, TRUE);
                    switch ($fileFormat) {
                        case 'gif':
                        case 'png':
                        case 'jpg':
                        case 'jpeg':
                        case 'bmp':
                            ///Image file uploading........
                            if ($this->upload->do_upload('imgFile')) {
                                $data = $this->upload->data();
                                $fileData = substr($config['upload_path'], 2) . '/' . $config['file_name'];
                            } else {
                                $ret['data'] = '封面图片上传错误' . $this->upload->display_errors();
                                $ret['status'] = 'fail';
                                echo json_encode($ret);
                                return;
                            }
                            break;
                    }
                }
                if ($fileData) $editArr['avatar'] = $fileData;
            }

            if ($id == 0) {
                $editArr['create_time'] = date("Y-m-d H:i:s");
                $result = $this->mainModel->add($editArr);
            } else {
                $result = $this->mainModel->edit($editArr, $id);
            }
            $ret['data'] = $result;
            $this->session->set_userdata('_avatar', $editArr['avatar']);
            if ($result > 0) {
                $ret['item'] = $this->mainModel->get_where(array('id' => $id));
            }
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }

        echo json_encode($ret);
    }

    public function updatePwd()
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
            if ($id == 1 && $user_id != 1) {
                $ret['data'] = '用户权限错误';
                echo json_encode($ret);
                return;
            }

            $password0 = $this->input->post('password_old');
            $password = $this->input->post('password_new');
            $cpassword = $this->input->post('cpassword_new');

            $userInfo = $this->mainModel->get_where(array(
                'id' => $user_id,
                'password' => $this->users_m->hash($password0)
            ));
            if ($userInfo == null) {
                $ret['data'] = '旧密码不正确';
                echo json_encode($ret);
                return;
            }
            if (strlen($password) == 0 || $password != $cpassword) {
                $ret['data'] = '新密码不正确';
                echo json_encode($ret);
                return;
            }
            if ($password == $password0) {
                $ret['data'] = '密码没有改变了';
                echo json_encode($ret);
                return;
            }

            $editArr = array(
                'password' => $this->users_m->hash($password),
                'update_time' => date("Y-m-d H:i:s")
            );
            $result = $this->mainModel->edit($editArr, $id);

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
}

?>