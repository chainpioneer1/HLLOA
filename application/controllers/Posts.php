<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends CI_Controller
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
        $this->data['mainModel'] = $model = 'tbl_post';
        $this->session->set_userdata('filter', array($model . '.type' => 0));
        $this->manage();
    }

    public function manage($mId = 12, $type = 0)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }
        $this->data['title'] = '行政管理 ＞ 公告信息管理';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'posts/manage';
        $this->data['mainModel'] = $model = 'tbl_post';

        $this->data['menuId'] = $mId;
        $this->data['userList'] = $this->users_m->getItems();

        $filter = array();
//        if ($this->uri->segment(SEGMENT) == '') $this->session->unset_userdata('filter');
        if ($_POST) {
            $this->session->unset_userdata('filter');
            $_POST['_type'] != '' && $filter[$model . '.type'] = $_POST['_type'];
            $filter['queryStr'] = $_POST['search_keyword'];
            $this->session->set_userdata('filter', $filter);
        } else {
            $this->session->set_userdata('filter', array($model . '.type' => $type));
        }
        $this->session->userdata('filter') != '' && $filter = $this->session->userdata('filter');
        $queryStr = $filter['queryStr'] . '';
        unset($filter['queryStr']);
        $this->data['perPage'] = $perPage = 8;
        $this->data['cntPage'] = $cntPage = $this->mainModel->get_count($filter, $queryStr);
        $ret = $this->paginationCompress($apiRoot . '/' . $mId, $cntPage, $perPage, 4);
        $this->data['curPage'] = $curPage = $ret['pageId'];
        $this->data["list"] = $this->mainModel->getItemsByPage($filter, $ret['pageId'], $ret['cntPerPage'], $queryStr);

        $this->data['typeCnt'] = array(
            $this->mainModel->get_count(array($model . '.type' => 0), $queryStr),
            $this->mainModel->get_count(array($model . '.type' => 1), $queryStr)
        );

//        $this->data["tbl_content"] = $this->output_content($this->data['list']);

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

    public function view($id)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'posts/view';
        $this->data["subview"] = $apiRoot;
        $this->data['menuId'] = 0;

        $postdata = $this->mainModel->get_where(array('id' => $id));

        $this->data['postdata'] = '';
        if ($postdata) {
            $ext = explode('.', $postdata[0]->data);
            $ext = $ext[count($ext) - 1];
            switch ($ext) {
                case 'pdf':
                    $this->data['postdata'] = base_url() . $postdata[0]->data;
                    break;
                case 'doc':
                case 'docx':
                    $this->data['postdata'] =
                        'https://view.officeapps.live.com/op/embed.aspx?src=' .
                        base_url() . $postdata[0]->data;
                    break;
            }
        }

        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_post', $this->data);
        }
    }

    public function viewlist($menu = 0)
    {
        if (!$this->checkRole()) {
            redirect(base_url('signin'));
            return;
        }

        $this->data['title'] = '首页 ＞ 更多公告';
        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $apiRoot = 'posts/viewlist';
        $this->data['mainModel'] = $model = 'tbl_post';
        $this->data["subview"] = $apiRoot;

        $this->data['menu'] = $menu;

        $this->data['list'] = $list = $this->mainModel->get_where(array('type' => 0));
        $this->data["tbl_content"] = $this->output_content_viewlist($this->data['list']);

        if (!$this->checkRole()) {
            $this->load->view('_layout_error', $this->data);
        } else {
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function output_content_viewlist($items)
    {
        $output = '';
        foreach ($items as $unit):
            $ext = explode('.', $unit->data);
            $ext = $ext[count($ext) - 1];
            if ($ext != 'pdf') $ext = 'word';
            $output .= '<tr>';
            $output .= '<td data-id="' . $unit->id . '" '
                . 'onclick="viewItem(this);">'
                . '<i class="fa fa-file-' . $ext . '" '
                .' style="font-size: 16px;color:#5f68e6;margin-right: 10px;"></i>'
                . $unit->title
                . '</td>';
            $output .= '<td>' . $unit->update_time . '</td>';
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
        if (!$this->checkRole()) {
            $ret['data'] = '用户权限错误';
            echo json_encode($ret);
            return;
        }
        $user_id = $this->session->userdata('_userid');
        if ($_POST) {
            $type = $this->input->post('type');
            $id = $this->input->post('id');
            $title = $this->input->post('title');
            if (!$title) {
                $ret['data'] = '标题无效';
                echo json_encode($ret);
                return;
            }
            $editArr = array(
                'author_id' => $user_id,
                'type' => $type,
                'status' => 1,
                'update_time' => date("Y-m-d H:i:s")
            );
            switch ($type) {
                case '0':
                    $editArr['title'] = $this->input->post('title');
                    if ($_FILES["docFile"]["name"] == '') break;
                    if ($id == 0) {
                        $editArr['create_time'] = date("Y-m-d H:i:s");
                        $id = $this->mainModel->add($editArr);
                    }
                    $config['upload_path'] = "./uploads/posts";
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path']);
                    }
                    $config['allowed_types'] = '*';
                    $filename = 'post_' . $id;
                    $fileFormat = strtolower($this->input->post('docFileFormat'));
                    $this->load->library('upload', $config);

                    $fileData = '';
                    if ($_FILES["docFile"]["name"] != '') {
                        $nameSuffix = '';
                        $config['file_name'] = $filename . $nameSuffix . '.' . $fileFormat;
                        if (file_exists(substr($config['upload_path'], 2) . '/' . $config['file_name'])) {
                            unlink(substr($config['upload_path'], 2) . '/' . $config['file_name']);
                        }
                        $this->upload->initialize($config, TRUE);
                        switch ($fileFormat) {
                            case 'doc':
                            case 'docx':
                            case 'pdf':
                                ///Image file uploading........
                                if ($this->upload->do_upload('docFile')) {
                                    $data = $this->upload->data();
                                    $fileData = substr($config['upload_path'], 2) . '/' . $config['file_name'];
                                } else {
                                    $ret['data'] = '文档上传错误' . $this->upload->display_errors();
                                    $ret['status'] = 'fail';
                                    echo json_encode($ret);
                                    return;
                                }
                                break;
                        }
                    }
                    if ($fileData) $editArr['data'] = $fileData;
                    break;
                case '1':
                    $editArr['title'] = $this->input->post('title');
                    if ($_FILES["imgFile"]["name"] == '') break;
                    if ($id == 0) {
                        $editArr['create_time'] = date("Y-m-d H:i:s");
                        $id = $this->mainModel->add($editArr);
                    }
                    $config['upload_path'] = "./uploads/posts";
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path']);
                    }
                    $config['allowed_types'] = '*';
                    $filename = 'post_' . $id;
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
                    if ($fileData) $editArr['data'] = $fileData;
                    break;
            }

            $result = 0;
            if ($editArr['title'] != '') {
                if ($id == 0) {
                    $editArr['create_time'] = date("Y-m-d H:i:s");
                    $result = $this->mainModel->add($editArr);
                } else {
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