<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Signin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $language = 'chinese';
        $this->load->model("signin_m");
        $this->lang->load('main', $language);
        $this->load->library("pagination");
        $this->load->library("session");
        $this->load->library('form_validation');

        $this->mainModel = $this->signin_m;
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'account',
                'label' => "账号",
                'rules' => 'trim|required|max_length[30]'
            ),
            array(
                'field' => 'password',
                'label' => "密码",
                'rules' => 'trim|required|max_length[30]'
            )
        );
        return $rules;
    }

    public function index()
    {

        $this->signin_m->isloggedin() == FALSE || redirect(base_url('home'));

        $this->data["subscript"] = "settings/script";
        $this->data["subcss"] = "settings/css";
        $this->data['apiRoot'] = $apiRoot = 'signin';
        $this->data['mainModel'] = 'tbl_user';

        $this->data["subview"] = "signin/index";

        $this->data['form_validation'] = '';
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->load->view('_layout_signin', $this->data);
            } else {
                if ($this->signin_m->signin() == TRUE) {
                    redirect(base_url('home/index'));
                } else {
                    $this->session->set_flashdata("errors", "请登录");
                    $this->data['form_validation'] = "登录信息无效";
                    $this->load->view('_layout_signin', $this->data);
                }
            }
            return;
        }

        $this->load->view('_layout_signin', $this->data);
        $this->session->sess_destroy();
    }

    public function signout()
    {
        $this->mainModel->signout();
        redirect(base_url("signin/index"));
    }
}

?>