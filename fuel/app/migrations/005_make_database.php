<?php
namespace Fuel\Migrations;

// テーブルccs_adminを作成。
class Make_database
{

    function up()
    {
        \DBUtil::create_database("migration_database", 'utf8_unicode_ci');
    }

    function down()
    {
        \DBUtil::drop_database("migration_database");
    }
}