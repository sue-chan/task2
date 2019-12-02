<div>
   <h1>ハッシュ化パスワード生成</h1>
   <form method="post"> 
      <input type="hidden" name="hash_make" value="yes">
      <?php
      // エラーメッセージがあれば表示
      if (!empty($post["message"])) {
         echo '<p>'.$post["message"].'</p>';
      }

      // ポストに入ったデータを全てhiddenで置いておく。
      if(!empty($post)) {
      foreach ($post as $key => $value) {
         if (is_array($value)) {
            foreach ($value as $_key => $_value) {
               echo '<input type="hidden" name="admin_permission_'.$_value.'" value="1">';
            }
         } else {
            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
         }
         }
      }
      ?>
      <?php
      if (empty($post["hash"])) {
         if (!empty($post["login_password"])) {
            echo '<input type="text" name="login_password" value="'.$post["login_password"].'">';
            echo '<input type="submit" value="生成">';
         } else {
            echo '<input type="text" name="login_password">';
            echo '<input type="submit" value="生成">';
         }
      } else {
         echo '<p>パスワード:'.$post["login_password"].'</p>';
      }
      ?>
   </form>
   <br>
   <?php
      echo "ハッシュ化したパスワード:";
      if(!empty($post["hash"])) {
         echo $post["hash"];
         // 新規作成から来た時は新規作成に戻り、編集から来た時は、編集に戻る。
         if (isset($post["from_new"])) {
            echo '<form action="../admin/new" method="post">';
         } else {
            echo '<form action="../admin/edit" method="post">';
         }
         // ハッシュ化画面から来たことを判別するためにfrom_hash_passwordというinputを置いておく
         echo '<input type="hidden" name="from_hash_password" value="yes">';

         // 全ての$postに入っていた情報をhiddenで置いておく
         if(!empty($post)) {
            foreach ($post as $key => $value) {
               if (is_array($value)) {
                  foreach ($value as $_key => $_value) {
                     echo '<input type="hidden" name="admin_permission_'.$_value.'" value="1">';
                  }
               } else {
               echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
               }
            }
            }
         echo '<input type="submit" value="フォームに戻る">';
         echo '</form>';
      }
   ?>
</div>