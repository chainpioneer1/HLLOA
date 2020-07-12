<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Admin_Controller
{

    protected $mainModel;

    function __construct()
    {
        parent::__construct();

        $language = 'chinese';
        $this->load->model("users_m");
        $this->load->model("subject_m");
        $this->load->model("terms_m");
        $this->load->model("usage_m");
        $this->lang->load('courses', $language);
        $this->load->library("pagination");
        $this->load->library("session");

        $this->mainModel = $this->users_m;
    }

    public function index()
    {
        $this->data['title'] = '用户信息管理';
        $this->data["subscript"] = "admin/settings/script";
        $this->data["subcss"] = "admin/settings/css";

        $this->data['subjectList'] = $this->subject_m->getItems();
        $this->data['termList'] = $this->terms_m->getItems();
        $this->data['userTypeList'] = [
            array('id' => '1', 'title' => "教师"),
            array('id' => '2', 'title' => "学生"),
            array('id' => '3', 'title' => "公众")
        ];

        $filter = array();
        if ($this->uri->segment(SEGMENT) == '') $this->session->unset_userdata('filter');

        if ($_POST) {
            $this->session->unset_userdata('filter');
            $_POST['search_no'] != '' && $filter['tbl_user.user_account'] = $_POST['search_no'];
            $_POST['search_title'] != '' && $filter['tbl_user.user_nickname'] = $_POST['search_title'];
            $_POST['search_subject'] != '' && $filter['tbl_huijiao_terms.subject_id'] = $_POST['search_subject'];
            $_POST['search_term'] != '' && $filter['tbl_huijiao_terms.id'] = $_POST['search_term'];
            $_POST['search_type'] != '' && $filter['tbl_user.user_type'] = $_POST['search_type'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $this->data['perPage'] = $perPage = PERPAGE;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter);
        $ret = $this->paginationCompress('admin/users/index', $cntPage, $perPage, 4);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage']);

        $this->data["tbl_content"] = $this->output_content($this->data['list']);

        $this->data["subview"] = "admin/users/users";

        if (!$this->checkRole()) {
            $this->load->view('admin/_layout_error', $this->data);
        } else {
            $this->load->view('admin/_layout_main', $this->data);
        }
    }

    public function output_content($items)
    {
        $admin_id = $this->session->userdata("admin_loginuserID");
        $output = '';
        $btn_str = ['启用', '禁用', '修改', '删除'];
        $type_str = ['无', '教师', '学生', '公众'];
        foreach ($items as $unit):
            $editable = $unit->status == 0;
            $schoolInfo = '';
            $userName = '';//$unit->user_account ;
            if ($unit->user_info != null && $unit->user_info != '') {
                $school = json_decode($unit->user_info);
                if ($unit->user_type == '1') {
//                    $schoolInfo = $school->xxjgid_zh;
                } else if ($unit->user_type == '2') {
//                    $schoolInfo = $school->xxjbxx_zh;
                }
                $schoolInfo = $school->organName;
                $userName .= '(' . $school->nick_name . ')';
                $userName = $school->nick_name;
            }
            $usageInfo = $this->usage_m->getUserUsageInfo($unit->id);
            if ($usageInfo == null) {
                $usageInfo = (object)['total_read' => 0, 'total_like' => 0, 'total_favorite' => 0];
            }
            $output .= '<tr>';
            $output .= '<td>' . $unit->user_account . '</td>';
            $output .= '<td>' . $userName . '</td>';
            $output .= '<td>' . $type_str[$unit->user_type] . '</td>';
            $output .= '<td>' . $unit->subject . '</td>';
            $output .= '<td>' . $unit->term . '</td>';
            $output .= '<td>' . $schoolInfo . '</td>';
            $output .= '<td>' . $unit->register_count . '</td>';
            $output .= '<td>' . intval($usageInfo->total_read / 5) . '</td>';
            $output .= '<td>' . $usageInfo->total_like . '</td>';
            $output .= '<td>' . $usageInfo->total_favorite . '</td>';
//            $output .= '<td>';
//
////            $output .= '<button'
////                . ' class="btn btn-sm ' . ($editable ? 'btn-success' : 'disabled') . '" '
////                . ' onclick = "' . ($editable ? 'editItem(this);' : '') . '" '
////                . ' data-id = "' . $unit->id . '"> '
////                . $btn_str[2] . '</button>';
//            $output .= '<button'
//                . ' class="btn btn-sm ' . ($editable ? 'btn-danger' : 'disabled') . '"'
//                . ' onclick = "' . ($editable ? 'deleteItem(this);' : '') . '"'
//                . ' data-info = \'' . $unit->user_info . '\''
//                . ' data-id = "' . $unit->id . '">'
//                . $btn_str[3] . '</button>';
//            $output .= '<button'
//                . ' class="btn btn-sm ' . ($editable ? 'btn-default' : 'btn-warning') . '"'
//                . ' onclick = "publishItem(this);"'
//                . ' data-status = "' . $unit->status . '"'
//                . ' data-id = "' . $unit->id . '">'
//                . $btn_str[$unit->status] . '</button>';
//            $output .= '</td>';
            $output .= '</tr>';
        endforeach;
        return $output;
    }

    public function manage()
    {
        $this->data['title'] = '用户管理';
        $this->data["subscript"] = "admin/settings/script";
        $this->data["subcss"] = "admin/settings/css";

        $this->data['subjectList'] = $this->subject_m->getItems();
        $this->data['termList'] = $this->terms_m->getItems();
        $this->data['userTypeList'] = [
            array('id' => '1', 'title' => "教师"),
            array('id' => '2', 'title' => "学生"),
            array('id' => '3', 'title' => "公众")
        ];

        $filter = array();
        if ($this->uri->segment(SEGMENT) == '') $this->session->unset_userdata('filter');
        $queryStr = "";
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $_POST['search_title'] != '' && $queryStr = $_POST['search_title'];
            $_POST['search_city'] != '' && $filter['tbl_user.user_city'] = $_POST['search_city'];
            $_POST['search_area'] != '' && $filter['tbl_user.user_area'] = $_POST['search_area'];
            $_POST['search_school'] != '' && $filter['tbl_user.user_school'] = $_POST['search_school'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $this->data['queryStr'] = $queryStr;
        $this->data['perPage'] = $perPage = PERPAGE;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, "admin", $queryStr);
        $ret = $this->paginationCompress('admin/users/index', $cntPage, $perPage, 4);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], "admin", $queryStr);

        $this->data["tbl_content"] = $this->output_manage_content($this->data['list']);

        $this->data["subview"] = "admin/users/manage";

        if (!$this->checkRole()) {
            $this->load->view('admin/_layout_error', $this->data);
        } else {
            $this->load->view('admin/_layout_main', $this->data);
        }
    }

    public function output_manage_content($items)
    {
        $admin_id = $this->session->userdata("admin_loginuserID");
        $output = '';
        $btn_str = ['启用', '禁用', '编辑权限', '删除'];
        $type_str = ['无', '教师', '学生', '公众'];
        foreach ($items as $unit):
            $editable = $unit->status == 0;
            $schoolInfo = '';
            $userName = $unit->user_nickname;
//            if ($unit->user_info != null && $unit->user_info != '') {
//                $school = json_decode($unit->user_info);
//                if ($unit->user_type == '1') {
////                    $schoolInfo = $school->xxjgid_zh;
//                } else if ($unit->user_type == '2') {
////                    $schoolInfo = $school->xxjbxx_zh;
//                }
//                $schoolInfo = $school->organName;
//                $userName .= '(' . $school->nick_name . ')';
//                $userName = $school->nick_name;
//            }
            $usageInfo = $this->usage_m->getUserUsageInfo($unit->id);
            if ($usageInfo == null) {
                $usageInfo = (object)['total_read' => 0, 'total_like' => 0, 'total_favorite' => 0];
            }
            $output .= '<tr>';
            $output .= '<td><input type="checkbox" class="user-selector" data-id="' . $unit->id . '"></td>';
            $output .= '<td>' . $unit->user_account . '</td>';
            $output .= '<td>' . $userName . '</td>';
            $output .= '<td>' . $unit->user_city . '</td>';
            $output .= '<td>' . $unit->user_area . '</td>';
            $output .= '<td>' . $unit->user_school . '</td>';
            $output .= '<td>' . count(json_decode($unit->user_subjects)) . '</td>';
            $output .= '<td>' . $unit->create_time . '</td>';
            $output .= '<td>';

            $output .= '<button'
                . ' class="btn btn-sm ' . ($editable ? 'btn-default' : 'btn-warning') . '"'
                . ' onclick = "publishItem(this);"'
                . ' data-status = "' . $unit->status . '"'
                . ' data-id = "' . $unit->id . '">'
                . $btn_str[$unit->status] . '</button>';
            $output .= '<button'
                . ' class="btn btn-sm ' . ($editable ? 'btn-success' : 'disabled') . '" '
                . ' onclick = "' . ($editable ? 'resetItem(this);' : '') . '" '
                . ' data-id = "' . $unit->id . '"> '
                . '初始化密码</button>';
            $output .= '<button'
                . ' class="btn btn-sm ' . ($editable ? 'btn-success' : 'disabled') . '" '
                . ' onclick = "' . ($editable ? 'editItem(this);' : '') . '" '
                . ' data-info = \'' . $unit->user_subjects . '\''
                . ' data-id = "' . $unit->id . '"> '
                . $btn_str[2] . '</button>';
            $output .= '<button'
                . ' class="btn btn-sm ' . ($editable ? 'btn-danger' : 'disabled') . '"'
                . ' onclick = "' . ($editable ? 'deleteItem(this);' : '') . '"'
                . ' data-info = \'' . $unit->user_info . '\''
                . ' data-id = "' . $unit->id . '">'
                . $btn_str[3] . '</button>';
            $output .= '</td>';
            $output .= '</tr>';
        endforeach;
        return $output;
    }

    public function school_info()
    {
        $this->data['title'] = '学校信息统计';
        $this->data["subscript"] = "admin/settings/script";
        $this->data["subcss"] = "admin/settings/css";

        $this->data['userTypeList'] = [
            array('id' => '1', 'title' => "教师"),
            array('id' => '2', 'title' => "学生"),
            array('id' => '3', 'title' => "公众"),
        ];

        $filter = array();
        if ($this->uri->segment(SEGMENT) == '') $this->session->unset_userdata('filter');

        if ($_POST) {
            $this->session->unset_userdata('filter');
            $_POST['search_no'] != '' && $filter['tbl_user.user_school'] = $_POST['search_no'];
            $_POST['search_title'] != '' && $filter['tbl_user.user_info'] = $_POST['search_title'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $this->mainModel->prepareSchoolInfo();

        $this->data['perPage'] = $perPage = PERPAGE;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_school_count($filter);
        $ret = $this->paginationCompress('admin/users/school_info', $cntPage, $perPage, 4);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $this->mainModel->getSchoolItemsByPage($filter, $ret['pageId'], $ret['cntPerPage']);

        $this->data["tbl_content"] = $this->output_school_content($this->data['list']);

        $this->data["subview"] = "admin/usage/schools";

        if (!$this->checkRole(65)) {
            $this->load->view('admin/_layout_error', $this->data);
        } else {
            $this->load->view('admin/_layout_main', $this->data);
        }
    }

    public function output_school_content($items)
    {
        $admin_id = $this->session->userdata("admin_loginuserID");
        $output = '';
        $btn_str = ['启用', '禁用', '修改', '删除'];
        $type_str = ['无', '教师', '学生', '公众'];
        foreach ($items as $unit):
            $output .= '<tr>';
            $output .= '<td>' . ' - ' . '</td>';
            $output .= '<td>' . $unit->user_school . '</td>';
            $output .= '<td>' . $unit->user_count . '</td>';
            $output .= '<td>' . $unit->teacher_count . '</td>';
            $output .= '<td>' . $unit->student_count . '</td>';
//            $output .= '<td>';
//
////            $output .= '<button'
////                . ' class="btn btn-sm ' . ($editable ? 'btn-success' : 'disabled') . '" '
////                . ' onclick = "' . ($editable ? 'editItem(this);' : '') . '" '
////                . ' data-id = "' . $unit->id . '"> '
////                . $btn_str[2] . '</button>';
//            $output .= '<button'
//                . ' class="btn btn-sm ' . ($editable ? 'btn-danger' : 'disabled') . '"'
//                . ' onclick = "' . ($editable ? 'deleteItem(this);' : '') . '"'
//                . ' data-info = \'' . $unit->user_info . '\''
//                . ' data-id = "' . $unit->id . '">'
//                . $btn_str[3] . '</button>';
//            $output .= '<button'
//                . ' class="btn btn-sm ' . ($editable ? 'btn-default' : 'btn-warning') . '"'
//                . ' onclick = "publishItem(this);"'
//                . ' data-status = "' . $unit->status . '"'
//                . ' data-id = "' . $unit->id . '">'
//                . $btn_str[$unit->status] . '</button>';
//            $output .= '</td>';
            $output .= '</tr>';
        endforeach;
        return $output;
    }

    public function register_info()
    {
        $this->data['title'] = '登录信息统计';
        $this->data["subscript"] = "admin/settings/script";
        $this->data["subcss"] = "admin/settings/css";

        $this->data['subjectList'] = $this->subject_m->getItems();
        $this->data['termList'] = $this->terms_m->getItems();
        $this->data['userTypeList'] = [
            array('id' => '1', 'title' => "教师"),
            array('id' => '2', 'title' => "学生")
        ];

        $filter = array();
        if ($this->uri->segment(SEGMENT) == '') $this->session->unset_userdata('filter');

        if ($_POST) {
            $this->session->unset_userdata('filter');
            $_POST['search_no'] != '' && $filter['tbl_user.user_account'] = $_POST['search_no'];
            $_POST['search_title'] != '' && $filter['tbl_user.user_nickname'] = $_POST['search_title'];
            $_POST['search_type'] != '' && $filter['tbl_user.user_type'] = $_POST['search_type'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');

        $this->data['perPage'] = $perPage = PERPAGE;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter);
        $ret = $this->paginationCompress('admin/users/register_info', $cntPage, $perPage, 4);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage']);

        $this->data["tbl_content"] = $this->output_content_register_info($this->data['list']);

        $this->data["subview"] = "admin/users/register_info";

        if (!$this->checkRole(61)) {
            $this->load->view('admin/_layout_error', $this->data);
        } else {
            $this->load->view('admin/_layout_main', $this->data);
        }
    }

    public function output_content_register_info($items)
    {
        $admin_id = $this->session->userdata("admin_loginuserID");
        $output = '';
        $btn_str = ['启用', '禁用', '修改', '删除'];
        $type_str = ['无', '教师', '学生', '公众'];
        foreach ($items as $unit):
            $editable = $unit->status == 0;
//            $lastUsedTime = date_diff(date_create($unit->register_time), date_create($unit->update_time));
            $lastUsedTime = strtotime($unit->update_time) - strtotime($unit->register_time);
            if ($lastUsedTime < 0) $lastUsedTime = 0;
            $hh = intval($lastUsedTime / 3600);
            $mm = intval(($lastUsedTime % 3600) / 60) + 1;

            $output .= '<tr>';
            $output .= '<td>' . $unit->user_account . '</td>';
            $output .= '<td>' . $unit->user_nickname . '</td>';
            $output .= '<td>' . $type_str[$unit->user_type] . '</td>';
            $output .= '<td>' . $unit->register_time . '</td>';
            $output .= '<td>' . $unit->information . '</td>';
            $output .= '<td>' . $unit->register_count . '</td>';
            $output .= '<td>' . $hh . '小时' . $mm . "分钟" . '</td>';
            $output .= '</tr>';
        endforeach;
        return $output;
    }

    public function updateItem()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (true && !$this->adminsignin_m->loggedin()) {
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $ids = json_decode($this->input->post('id'));
            $subject_info = $this->input->post('subject_ids');

            //At first insert new coureware information to the database table
            $old = $this->mainModel->get_where_in('id', $ids);

            if ($old != null) {
                foreach ($old as $item) {
                    $this->mainModel->edit(array(
                        'user_subjects' => $subject_info,
                        'update_time' => date('Y-m-d H:i:s')
                    ), $item->id);
                }
            } else {
                $ret['data'] = '操作失败 : 用户不存在!';
                echo json_encode($ret);
                return;
            }
            $ret['data'] = '操作成功';
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    public function uploadCSV()
    {
        $ret = array(
            'data' => '操作失败',
            'status' => 'fail'
        );
        if (true && !$this->adminsignin_m->loggedin()) {
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $csvData = json_decode($this->input->post('csvData'));
            //At first insert new coureware information to the database table
            foreach ($csvData as $item) {
                $userItem = $this->mainModel->get_single_by_name(array('user_account' => $item->user_account));
                if ($userItem != null) continue;
                $item->password = $this->users_m->hash('1');
                $item->user_type = 1;
                $item->term_id = null;
                $item->status = 0;
                $item->information = '';
                $item->register_count = 0;
                $item->create_time = date('Y-m-d H:i:s');
                $item->register_time = date('Y-m-d H:i:s');
                $item->update_time = date('Y-m-d H:i:s');
                $result = $this->mainModel->insert((array)$item);
                if (!$result) {
                    echo json_encode($ret);
                    return;
                }
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
        if (!$this->adminsignin_m->loggedin()) {
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $items = $this->mainModel->publish($id, $status);
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
        if (!$this->adminsignin_m->loggedin()) {
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $list = $this->mainModel->delete($id);
            $ret['data'] = $ret['data'] = '操作成功';//$this->output_content($list);
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
        if (!$this->adminsignin_m->loggedin()) {
            echo json_encode($ret);
            return;
        }
        if ($_POST) {
            $id = $_POST['id'];
            $list = $this->mainModel->reset($id);
            $ret['data'] = $ret['data'] = '操作成功';//$this->output_content($list);
            $ret['status'] = 'success';
        }
        echo json_encode($ret);
    }

    function checkRole($id = 60)
    {
        $permission = $this->session->userdata('admin_user_type');
        if ($permission != NULL) {
            $permissionData = (array)(json_decode($permission));
            $accessInfo = $permissionData['menu_' . $id];
            if ($accessInfo == '1') return true;
            else return false;
        }
        return false;
    }
}

?>