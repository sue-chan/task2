<?php 
class Model_Admin extends \Model_Crud{
    protected static $_table_name = 'ccs_admin';

    protected static $_primary_key = 'admin_id';

    protected static $_rules = array(
        'login_nickname' => 'required|max_length[40]',
        'login_id' => 'required|min_length[6]|max_length[40]|valid_string[numeric,alpha,dashes]',
        'login_password' => 'required|min_length[6]|max_length[255]',
        'admin_email' => 'required|valid_email'
    );

    protected static $_labels = array(
        'login_nickname' => 'ログイン名',
        'login_id' => 'ログインID',
        'login_password' => 'パスワード',
        'admin_email' => 'メールアドレス'
    );
}