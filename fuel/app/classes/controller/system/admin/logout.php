<?php

class Controller_System_Admin_logout extends Controller_System_Admin
{
    public function action_index()
    {
        // ログアウト
        $this->template->title = "ログアウト";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/logout');
    }

    public function post_index()
    {
        // ログアウト時はセッションを消し、ログインステータスも0にする。
        $current_admin = Session::get("current_admin", null);
        $admin = Model_Admin::find_one_by("admin_id", $current_admin["admin_id"]);
        $admin->login_status = 0;
        $admin->save();
        
        Session::delete("current_admin");
        Response::redirect("system/admin/login/");
    }
}