<?php

return array(
    "required" => ":labelを入力してください",
    "min_length" => ":labelの値は:param:1以上で指定してください",
    "max_length" => ":labelの値は:param:1以内で指定してください",
    "valid_email" => "正しいメールアドレスを入力してください",
    "valid_string" => ":labelには:param:1を入力してください",
    // valid_stringのエラーメッセージのnumericを日本語にするのが出来ませんでした。
    // modelのvalidationでvalid_string[numeric,数字]としてエラーメッセージで:param:2を指定しても「数字」が取り出せませんでした。  
);