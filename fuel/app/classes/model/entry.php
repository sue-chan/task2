<?php 
class Model_Entry extends \Model_Crud{
    protected static $_table_name = 'ccs_entry';

    protected static $_primary_key = 'entry_id';

    protected static $_rules = array(
        'entry_name' => 'required|max_length[40]',
        'entry_ruby' => 'required|max_length[40]',
        'entry_address' => 'required|max_length[255]',
        'entry_telephone_h' => 'required|max_length[5]|valid_string[numeric]',
        'entry_telephone_m' => 'required|max_length[4]|valid_string[numeric]',
        'entry_telephone_l' => 'required|max_length[4]|valid_string[numeric]',
        'entry_email' => 'required|valid_email|max_length[255]',
    );

    protected static $_labels = array(
        'entry_name' => '名前',
        'entry_ruby' => 'ふりがな',
        'entry_address' => '住所',
        'entry_telephone_h' => '電話番号(上桁)',
        'entry_telephone_m' => '電話番号(中桁)',
        'entry_telephone_l' => '電話番号(下桁)',
        'entry_email' => 'メールアドレス',
    );
}