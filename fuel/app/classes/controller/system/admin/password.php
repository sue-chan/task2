<?php

class Controller_System_Admin_password extends Controller_System_Admin
{
    public function get_index()
    {
        // ハッシュ化パスワード生成

        // getで来た時は、ランダムなパスワードを生成しておく。(getで直接来る場合の機能を作る必要はなかったなと今は思います。)
        $random_password = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 12);
        $data["post"]["login_password"] = $random_password;

        // ビューを表示
        $this->template->title = "ハッシュ化パスワード生成";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/password', $data);
    }

    public function post_index()
    {
        $data["post"] = Input::post();

        // ハッシュ化画面で「生成」が押された場合の処理
        if (!empty(Input::post("hash_make"))) {
            // パスワードのバリデーション
            if (ctype_alpha(Input::post("login_password")) and strlen(Input::post("login_password")) >= 6) {
                // ハッシュ化
                $hash = password_hash(Input::post("login_password"), PASSWORD_BCRYPT);
                $data["post"]["hash"] = $hash;
            } else {
            // バリデーションが失敗したらエラーメッセージを残す
                $data["post"]["message"] = "パスワードは6文字以上で半角英字のみで構成してください。";
            }
            // フォームから来た、かつ、パスワードが入力されていた時は、そのまま
        } elseif (!empty(Input::post("login_password"))) {

        } else {
            // フォームから来た、かつ、パスワードが入力されていない時は、ランダムなパスワードを生成
            $random_password = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 12);
            $data["post"]["login_password"] = $random_password;
        }

        // ビューを渡す
        $this->template->title = "ハッシュ化パスワード生成";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/password', $data);
    }
}