<div>
<h1>フォーム</h1>
    <?php
    // セッションにエラーメッセージや直前のポストの情報があれば取得する。
    if (!empty($session)) {
        foreach ($session["validator_errors"] as $e) {
            echo "<p>".$e."</p>";
        }
        if (!empty($session["posts"])) {
            $posts = $session["posts"];
        }
    }
    ?>
    <form method = "post">
        <!-- csrf対策 -->
        <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

        <!-- ハッシュ化されたパスワード -->
        <input type="hidden" name="hash" value="<?php if (!empty($posts["hash"])) {echo $posts["hash"];} ?>">
        
        <!-- もし直前のポストの情報があれば、defaultの値として表示 -->
        <label>ログイン名：<input type="text" name="login_nickname" value="<?php if (!empty($posts["login_nickname"])) {echo $posts["login_nickname"];} ?>"></label>
        <br><br>
        <label>メールアドレス：<input type="text" name="admin_email" value="<?php if (!empty($posts["admin_email"])) {echo $posts["admin_email"];} ?>"></label>
        <br><br>
        <label>ログインID(半角英数字)：<input type="text" name="login_id" value="<?php if (!empty($posts["login_id"])) {echo $posts["login_id"];} ?>"></label>
        <br><br>
        <?php
        // すでにハッシュ化されていたら、パスワードを変更させないようにする。
        if (!empty($posts["hash"])) {
            if ((!empty($posts["login_password"]))) {
            echo '<p>パスワード:'.$posts["login_password"].'</p>';
            echo '<p>ハッシュ化されたパスワード:'.$posts["hash"].'</p>';
            echo '<input type="hidden" name="login_password" value="'.$posts["login_password"].'">';
            } 
        } else {
            if ((!empty($posts["login_password"]))) {
                echo '<label>パスワード(半角文字)：<input type="text" name="login_password" pattern="[A-Za-z]{6,}" value="'.$posts["login_password"].'"></label>';
            } else {
                echo '<label>パスワード(半角文字)：<input type="text" name="login_password" pattern="[A-Za-z]{6,}"></label>';
            }
            echo '<p>（※パスワードは6文字以上の半角文字で、設定したら「作成」を押す前に「パスワードをハッシュ化する」を押してください。）<br>（※もしランダムなパスワードを生成したい場合は、パスワード欄を空欄にしたまま「パスワードをハッシュ化する」を押してください。）</p>';
        }
        ?>

        <p>権限<br>
        <?php
        $label = '<label>管理者の管理：<input type="checkbox" name="admin_permission[]" value="admin"';
        if ((!empty($posts))) {
            if (!empty($posts["admin_permission"])) {
                if (in_array("admin", $posts["admin_permission"])) {
                    $label .= ' checked';
                }
            }
        }
        $label .= '></label>';
        echo $label;
        echo '<br>';

        $label = '<label>設定値の管理：<input type="checkbox" name="admin_permission[]" value="config"';
        if ((!empty($posts))) {
            if (!empty($posts["admin_permission"])) {
                if (in_array("config", $posts["admin_permission"])) {
                    $label .= ' checked';
                }
            }
        }
        $label .= '></label>';
        echo $label;
        echo '<br>';

        $label = '<label>マスタの管理：<input type="checkbox" name="admin_permission[]" value="master"';
        if ((!empty($posts))) {
            if (!empty($posts["admin_permission"])) {
                if (in_array("master", $posts["admin_permission"])) {
                    $label .= ' checked';
                }
            }
        }
        $label .= '></label>';
        echo $label;
        echo '<br>';

        $label = '<label>ログの管理：<input type="checkbox" name="admin_permission[]" value="log"';
        if ((!empty($posts))) {
            if (!empty($posts["admin_permission"])) {
                if (in_array("log", $posts["admin_permission"])) {
                    $label .= ' checked';
                }
            }
        }
        $label .= '></label>';
        echo $label;
        echo '<br>';

        $label = '<label>特別機能の使用：<input type="checkbox" name="admin_permission[]" value="special"';
        if ((!empty($posts))) {
            if (!empty($posts["admin_permission"])) {
                if (in_array("special", $posts["admin_permission"])) {
                    $label .= ' checked';
                }
            }
        }
        $label .= '></label>';
        echo $label;
        echo '<br>';

        $label = '<label>エントリーの閲覧：<input type="checkbox" name="admin_permission[]" value="show"';
        if ((!empty($posts))) {
            if (!empty($posts["admin_permission"])) {
                if (in_array("show", $posts["admin_permission"])) {
                    $label .= ' checked';
                }
            }
        }
        $label .= '></label>';
        echo $label;
        echo '<br>';

        $label = '<label>エントリーの編集：<input type="checkbox" name="admin_permission[]" value="edit"';
        if ((!empty($posts))) {
            if (!empty($posts["admin_permission"])) {
                if (in_array("edit", $posts["admin_permission"])) {
                    $label .= ' checked';
                }
            }
        }
        $label .= '></label>';
        echo $label;
        echo '<br>';

        $label = '<label>エントリーの削除：<input type="checkbox" name="admin_permission[]" value="delete"';
        if ((!empty($posts))) {
            if (!empty($posts["admin_permission"])) {
                if (in_array("delete", $posts["admin_permission"])) {
                    $label .= ' checked';
                }
            }
        }
        $label .= '></label>';
        echo $label;
        echo '<br>';
        ?>     
         <p>ログインステータス:<br>
            <select name="login_status">
            <?php
            if (!empty($posts["login_status"])) {
            if ($posts["login_status"]=="0") {
	            echo '<option value="0" selected>無効</option>';
                echo '<option value="1">有効</option>';
            } elseif ($posts["login_status"]=="1") {
                echo '<option value="0">無効</option>';
                echo '<option value="1" selected>有効</option>';
            }
            } else {
                echo '<option value="0">無効</option>';
                echo '<option value="1">有効</option>';
            }
            ?>
            </select></p>
            <br>
        <input type="submit" value="作成">
        <?php
        // ハッシュ化画面へのボタン。
        if (empty($posts["hash"])) {
            echo '<input type="submit" value="パスワードをハッシュ化する" formaction="../password/" name="from_new">';
        }
        ?>
    </form>
</div>