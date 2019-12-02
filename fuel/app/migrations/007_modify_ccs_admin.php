<?php
namespace Fuel\Migrations;

class Modify_ccs_admin
{

    function up()
    {
        \DBUtil::modify_fields('ccs_admin',array(
            'login_password' => array('type' => 'char', 'constraint' => 255),
        ));
        
    }

    function down()
    {
       \DBUtil::drop_table('ccs_entry');
    }
}