<?php
namespace Fuel\Migrations;

// テーブルccs_adminを作成。
class Make_ccs_admin
{

    function up()
    {
        \DBUtil::create_table(
            'ccs_admin',
            array(
            'admin_id' => array('type' => 'int', 'auto_increment' => true),
            'admin_email' => array('type' => 'char', 'constraint' => 255),
            'admin_permission_admin' => array('type' => 'bigint', 'default' => 0),
            'admin_permission_config' => array('type' => 'bigint', 'default' => 0),
            'admin_permission_master' => array('type' => 'bigint', 'default' => 0),
            'admin_permission_log' => array('type' => 'bigint', 'default' => 0),
            'admin_permission_special' => array('type' => 'bigint', 'default' => 0),
            'admin_permission_show' => array('type' => 'bigint', 'default' => 0),
            'admin_permission_edit' => array('type' => 'bigint', 'default' => 0),
            'admin_permission_delete' => array('type' => 'bigint', 'default' => 0),
            'login_nickname' => array('type' => 'char', 'constraint' => 40),
            'login_id' => array('type' => 'char', 'constraint' => 255),
            'login_password' => array('type' => 'char', 'constraint' => 40),
            'login_passport' => array('type' => 'char', 'constraint' => 40),
            'login_time' => array('type' => 'double', 'default' => 0),
            'login_status' => array('type' => 'smallint', 'default' => 0),
        ), array('admin_id'), true, false, 'utf8_unicode_ci');
    }

    function down()
    {
       \DBUtil::drop_table('ccs_entry');
    }
}