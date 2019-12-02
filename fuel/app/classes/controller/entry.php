<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Entry extends Controller
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function get_index()
	{
		// エラーメッセージや直前のポストがセッションに記録されていればそれを取得する。
		$data["session"] = Session::get_flash(
            "session",
             array()
		);
		return Response::forge(View::forge('entry/entry', $data));
	}

	public function post_index()
	{
		if(Security::check_token()){
			// ポストされた内容をモデルに登録する。
			$entry = Model_Entry::forge(
				array(
					"entry_name" => Input::post("entry_name"),
					"entry_ruby" => Input::post("entry_ruby"),
					"entry_birthday" => (int)(Input::post("entry_birthday_y").Input::post("entry_birthday_m").Input::post("entry_birthday_d")),
					"entry_prefecture" => (int)Input::post("entry_prefecture"),
					"entry_address" => Input::post("entry_address"),
					"entry_telephone_h" => Input::post("entry_telephone_h"),
					"entry_telephone_m" => Input::post("entry_telephone_m"),
					"entry_telephone_l" => Input::post("entry_telephone_l"),
					"entry_email" => Input::post("entry_email"),
					"entry_magazine" => (int)Input::post("entry_magazine"),
					"entry_magazine_type" => (int)Input::post("entry_magazine_type"),
				)
			);
			// 「投稿」が押された場合。
			if (Input::post("state") == "after_confirmation") {
				// 登録日時を現在の日時に設定する。
				$date = new DateTime();
				$entry->set(array(
					"entry_register_time" => (int)$date->format('Ymdhis'),	
				));
				try {
					// データベースへ登録する。
					DB::start_transaction();
					$entry->save();
					DB::commit_transaction();
					
					// メールを送信する。
					$email = Email::forge();
					$email->from('nemunemu_ya@yahoo.co.jp', '送信者');
					$email->to(array(
						'nemunemu.sue@gmail.com' => "管理者",
						Input::post("entry_email") => "ユーザー",
					));
					$email->subject('新規エントリーのデータベースへの登録が完了しました');
					$email->body(   
									"お名前:".Input::post("entry_name").
									"\nふりがな:".Input::post("entry_ruby").
									"\n誕生日:".Input::post("entry_birthday_y").Input::post("entry_birthday_m").Input::post("entry_birthday_d").
									"\n住所:".Input::post("entry_address").
									"\n電話番号:".Input::post("entry_telephone_h").Input::post("entry_telephone_m").Input::post("entry_telephone_l").
									"\nメールアドレス：".Input::post("entry_email")	
							);
					try
					{
						$email->send();
					}
					catch(\EmailValidationFailedException $e)
					{
						// バリデーションエラーが出たら、フォームへ戻る。
						Session::set_flash("session", array("validator_errors" => array("メールアドレスがバリデーションを通過しませんでした。"), "posts" => Input::post()));
						return Response::redirect("entry");
					}
					catch(\EmailSendingFailedException $e)
					{
						// ドライバのエラーが出たら、フォームに戻る。
						Session::set_flash("session", array("validator_errors" => array("ドライバがメールを送信できませんでした。"), "posts" => Input::post()));
						return Response::redirect("entry");
					}
					// メール送信が成功したら、完了画面を表示する。
					return Response::forge(View::forge('entry/done'));

				} catch (Database_Exception $e) {
					// データベースへの登録が失敗したら、フォームに戻る。
					DB::rollback_transaction();
					Session::set_flash("session", array("validator_errors" => array("データベースへの保存が失敗しました。"), "posts" => Input::post()));
					return Response::redirect("entry");
				}
			} else {
				// 「確認」が押された場合。
				if ($entry->Validates()) {
					// バリデーションが成功したら、確認画面へ。
					$data["posts"] = Input::post();
					return Response::forge(View::forge('entry/confirmation', $data));
				} else {
					// バリデーションが失敗したら、エラーメッセージを取得して、フォームへ戻る。
					$save_errors = array();

					$errors = $entry->validation()->error();

					foreach ($errors as $err) {
						array_push($save_errors, $err->get_message());
					}
					Session::set_flash("session", array("validator_errors" => $save_errors, "posts" => Input::post()));
					return Response::redirect("entry");
				}
			}
		} else {
		}
	}

	/**
	 * A; typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_hello()
	{
		return Response::forge(Presenter::forge('welcome/hello'));
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('welcome/404'), 404);
	}
}

