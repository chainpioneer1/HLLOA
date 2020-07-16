<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Userprices extends CI_Controller
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
        $this->lang->load('main', $language);
        $this->load->library("pagination");
        $this->load->library("session");

        $this->mainModel = $this->userprice_m;
    }

    public function index()
    {
        $this->salary();
    }

    public function salary()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '行政管理 ＞ 工资管理';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'userprices/salary';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_user';

        $this->data['menu'] = 13;

        $userId = $this->session->userdata('_userid');
        $roleId = $this->session->userdata('_role_id');
        $partId = $this->session->userdata('_part_id');
        $this->data['partList'] = $this->userpart_m->getItems();
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
            $_POST['search_part'] != '' && $filter['tbl_user_part.id'] = $_POST['search_part'];
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        $filterStr = " tbl_union.completed_at >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_union.completed_at < '{$filter['range_to']}' ";
        $filterStr = "'1' = '1'";
        if (isset($filter['tbl_user_part.id'])) $filterStr .= " and tbl_user_part.id = {$filter['tbl_user_part.id']} ";
        if (isset($filter['tbl_user.id'])) $filterStr .= " and tbl_user.id = {$filter['tbl_user.id']} ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data['perPage'] = $perPage = 1000;
        $this->data['cntPage'] = $cntPage = 1000;

        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $list = $this->mainModel->getUserSalaryByPage($filterStr, $ret['pageId'], $ret['cntPerPage'],
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
        $this->data["tbl_content"] = $this->output_content_salary($resultList, $startNo);

        if (!$this->checkRole(13)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_salary($items, $startNo = 0)
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
            $output .= '<tr data-id="' . $unit->id . '">';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->part . '</td>';
            $output .= '<td>' . $unit->position . '</td>';
//            $output .= '<td><div class="user-avatar" '
//                . ' style="background-image:url(' . $avatar . ');' . $colorStyle . '">'
//                . '</div></td>';
            $output .= '<td>' . $unit->name . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="e">' . $unit->e . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="f">' . $unit->gangwei_price . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="g">' . $unit->f . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="h">' . $unit->g . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="i">' . $unit->h . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="j">' . $unit->i . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="k">' . $unit->j . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="l">' . $unit->k . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="m">' . $unit->l . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="n">' . $unit->m . '</td>';
            // 应发工资 price1 = gangwei_price + f + g - i - k
            $price1 = $unit->gangwei_price + $unit->f + $unit->g - $unit->i - $unit->k;
            $output .= '<td data-id="' . $unit->id . '" data-col="o">' . $price1 . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="p">' . $unit->n . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="q">' . $unit->o . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="r">' . $unit->p . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="s">' . $unit->q . '</td>';
            // 计税部分 price2 = price1 - n - o - p - q - 5000
            $price2 = $price1 - $unit->n - $unit->o - $unit->p - $unit->q - 5000;
            if ($price2 < 0) $price2 = 0;
            $output .= '<td data-id="' . $unit->id . '" data-col="t">' . $price2 . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="u">' . $unit->r . '</td>';
            // 实发工资 price3 = price1 - n - o - p - q - r
            $price3 = $price1 - $unit->n - $unit->o - $unit->p - $unit->q - $unit->r;
            $output .= '<td data-id="' . $unit->id . '" data-col="v">' . $price3 . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="w">' . $unit->standard_factor . '</td>';
            // 本月最低合格绩效 price4 = standard_factor / 21.75 * workdays
            $price4 = $unit->s; //floatval(intval($unit->standard_factor / 21.75 * $unit->workdays * 100) / 100);
            $output .= '<td data-id="' . $unit->id . '" data-col="x">' . $price4 . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="y">' . $score . '</td>';
            // 绩效奖金 price5 = (score - price4）* rank_factor
            $price5 = floatval(intval(($score - $price4) * $unit->rank_factor * 100) / 100);
            if ($score < $price4 || $price4 == 0) $price5 = 0;
            $output .= '<td data-id="' . $unit->id . '" data-col="z">' . $price5 . '</td>';
//            $output .= '<td>' . $standard . '</td>';
//            $output .= '<td>' . $profit . '</td>';
//            $output .= '<td>';
//            $output .= '<div class="btn-rect btn-orange" onclick="viewItem(this);"'
//                . ' data-id="' . $unit->id . '" '
//                . '>本月任务详情</div>';
//            $output .= '</td>';
            $output .= '</tr>';
        endforeach;

        $output .= '<tr data-id="-1">';
        $output .= '<td colspan="3">---</td>';
        $output .= '<td>合计</td>';
        $output .= '<td data-id="-1" data-col="e"></td>';
        $output .= '<td data-id="-1" data-col="f"></td>';
        $output .= '<td data-id="-1" data-col="g"></td>';
        $output .= '<td data-id="-1" data-col="h"></td>';
        $output .= '<td data-id="-1" data-col="i"></td>';
        $output .= '<td data-id="-1" data-col="j"></td>';
        $output .= '<td data-id="-1" data-col="k"></td>';
        $output .= '<td data-id="-1" data-col="l"></td>';
        $output .= '<td data-id="-1" data-col="m"></td>';
        $output .= '<td data-id="-1" data-col="n"></td>';
        $output .= '<td data-id="-1" data-col="o"></td>';
        $output .= '<td data-id="-1" data-col="p"></td>';
        $output .= '<td data-id="-1" data-col="q"></td>';
        $output .= '<td data-id="-1" data-col="r"></td>';
        $output .= '<td data-id="-1" data-col="s"></td>';
        $output .= '<td data-id="-1" data-col="t"></td>';
        $output .= '<td data-id="-1" data-col="u"></td>';
        $output .= '<td data-id="-1" data-col="v"></td>';
        $output .= '<td data-id="-1" data-col="w"></td>';
        $output .= '<td data-id="-1" data-col="x"></td>';
        $output .= '<td data-id="-1" data-col="y"></td>';
        $output .= '<td data-id="-1" data-col="z"></td>';
        $output .= '</tr>';

        return $output;
    }

    public function mine()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '个人中心 ＞ 我的工资';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'userprices/mine';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_user';

        $this->data['menu'] = -1;

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
        $filter['tbl_user.id'] = $userId;
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
        $filterStr = " tbl_union.completed_at >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_union.completed_at < '{$filter['range_to']}' ";
        $filterStr = "'1' = '1'";
        if (isset($filter['tbl_user_part.id'])) $filterStr .= " and tbl_user_part.id = {$filter['tbl_user_part.id']} ";
        if (isset($filter['tbl_user.id'])) $filterStr .= " and tbl_user.id = {$filter['tbl_user.id']} ";
        $this->data['search_keyword'] = $filter['queryStr'] . '';
        $this->data['range_from'] = $filter['range_from'] . '';
        $this->data['range_to'] = $filter['range_to'] . '';
        unset($filter['queryStr']);
        unset($filter['range_from']);
        unset($filter['range_to']);

        $this->data['perPage'] = $perPage = 1000;
        $this->data['cntPage'] = $cntPage = 1000;

        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $list = $this->mainModel->getUserSalaryByPage($filterStr, $ret['pageId'], $ret['cntPerPage'],
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
        $this->data["tbl_content"] = $this->output_content_mine($resultList, $startNo);

        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_mine($items, $startNo = 0)
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
            $output .= '<tr data-id="' . $unit->id . '">';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->part . '</td>';
            $output .= '<td>' . $unit->position . '</td>';
//            $output .= '<td><div class="user-avatar" '
//                . ' style="background-image:url(' . $avatar . ');' . $colorStyle . '">'
//                . '</div></td>';
            $output .= '<td>' . $unit->name . '</td>';
            $output .= '<td>' . $unit->refdate . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="e">' . $unit->e . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="f">' . $unit->gangwei_price . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="g">' . $unit->f . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="h">' . $unit->g . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="i">' . $unit->h . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="j">' . $unit->i . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="k">' . $unit->j . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="l">' . $unit->k . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="m">' . $unit->l . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="n">' . $unit->m . '</td>';
            // 应发工资 price1 = gangwei_price + f + g - i - k
            $price1 = $unit->gangwei_price + $unit->f + $unit->g - $unit->i - $unit->k;
            $output .= '<td data-id="' . $unit->id . '" data-col="o">' . $price1 . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="p">' . $unit->n . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="q">' . $unit->o . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="r">' . $unit->p . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="s">' . $unit->q . '</td>';
            // 计税部分 price2 = price1 - n - o - p - q - 5000
            $price2 = $price1 - $unit->n - $unit->o - $unit->p - $unit->q - 5000;
            if ($price2 < 0) $price2 = 0;
            $output .= '<td data-id="' . $unit->id . '" data-col="t">' . $price2 . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="u">' . $unit->r . '</td>';
            // 实发工资 price3 = price1 - n - o - p - q - r
            $price3 = $price1 - $unit->n - $unit->o - $unit->p - $unit->q - $unit->r;
            $output .= '<td data-id="' . $unit->id . '" data-col="v">' . $price3 . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="w">' . $unit->standard_factor . '</td>';
            // 本月最低合格绩效 price4 = standard_factor / 21.75 * workdays
            $price4 = $unit->s;//floatval(intval($unit->standard_factor / 21.75 * $unit->workdays * 100) / 100);
            $output .= '<td data-id="' . $unit->id . '" data-col="x">' . $price4 . '</td>';
            $output .= '<td data-id="' . $unit->id . '" data-col="y">' . $score . '</td>';
            // 绩效奖金 price5 = (score - price4）* rank_factor
            $price5 = floatval(intval(($score - $price4) * $unit->rank_factor * 100) / 100);
            if ($score < $price4 || $price4 == 0) $price5 = 0;
            $output .= '<td data-id="' . $unit->id . '" data-col="z">' . $price5 . '</td>';
//            $output .= '<td>' . $standard . '</td>';
//            $output .= '<td>' . $profit . '</td>';
//            $output .= '<td>';
//            $output .= '<div class="btn-rect btn-orange" onclick="viewItem(this);"'
//                . ' data-id="' . $unit->id . '" '
//                . '>本月任务详情</div>';
//            $output .= '</td>';
            $output .= '</tr>';
        endforeach;

        $output .= '<tr data-id="-1">';
        $output .= '<td colspan="3">---</td>';
        $output .= '<td>合计</td>';
        $output .= '<td></td>';
        $output .= '<td data-id="-1" data-col="e"></td>';
        $output .= '<td data-id="-1" data-col="f"></td>';
        $output .= '<td data-id="-1" data-col="g"></td>';
        $output .= '<td data-id="-1" data-col="h"></td>';
        $output .= '<td data-id="-1" data-col="i"></td>';
        $output .= '<td data-id="-1" data-col="j"></td>';
        $output .= '<td data-id="-1" data-col="k"></td>';
        $output .= '<td data-id="-1" data-col="l"></td>';
        $output .= '<td data-id="-1" data-col="m"></td>';
        $output .= '<td data-id="-1" data-col="n"></td>';
        $output .= '<td data-id="-1" data-col="o"></td>';
        $output .= '<td data-id="-1" data-col="p"></td>';
        $output .= '<td data-id="-1" data-col="q"></td>';
        $output .= '<td data-id="-1" data-col="r"></td>';
        $output .= '<td data-id="-1" data-col="s"></td>';
        $output .= '<td data-id="-1" data-col="t"></td>';
        $output .= '<td data-id="-1" data-col="u"></td>';
        $output .= '<td data-id="-1" data-col="v"></td>';
        $output .= '<td data-id="-1" data-col="w"></td>';
        $output .= '<td data-id="-1" data-col="x"></td>';
        $output .= '<td data-id="-1" data-col="y"></td>';
        $output .= '<td data-id="-1" data-col="z"></td>';
        $output .= '</tr>';

        return $output;
    }

    public function manage()
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '行政管理 ＞ 工资管理 ＞ 编辑工资信息';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'userprices/manage';
        $this->data["subview"] = $apiRoot;
        $this->data['mainModel'] = 'tbl_user';

        $this->data['menu'] = 13;

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


        $filter['range_from'] = date('Y-m-01');
        $filter['range_to'] = date('Y-m-01', strtotime('+1 months'));
        $startNo = 0;
        if ($this->uri->segment(3) != '') $startNo = $this->uri->segment(3);
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $filter['queryStr'] = $_POST['search_keyword'];
            if ($_POST['ref_month'] != '') {
                $filter['range_from'] = $_POST['ref_month'] . '-01';
                $date = date_create($filter['range_from']);
                date_add($date, date_interval_create_from_date_string("1 month"));
                $filter['range_to'] = date_format($date, "Y-m-d H:i:s");
            }
            $this->session->set_userdata('filter', $filter);
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        $filterStr = " tbl_user_price.refdate >= '{$filter['range_from']}' ";
        $filterStr .= " and tbl_user_price.refdate < '{$filter['range_to']}' ";
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

        $this->data['perPage'] = $perPage = 10000;
        $this->data['cntPage'] = $cntPage = 10000;

        $ret = $this->paginationCompress($apiRoot, $cntPage, $perPage, 3);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $list = $this->mainModel->getItemsByPage($filterStr, $ret['pageId'], $ret['cntPerPage'],
            $queryStr);
        $this->data["userList"] = $userList = $this->users_m->getItems();

        if ($list == null) {
            foreach ($userList as $item) {
                if ($item->is_calc_score == '0') continue;
                $workdays = date_diff(date_create($this->data['range_from']), date_create($this->data['range_to']));
                $this->mainModel->add(array(
                    'workdays' => $workdays->format('%a'),
                    'refdate' => $this->data['range_from'],
                    'user_id' => $item->id,
                    'create_time' => date('Y-m-d H:i:s'),
                    'update_time' => date('Y-m-d H:i:s'),
                ));
            }
            $this->data["list"] = $list = $this->mainModel->getItemsByPage($filterStr, $ret['pageId'], $ret['cntPerPage'],
                $queryStr);
        }
        $resultList = $list;
        $this->data['workdays'] = $resultList[0]->workdays;
        $this->data["tbl_content"] = $this->output_content_manage($resultList, $startNo);

        if (!$this->checkRole(13)) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_manage($items, $startNo = 0)
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
            $output .= '<tr data-id="' . $unit->id . '">';
            $output .= '<td>' . sprintf("%02d", $startNo) . '</td>';
            $output .= '<td>' . $unit->part . '</td>';
            $output .= '<td>' . $unit->position . '</td>';
//            $output .= '<td><div class="user-avatar" '
//                . ' style="background-image:url(' . $avatar . ');' . $colorStyle . '">'
//                . '</div></td>';
            $output .= '<td>' . $unit->name . '</td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="e" value="' . $unit->e . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="f" value="' . $unit->f . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="g" value="' . $unit->g . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="h" value="' . $unit->h . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="i" value="' . $unit->i . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="j" value="' . $unit->j . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="k" value="' . $unit->k . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="l" value="' . $unit->l . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="m" value="' . $unit->m . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="n" value="' . $unit->n . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="o" value="' . $unit->o . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="p" value="' . $unit->p . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="q" value="' . $unit->q . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="r" value="' . $unit->r . '"/></td>';
            $output .= '<td><input data-id="' . $unit->id . '" data-col="s" value="' . $unit->s . '"/></td>';
//            $output .= '<td>' . $standard . '</td>';
//            $output .= '<td>' . $profit . '</td>';
//            $output .= '<td>';
//            $output .= '<div class="btn-rect btn-orange" onclick="viewItem(this);"'
//                . ' data-id="' . $unit->id . '" '
//                . '>本月任务详情</div>';
//            $output .= '</td>';
            $output .= '</tr>';
        endforeach;

        $output .= '<tr data-id="-1" >';
        $output .= '<td colspan="3">---</td>';
        $output .= '<td>合计</td>';
        $output .= '<td><input data-id="-1" data-col="e" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="f" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="g" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="h" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="i" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="j" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="k" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="l" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="m" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="n" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="o" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="p" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="q" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="r" disabled/></td>';
        $output .= '<td><input data-id="-1" data-col="s" disabled/></td>';
        $output .= '</tr>';

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
        if ($_POST) {
            $jsonData = json_decode($this->input->post('jsonData'));
            $user_id = $this->session->userdata('_userid');
            foreach ($jsonData as $item) {
                $editArr = $item;
                $id = $item->id;
                unset($editArr->id);
                $editArr->refdate = $editArr->refdate . '-01';
                $editArr->update_time = date('Y-m-d H:i:s');
                $this->mainModel->edit($editArr, $id);
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