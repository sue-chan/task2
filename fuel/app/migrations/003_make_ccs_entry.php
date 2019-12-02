<?php
namespace Fuel\Migrations;

// テーブルccs_entryを作成。migrationファイルの001,002はテストのために使用しましたが、削除したため、003からになっています。
class Make_ccs_entry
{

    function up()
    {
        \DBUtil::create_table(
            'ccs_entry',
            array(
            'entry_id' => array('type' => 'int', 'auto_increment' => true),
            'entry_name' => array('type' => 'char', 'constraint' => 40),
            'entry_ruby' => array('type' => 'char', 'constraint' => 40),
            'entry_birthday' => array('type' => 'double', 'default' => 0),
            'entry_prefecture' => array('type' => 'smallint', 'default' => 0),
            'entry_address' => array('type' => 'char', 'constraint' => 255),
            'entry_telephone_h' => array('type' => 'char', 'constraint' => 5),
            'entry_telephone_m' => array('type' => 'char', 'constraint' => 4),
            'entry_telephone_l' => array('type' => 'char', 'constraint' => 4),
            'entry_email' => array('type' => 'char', 'constraint' => 255),
            'entry_magazine' => array('type' => 'smallint', 'default' => 0),
            'entry_magazine_type' => array('type' => 'smallint', 'default' => 0),
            'entry_transfer' => array('type' => 'smallint', 'default' => 0),
            'entry_register_time' => array('type' => 'double', 'default' => 0),
        ), array('entry_id'), true, false, 'utf8_unicode_ci');
    }

    function down()
    {
       \DBUtil::drop_table('ccs_entry');
    }
}