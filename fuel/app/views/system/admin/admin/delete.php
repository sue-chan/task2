<div>
   <h1>管理者削除</h1>

   <?php

   $dict = array(
      "0" => "無",
      "1" => "有"
   );

   if (!empty($session)) {
      foreach ($session["errors"] as $e) {
            echo "<p>".$e."</p>";
      }
   }
      echo '<p>ID:'.(string)$admin["admin_id"].'</p>';
      echo '<p>ユーザー名:'.$admin["login_nickname"].'</p>';
      echo '<p>メールアドレス:'.$admin["admin_email"].'</p>';
      echo '<p>ログインID:'.$admin["login_id"].'</p>';
      echo '<p>パスワード:'.$admin["login_password"].'</p>';
      echo '<p>自動ログインセッションID:'.$admin["login_passport"].'</p>';
      echo '<p>権限:</p>';
      echo '<p>管理者の管理:'.$dict[(string)$admin["admin_permission_admin"]].'</p>';
      echo '<p>設定値の管理:'.$dict[(string)$admin["admin_permission_config"]].'</p>';
      echo '<p>マスタの管理:'.$dict[(string)$admin["admin_permission_master"]].'</p>';
      echo '<p>ログの管理:'.$dict[(string)$admin["admin_permission_log"]].'</p>';
      echo '<p>特別機能の使用:'.$dict[(string)$admin["admin_permission_special"]].'</p>';
      echo '<p>エントリーの閲覧:'.$dict[(string)$admin["admin_permission_show"]].'</p>';
      echo '<p>エントリーの編集:'.$dict[(string)$admin["admin_permission_show"]].'</p>';
      echo '<p>エントリーの削除:'.$dict[(string)$admin["admin_permission_show"]].'</p>';
   ?>
   <br>
   <p>本当にこのエントリーを削除しますか?</p>
   <form method="post">
      <!-- csrf対策 -->
      <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
      <input type="hidden" name="admin_id" value="<?= $admin["admin_id"] ?>">
      <input type="submit" value="削除">
   </form>
</div>