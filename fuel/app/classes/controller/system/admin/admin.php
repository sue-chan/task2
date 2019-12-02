<?php

class Controller_System_Admin_admin extends Controller_System_Admin
{
    public function before()
    {
        // 親クラスをオーバーライドしないように呼び出す
        parent::before();

        // 今ログインしているユーザーが閲覧の権限がない場合は、「権限がありません」ページに遷移する
        $current_admin = Session::get("current_admin", null);
        if ($current_admin["admin_permission_show"] == 0) {
            return Response::redirect("system/admin/nonpermission");
        }
    }

    public function action_index()
    {
        // 管理者一覧

        $data["admins"] = Model_Admin::find_all();
        
        // ビューを返す。(Templateを使用)
        $this->template->title = "管理者一覧";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/admin/index', $data);
    }

    public function get_new()
	{
        // 管理者新規追加

		// エラーメッセージや直前のポストがセッションに記録されていればそれを取得する。
		$data["session"] = Session::get_flash(
            "session",
             array()
        );
        // 管理者新規追加画面へ
        $this->template->title = "管理者新規追加";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/admin/new', $data);
	}

	public function post_new()
	{    
        // パスワードハッシュ化画面から来たときの処理
        if (!empty(Input::post("from_hash_password"))) {
            // ビューでセッションという変数名を使っているため、本当はセッションではないが、変数名を合わせるため、"session"にデータを格納する。
            $data["session"] = array(
                "posts" => Input::post(),
                "validator_errors" => array()
            );
            // 権限のデータをcheckboxを使うに当たって、使いやすいようなデータに変換する。(ここら辺の処理は探り探りやっているうちにごちゃごちゃしてしまいました。)
            $data["session"]["posts"]["admin_permission"] = array();
            $permissions = array("admin", "config", "master", "log", "special", "show", "edit", "delete");
            foreach ($permissions as $permission) {
                if (!empty(Input::post("admin_permission_".$permission))) {
                    $data["session"]["posts"]["admin_permission"][] = $permission; 
                }
            }
            // 管理者新規追加画面へ
            $this->template->title = "管理者新規追加";
            $this->template->header = View::forge('parts/header');
            $this->template->content = View::forge('system/admin/admin/new', $data);
        } else {
		if(Security::check_token()){
            // ポストされた内容をモデルに登録する。
			$admin = Model_Admin::forge(
				array(
					"login_nickname" => Input::post("login_nickname"),
                    "admin_email" => Input::post("admin_email"),
                    "login_id" => Input::post("login_id"),
                    "login_password" => Input::post("login_password"),
                    "login_passport" => "a",
                    "login_status" => Input::post("login_status"),
                )
            );
            // 権限を登録
            if (!empty(Input::post("admin_permission"))) {
                if (in_array("admin", Input::post("admin_permission"))) {
                    $admin->admin_permission_admin = 1;
                }
                if (in_array("config", Input::post("admin_permission"))) {
                    $admin->admin_permission_config = 1;
                }
                if (in_array("master", Input::post("admin_permission"))) {
                    $admin->admin_permission_master = 1;
                }
                if (in_array("log", Input::post("admin_permission"))) {
                    $admin->admin_permission_log = 1;
                }
                if (in_array("special", Input::post("admin_permission"))) {
                    $admin->admin_permission_special = 1;
                }
                if (in_array("show", Input::post("admin_permission"))) {
                    $admin->admin_permission_show = 1;
                }
                if (in_array("edit", Input::post("admin_permission"))) {
                    $admin->admin_permission_edit = 1;
                }
                if (in_array("delete", Input::post("admin_permission"))) {
                    $admin->admin_permission_delete = 1;
                }
            }
            if ($admin->Validates() and !empty(Input::post("hash"))) {
				try {
                    // バリデーションが成功したら、データベースへ登録する。

                    // パスワードには、ハッシュ化されたパスワードを保存する。
                    $admin->login_password = Input::post("hash");

                    // データベースへINSERT
					DB::start_transaction();
					$admin->save();
					DB::commit_transaction();
					
                    // 登録が成功したら、フォームを再度表示する。
					Session::set_flash("session", array("validator_errors" => array("データベースへ保存しました")));
					return Response::redirect("system/admin/admin/new");

				} catch (Database_Exception $e) {
					// データベースへの登録が失敗したら、フォームに戻る。
					DB::rollback_transaction();
					Session::set_flash("session", array("validator_errors" => array("データベースへの保存が失敗しました。"), "posts" => Input::post()));
					return Response::redirect("system/admin/admin/new");
				}
			} else {
					// バリデーションが失敗したら、エラーメッセージを取得して、フォームへ戻る。
					$save_errors = array();
                    $errors = $admin->validation()->error();
                    
                    // ハッシュ化がされていない場合のエラーメッセージを追加。
                    if (empty(Input::post("hash"))) {
                        $save_errors[] = "パスワードをハッシュ化してください";
                    }

					foreach ($errors as $err) {
						array_push($save_errors, $err->get_message());
                    }
                    // フォームにもどる。
					Session::set_flash("session", array("validator_errors" => $save_errors, "posts" => Input::post()));
					return Response::redirect("system/admin/admin/new");
				}
		} else {
        }
    }
	}

    
    public function get_edit()
    {
        // 管理者編集

        // エラーメッセージや直前のポストの情報がセッションに入っていれば、それを取得する。
        $data["session"] = Session::get_flash(
            "session",
             array()
        );
        
        // 一番初めにエントリー一覧から編集ボタンを押して来た時の処理。entry_idから該当するModelを引っ張り出す。
        $data["admin"] = array();
        if(!empty(Input::get("admin_id"))) {
            $id = Input::get("admin_id");
            $data["admin"] = Model_Admin::find_one_by("admin_id", $id);
        }

        // ビューを返す。(Templateクラスを使用)
        $this->template->title = "管理者編集";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/admin/edit', $data);
    }

    public function post_edit()
	{
        // パスワードハッシュ化画面から来たときの処理
        if (!empty(Input::post("from_hash_password"))) {
            // ビューでセッションという変数名を使っているため、本当はセッションではないが、変数名を合わせるため、"session"にデータを格納する。
            $data["session"] = array(
                "posts" => Input::post(),
                "validator_errors" => array()
            );

            // 権限のデータをcheckboxを使うに当たって、使いやすいようなデータに変換する。
            $data["session"]["posts"]["admin_permission"] = array();
            $permissions = array("admin", "config", "master", "log", "special", "show", "edit", "delete");
            foreach ($permissions as $permission) {
                if (!empty(Input::post("admin_permission_".$permission))) {
                    if (Input::post("admin_permission_".$permission) == 1) {
                    $data["session"]["posts"]["admin_permission"][] = $permission; 
                    }
                }
            }
            // 管理者編集画面へ
            $this->template->title = "管理者編集";
            $this->template->header = View::forge('parts/header');
            $this->template->content = View::forge('system/admin/admin/edit', $data);
        } else {
		if(Security::check_token()){
            // ポストされた内容をモデルに登録する。
            $admin = Model_Admin::find_by_pk(Input::post("admin_id"));
			$admin->login_nickname = Input::post("login_nickname");
            $admin->admin_email = Input::post("admin_email");
            $admin->login_id = Input::post("login_id");
            $admin->login_password = Input::post("login_password");
            $admin->login_status = Input::post("login_status");


            // 権限の更新
            $admin->admin_permission_admin = 0;
            $admin->admin_permission_config = 0;
            $admin->admin_permission_master = 0;
            $admin->admin_permission_log = 0;
            $admin->admin_permission_special = 0;
            $admin->admin_permission_show = 0;
            $admin->admin_permission_edit = 0;
            $admin->admin_permission_delete = 0;
			if (!empty(Input::post("admin_permission"))) {
                if (in_array("admin", Input::post("admin_permission"))) {
                    $admin->admin_permission_admin = 1;
                }
                if (in_array("config", Input::post("admin_permission"))) {
                    $admin->admin_permission_config = 1;
                }
                if (in_array("master", Input::post("admin_permission"))) {
                    $admin->admin_permission_master = 1;
                }
                if (in_array("log", Input::post("admin_permission"))) {
                    $admin->admin_permission_log = 1;
                }
                if (in_array("special", Input::post("admin_permission"))) {
                    $admin->admin_permission_special = 1;
                }
                if (in_array("show", Input::post("admin_permission"))) {
                    $admin->admin_permission_show = 1;
                }
                if (in_array("edit", Input::post("admin_permission"))) {
                    $admin->admin_permission_edit = 1;
                }
                if (in_array("delete", Input::post("admin_permission"))) {
                    $admin->admin_permission_delete = 1;
                }
            }

            // バリデーションが成功したら、更新へ。
            if ($admin->Validates() and !empty(Input::post("hash"))) {
                try {
                    // データベースへ登録する。

                    // パスワードには、ハッシュ化されたパスワードを登録する。
                    $admin->login_password = Input::post("hash");
                    
                    // データをUPDATE
                    DB::start_transaction();
                    $admin->save();
                    DB::commit_transaction();
                    
                    // データベース更新が成功したら、フォームを表示する。
                    Session::set_flash("session", array("validator_errors" => array("データベースへ更新が成功しました。"), "posts" => Input::post()));
                    return Response::redirect("system/admin/admin/edit");

                } catch (Database_Exception $e) {
                    // データベースへ更新が失敗したら、フォームに戻る。
                    DB::rollback_transaction();
                    Session::set_flash("session", array("validator_errors" => array("データベースへ更新が失敗しました。"), "posts" => Input::post()));
                    return Response::redirect("system/admin/admin/edit");
                }
            } else {
                // バリデーションが失敗したら、エラーメッセージを取得して、フォームへ戻る。
                $save_errors = array();

                $errors = $admin->validation()->error();

                // ハッシュ化がされてない時の、エラーメッセージを追加する。
                if (empty(Input::post("hash"))) {
                    $save_errors[] = "パスワードをハッシュ化してください";
                }

                foreach ($errors as $err) {
                    array_push($save_errors, $err->get_message());
                }
                // フォームへ戻る。
                Session::set_flash("session", array("validator_errors" => $save_errors, "posts" => Input::post()));
                return Response::redirect("system/admin/admin/edit");
            }
        } else {
        }
    }
    }
    public function action_delete()
    {
        // 管理者削除
        $this->template->title = "管理者削除";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/admin/delete');
    }

    public function get_delete()
    {
        // エントリー削除
        
        // エラーメッセージがセッションに入っていれば、それを取得する。
        $data["session"] = Session::get_flash(
            "session",
             array()
        );

        if(!empty($data["session"])){
            // 削除が成功したら(セッションのsuccessの値がyesなら)、削除完了画面を表示する。
            if($data["session"]["success"] == "yes"){
                $this->template->title = "削除完了";
                $this->template->header = View::forge('parts/header');
                $this->template->content = View::forge('system/admin/admin/delete_done');
            } else {
                // 削除が失敗したら(セッションのsuccessの値がnoなら)、確認画面に戻り、エラーメッセージを表示する。
                $id = $data["session"]["id"];
                $data["entry"] = Model_Entry::find_one_by("entry_id", $id);
                $this->template->title = "管理者削除";
                $this->template->header = View::forge('parts/header');
                $this->template->content = View::forge('system/admin/admin/delete', $data);
            }
        }

        // エントリー一覧から削除ボタンを押して来た時は、該当するModelを引っ張り出して、確認画面を表示する。
        if(!empty(Input::get("admin_id"))) {
            $id = Input::get("admin_id");
            $data["admin"] = Model_Admin::find_one_by("admin_id", $id);
            $this->template->title = "管理者削除";
            $this->template->header = View::forge('parts/header');
            $this->template->content = View::forge('system/admin/admin/delete', $data);
        }
    }

    public function post_delete()
    {
        if(Security::check_token()){
            // ポストされたidのモデルを取り出す。
            $entry = Model_Admin::find_one_by("admin_id", Input::post("admin_id"));
            try {
                // データベースから削除する。

                DB::start_transaction();
                $entry->delete();
                DB::commit_transaction();
                
                
                // 削除が成功したら、セッションにsuccess=>yesを格納して、get_deleteにリダイレクトする。
                Session::set_flash("session", array("success" => "yes"));
                return Response::redirect("system/admin/admin/delete");

            } catch (Database_Exception $e) {
                // 削除が失敗したら、エラーメッセージとsuccess=>noをセッションに格納して、get_deleteにリダイレクトする。
                DB::rollback_transaction();
                Session::set_flash("session", array("success" => "no", "errors" => array("削除が失敗しました。"), "id" => Input::post("admin_id")));
                return Response::redirect("system/admin/admin/delete");
            }
        }
    }
}