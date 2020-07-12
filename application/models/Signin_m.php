<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Signin_m extends MY_Model
{

    function __construct()
    {
        $this->load->model('users_m');
        parent::__construct();
    }

    public function signout()
    {
        //$this->session->sess_destroy();
        $this->session->unset_userdata('_userid');
        $this->session->unset_userdata('_account');
        $this->session->unset_userdata('_avatar');
        $this->session->unset_userdata('_name');
        $this->session->unset_userdata('_role_id');
        $this->session->unset_userdata('_part_id');
        $this->session->unset_userdata('_position_id');
        $this->session->unset_userdata('_rank_id');
        $this->session->unset_userdata('_permission');
        $this->session->unset_userdata('_isloggedin');
    }

    public function isloggedin()
    {
        $isLoggedIn = (bool)$this->session->userdata("_isloggedin");
        if ($isLoggedIn) $this->users_m->edit(
            array('update_time' => date("Y-m-d H:i:s")),
            $this->session->userdata("_userid")
        );
        return $isLoggedIn;
    }

    public function signin($account = '', $password = '')
    {
        if ($account == '') {
            $account = $this->input->post('account');
            $password = $this->input->post('password');
            $password = $this->users_m->hash($password);
        }

        $user = $this->users_m->getItems(array(
            'tbl_user.account' => $account,
            'tbl_user.password' => $password,
            'tbl_user.status' => 1
        ));

        if ($user == null) return FALSE;

        $user = $user[0];

        if (substr($user->register_time, 0, 10) != date('Y-m-d'))
            $this->setLoginAction('login');

        $this->signout();

        $data = array(
            "_userid" => $user->id,
            "_account" => $user->account,
            "_avatar" => $user->avatar,
            "_name" => $user->name,
            "_role_id" => $user->role_id,
            "_part_id" => $user->part_id,
            "_position_id" => $user->position_id,
            "_rank_id" => $user->rank_id,
            "_permission" => $user->permission,
            "_isloggedin" => TRUE
        );
        $this->session->set_userdata($data);
        $user_id = $this->session->userdata("_userid");
        $arr = array(
            "register_time" => date('Y-m-d H:i:s'),
            "update_time" => date('Y-m-d H:i:s'),
            "information" => $this->users_m->get_client_ip(),
            "register_count" => $user->register_count + 1
        );
        $this->users_m->edit($arr, $user_id);
        return TRUE;
    }

    public function setLoginAction($type = 'login')
    {
        return true;
        $this->db->from('tbl_user_action');
        $this->db->where('action_date', date('Y-m-d'));
        $query = $this->db->get();
        $result = $query->result();
        if ($result == null) {
            $register = 0;
            if ($type == 'register') $register = 1;
            $this->db->insert('tbl_user_action', array(
                'action_date' => date('Y-m-d'),
                'register_count' => $register,
                'login_count' => 1
            ));
        } else {
            $this->db->where('id', $result[0]->id);
            $arr = array();
            switch ($type) {
                case 'register':
                    $arr['register_count'] = $result[0]->register_count + 1;
                case 'login':
                    $arr['login_count'] = $result[0]->login_count + 1;
                    break;
            }
            $this->db->update('tbl_user_action', $arr);
        }
    }

    public function setPlatformLogin($platform = 'pcweb')
    {
        if ($platform != 'pcweb' && $platform != 'mweb' &&
            $platform != 'android' && $platform != 'ios') {
            return false;
        }
        $sql = "update tbl_user_action set login_{$platform} = login_{$platform} + 1 " .
            "where action_date = '" . date('Y-m-d') . "'";
        $result = $this->db->query($sql);
        return $result;
    }

}
/* End of file signin_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/signin_m.php */
