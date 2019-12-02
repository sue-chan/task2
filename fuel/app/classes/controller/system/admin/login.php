<?php

class Controller_System_Admin_Login extends Controller
{

    public function get_index() {
        return View::forge("system/admin/login");
    }

    public function post_index() {
        // ログインIDから該当ユーザーを検索
        if(Security::check_token()){
            $admin = Model_Admin::find_one_by("login_id", Input::post("login_id"));

            // 該当ユーザーがいなかった場合はエラーメッセージと共にログイン画面へ
            if (is_null($admin)) {
                $data["error_message"] = "ユーザー名かパスワードが正しくありません。";
                return Response::forge(View::forge('system/admin/login', $data));
            }
            // パスワードが正しくない場合はエラーメッセージと共にログイン画面へ
            if(!password_verify(Input::post("login_password"), $admin->login_password)) {
                $data["error_message"] = "ユーザー名かパスワードが正しくありません。";
                return Response::forge(View::forge('system/admin/login', $data));
            } else {
                // ログインが成功したら、login_passportにランダムな文字列を入れて、login_statusをonにする。
                $admin->login_passport = sha1(uniqid(rand(), true));
                $admin->login_status = 1;
                $admin->save();

                // セッションに認証チェックに必要な情報と権限をいれる。
                Session::set(
                    "current_admin", array(
                        "admin_id" => $admin->admin_id,
                        "login_passport" => $admin->login_passport,
                        "admin_permission_admin" => $admin->admin_permission_admin,
                        "admin_permission_config" => $admin->admin_permission_config,
                        "admin_permission_master" => $admin->admin_permission_master,
                        "admin_permission_log" => $admin->admin_permission_log,
                        "admin_permission_special" => $admin->admin_permission_special,
                        "admin_permission_show" => $admin->admin_permission_show,
                        "admin_permission_edit" => $admin->admin_permission_edit,
                        "admin_permission_delete" => $admin->admin_permission_delete,
                    )
                );
            }
            // トップページへ遷移する
            Response::redirect("system/admin/");
        }
    }
}