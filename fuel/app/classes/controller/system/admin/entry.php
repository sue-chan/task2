<?php

class Controller_System_Admin_entry extends Controller_System_Admin
{
    public function action_index()
    {
        // エントリー一覧

        // エントリーの検索
            // キーワード検索
        $entries = DB::select('entry_id','entry_name','entry_ruby')
                        ->from('ccs_entry')
                        ->where_open()
                        ->where('entry_name', 'like',  "%".Input::get("keyword")."%")
                        ->or_where('entry_ruby', 'like',  "%".Input::get("keyword")."%")
                        ->where_close();
            // 都道府県検索
        if (!empty(Input::get("entry_prefecture"))) {
            $entries = $entries->and_where_open();
            foreach(Input::get("entry_prefecture") as $entry_prefecture) {
                $entries = $entries->or_where("entry_prefecture", "=", (int)$entry_prefecture);
            }
            $entries = $entries->and_where_close();
        }
            // マガジン検索
        if (!empty(Input::get("entry_magazine"))) {
            $entries = $entries->and_where_open();
            foreach(Input::get("entry_magazine") as $entry_magazine) {
                $entries = $entries->or_where("entry_magazine", "=", (int)$entry_magazine);
            }
            $entries = $entries->and_where_close();
        }
            // マガジンタイプ検索
        if (!empty(Input::get("entry_magazine_type"))) {
            $entries = $entries->and_where_open();
            foreach(Input::get("entry_magazine_type") as $entry_magazine_type) {
                $entries = $entries->or_where("entry_magazine_type", "=", (int)$entry_magazine_type);
            }
            $entries = $entries->and_where_close();
        }
            // 生年月日(下限)で検索 
        if (!empty(Input::get("entry_birthday_min"))) {
            $entries = $entries->and_where_open()
                                ->where("entry_birthday", ">=", (int)Input::get("entry_birthday_min"))
                                ->and_where_close();
        }

            // 生年月日(上限)で検索 
         if (!empty(Input::get("entry_birthday_max"))) {
            $entries = $entries->and_where_open()
                                ->where("entry_birthday", "<=", (int)Input::get("entry_birthday_max"))
                                ->and_where_close();
        }

           // 登録日時(下限)で検索 
           if (!empty(Input::get("entry_register_time_min"))) {
            $entries = $entries->and_where_open()
                                ->where("entry_register_time", ">=", (int)Input::get("entry_register_time_min"))
                                ->and_where_close();
        }

            // 登録日時(上限)で検索 
        if (!empty(Input::get("entry_register_time_max"))) {
            $entries = $entries->and_where_open()
                                ->where("entry_register_time", "<=", (int)Input::get("entry_register_time_max"))
                                ->and_where_close();
        }

        // Paginationの設定
        $entries_copy = $entries->execute(); 
        $count = count($entries_copy);    
        $config = array(
            'total_items'    => $count,
            'per_page'       => 20,
            'uri_segment'    => 'page',
        );
        $pagination = Pagination::forge('mypagination', $config);
                
        $data["entries"] = $entries
                            ->limit($pagination->per_page)
                            ->offset($pagination->offset)
                            ->execute()
                            ->as_array();

        $data["message"] = "全部で".$count."件あります。";
        $data['pagination'] = $pagination;
        
        // ビューを返す。(Templateを使用)
        $this->template->title = "エントリー一覧";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/entry/index', $data);
    }


    public function get_edit()
    {
        // エントリー編集

        // エラーメッセージや直前のポストの情報がセッションに入っていれば、それを取得する。
        $data["session"] = Session::get_flash(
            "session",
             array()
        );
        
        // 一番初めにエントリー一覧から編集ボタンを押して来た時は、entry_idから該当するModelを引っ張り出す。
        $data["entry"] = array();
        if(!empty(Input::get("entry_id"))) {
            $id = Input::get("entry_id");
            $data["entry"] = Model_Entry::find_one_by("entry_id", $id);
        }

        // ビューを返す。(Templateクラスを使用)
        $this->template->title = "エントリー編集";
        $this->template->header = View::forge('parts/header');
        $this->template->content = View::forge('system/admin/entry/edit', $data);
    }

    public function post_edit()
	{
		if(Security::check_token()){
            // ポストされた内容をモデルに登録する。
            $entry = Model_Entry::find_one_by("entry_id", Input::post("entry_id"));
			$entry->entry_name = Input::post("entry_name");
			$entry->entry_ruby = Input::post("entry_ruby");
			$entry->entry_birthday = (int)(Input::post("entry_birthday_y").Input::post("entry_birthday_m").Input::post("entry_birthday_d"));
	        $entry->entry_prefecture = (int)Input::post("entry_prefecture");
			$entry->entry_address = Input::post("entry_address");
			$entry->entry_telephone_h = Input::post("entry_telephone_h");
			$entry->entry_telephone_m = Input::post("entry_telephone_m");
			$entry->entry_telephone_l = Input::post("entry_telephone_l");
            $entry->entry_email = Input::post("entry_email");
			$entry->entry_magazine = (int)Input::post("entry_magazine");
            $entry->entry_magazine_type = (int)Input::post("entry_magazine_type");
            $entry->entry_register_time = (int)(Input::post("entry_register_time_y").Input::post("entry_register_time_m").Input::post("entry_register_time_d").Input::post("entry_register_time_h").Input::post("entry_register_time_min").Input::post("entry_register_time_s"));
            $entry->entry_transfer = Input::post("entry_transfer");

            // バリデーションが成功したら、更新へ。
            if ($entry->Validates()) {
                try {
                    // データベースへ登録する。

                    DB::start_transaction();
                    $entry->save();
                    DB::commit_transaction();
                    
                    // データベース更新が成功したら、フォームを表示する。
                    Session::set_flash("session", array("validator_errors" => array("データベースへの保存が成功しました。"), "posts" => Input::post()));
                    return Response::redirect("system/admin/entry/edit");

                } catch (Database_Exception $e) {
                    // データベースへの登録が失敗したら、フォームに戻る。
                    DB::rollback_transaction();
                    Session::set_flash("session", array("validator_errors" => array("データベースへの保存が失敗しました。"), "posts" => Input::post()));
                    return Response::redirect("system/admin/entry/edit");
                }
            } else {
                // バリデーションが失敗したら、エラーメッセージを取得して、フォームへ戻る。
                $save_errors = array();

                $errors = $entry->validation()->error();

                foreach ($errors as $err) {
                    array_push($save_errors, $err->get_message());
                }
                Session::set_flash("session", array("validator_errors" => $save_errors, "posts" => Input::post()));
                return Response::redirect("system/admin/entry/edit");
            }
        } else {
        }
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
                $this->template->content = View::forge('system/admin/entry/delete_done');
            } else {
                // 削除が失敗したら(セッションのsuccessの値がnoなら)、確認画面に戻り、エラーメッセージを表示する。
                $id = $data["session"]["id"];
                $data["entry"] = Model_Entry::find_one_by("entry_id", $id);
                $this->template->title = "エントリー削除";
                $this->template->header = View::forge('parts/header');
                $this->template->content = View::forge('system/admin/entry/delete', $data);
            }
        }

        // エントリー一覧から削除ボタンを押して来た時は、該当するModelを引っ張り出して、確認画面を表示する。
        if(!empty(Input::get("entry_id"))) {
            $id = Input::get("entry_id");
            $data["entry"] = Model_Entry::find_one_by("entry_id", $id);
            $this->template->title = "エントリー削除";
            $this->template->header = View::forge('parts/header');
            $this->template->content = View::forge('system/admin/entry/delete', $data);
        }
    }

    public function post_delete()
    {
        if(Security::check_token()){
            // ポストされたidのモデルを取り出す。
            $entry = Model_Entry::find_one_by("entry_id", Input::post("entry_id"));
            try {
                // データベースから削除する。
                DB::start_transaction();
                $entry->delete();
                DB::commit_transaction();
                
                
                // 削除が成功したら、セッションにsuccess=>yesを格納して、get_deleteにリダイレクトする。
                Session::set_flash("session", array("success" => "yes"));
                return Response::redirect("system/admin/entry/delete");

            } catch (Database_Exception $e) {
                // 削除が失敗したら、エラーメッセージとsuccess=>noをセッションに格納して、get_deleteにリダイレクトする。
                DB::rollback_transaction();
                Session::set_flash("session", array("success" => "no", "errors" => array("削除が失敗しました。"), "id" => Input::post("entry_id")));
                return Response::redirect("system/admin/entry/delete");
            }
        }
    }
}
