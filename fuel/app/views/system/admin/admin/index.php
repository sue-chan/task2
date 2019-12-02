<div>
   <h1>管理者一覧</h1>
   <a href="new">管理者新規追加</a>
   <br><br>
   <?php
      $login_status = array(
         "0" => "無効",
         "1" => "有効"
      );

      if (!empty($admins)) {
      foreach($admins as $admin) {
            echo 'ID:'.$admin['admin_id']."<br>";
            echo 'ユーザー名:'.$admin['login_nickname']."<br>";
            echo 'アカウントの状態:'.$login_status[$admin['login_status']]."<br>";

            // 編集の権限があれば、編集ボタンを表示。(これだと、urlを直接入力されると編集画面に行けてしまうのですが、権限ごとにfunctionへのアクセスを制限する方法がわかりませんでした。)
            $current_admin = Session::get("current_admin", null);
            if ($current_admin["admin_permission_edit"] == 1) {
            echo '<form action="edit" method="get"><input type="hidden" name="admin_id" value="'.$admin['admin_id'].'"><input type="submit" value="編集"></form>';
            }
            // 削除の権限があれば、削除ボタンを表示
            if ($current_admin["admin_permission_delete"] == 1) {
            echo '<form action="delete" method="get"><input type="hidden" name="admin_id" value="'.$admin['admin_id'].'"><input type="submit" value="削除"></form>';;
            }
            echo '<br>';
         }
      }
   ?>
</div>