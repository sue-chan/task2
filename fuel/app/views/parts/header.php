<div>
    <?php 
    echo "ログイン中のユーザー<br>";
    $admins = Model_Admin::find_by("login_status", 1);
    if(!empty($admins)) {
        foreach ($admins as $admin) {
            echo $admin->login_nickname;
            echo'<br>';
        }
    }
    // セッションの内容を全て表示する（バグ発見用の処理）
    // $current_admin = Session::get("current_admin", null);
    // foreach ($current_admin as $key => $value) {
    //     echo $key.':'.$value.'<br>';
    // }
    ?>
    <br>
    <?php 
    // base_urlを環境に合わせて変えてください。
    // 相対パスを使いたかったのですが、templateのheaderなので自分が今いるページがいくつかパターンがあるため、このようにしました。
    $base_url = "http://localhost/test/public/";
    echo '<a href="'.$base_url.'system/admin/">トップ</a><br>';
    echo '<a href="'.$base_url.'system/admin/entry/">エントリー一覧</a><br>';
    echo '<a href="'.$base_url.'system/admin/admin/">管理者一覧</a><br>';
    echo '<a href="'.$base_url.'system/admin/password/">パスワード生成</a><br>';
    echo '<a href="'.$base_url.'system/admin/logout/">ログアウト</a><br>';
    ?>
    
</div>