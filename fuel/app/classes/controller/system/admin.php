<?php

// このクラスは認証が必要な全てのページから継承されているため、毎回このログイン認証がなされます。
class Controller_System_Admin extends Controller_Template
{
    public function before()
    {
        // templateクラスのbefore()を呼び出す
        parent::before();

        // 管理者かどうかチェック
        $current_admin = Session::get("current_admin", null);

        if (!is_null($current_admin)) {
            if (isset($current_admin["admin_id"]) and isset($current_admin["login_passport"])) {
                // セッションに格納されているユーザー情報から特定する

                $admin = Model_Admin::find(
                    array(
                        "where" => array(
                            "admin_id" => $current_admin["admin_id"],
                            "login_passport" => $current_admin["login_passport"]
                        )
                    )
                );

                if (!is_null($admin)) {
                    // 存在していれば処理続行
                    return;
                } else {
                    // ログインしていなかったら、ログイン画面へ
                    return Response::redirect("system/admin/login/");
                }
            } else {
                return Response::redirect("system/admin/login/");
            }
        } else {
            return Response::redirect("system/admin/login/");
        }
    }

    public function action_index()
    {
        // トップ(ダッシュボードをロード)
        $this->template->title = "トップ";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin');
    }

}