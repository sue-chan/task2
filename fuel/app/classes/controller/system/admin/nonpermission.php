<?php

class Controller_System_Admin_Nonpermission extends Controller
{
    public function action_index()
    {
        // 権限無し画面を表示(試行錯誤したのですが、案件定義書にないこのurlを追加することでしか実装が出来ませんでした。)
        return Response::forge(View::forge('system/admin/nonpermission'));
    }


}