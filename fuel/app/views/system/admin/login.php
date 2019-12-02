<html>
  <head>
    <title>ログイン</title>
  </head>
  <body>
    <h1>ログイン</h1>
    <?php
    if(!empty($error_message)) {
        echo $error_message;
    }
    ?>
    <form method="post">
      <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
      <label>ログインID:<input type="text" name="login_id"></label><br>
      <label>ログインパスワード:<input type="text" name="login_password"></label><br>
      <input type="submit" value="ログイン">
    </form>
  </body>
</html>