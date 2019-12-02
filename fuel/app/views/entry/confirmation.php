<html>
    <head>
        <title>確認</title>
    </head>
    <body>
        <h1>確認</h1>
        <p>名前:<?= $posts["entry_name"]; ?></p>
        <p>ふりがな:<?= $posts["entry_ruby"]; ?></p>
        <p>生年月日:<?php echo $posts["entry_birthday_y"]."/".$posts["entry_birthday_m"]."/".$posts["entry_birthday_d"]; ?></p>
        <p>都道府県:
        <?php
            $pref = array(
                '1'=>'北海道',
                '2'=>'青森県',
                '3'=>'岩手県',
                '4'=>'宮城県',
                '5'=>'秋田県',
                '6'=>'山形県',
                '7'=>'福島県',
                '8'=>'茨城県',
                '9'=>'栃木県',
                '10'=>'群馬県',
                '11'=>'埼玉県',
                '12'=>'千葉県',
                '13'=>'東京都',
                '14'=>'神奈川県',
                '15'=>'新潟県',
                '16'=>'富山県',
                '17'=>'石川県',
                '18'=>'福井県',
                '19'=>'山梨県',
                '20'=>'長野県',
                '21'=>'岐阜県',
                '22'=>'静岡県',
                '23'=>'愛知県',
                '24'=>'三重県',
                '25'=>'滋賀県',
                '26'=>'京都府',
                '27'=>'大阪府',
                '28'=>'兵庫県',
                '29'=>'奈良県',
                '30'=>'和歌山県',
                '31'=>'鳥取県',
                '32'=>'島根県',
                '33'=>'岡山県',
                '34'=>'広島県',
                '35'=>'山口県',
                '36'=>'徳島県',
                '37'=>'香川県',
                '38'=>'愛媛県',
                '39'=>'高知県',
                '40'=>'福岡県',
                '41'=>'佐賀県',
                '42'=>'長崎県',
                '43'=>'熊本県',
                '44'=>'大分県',
                '45'=>'宮崎県',
                '46'=>'鹿児島県',
                '47'=>'沖縄県'
            );
            $pref_number = $posts["entry_prefecture"];
            echo $pref["{$pref_number}"]
        ?>
        </p>
        <p>住所:<?= $posts["entry_address"]; ?></p>
        <p>電話番号:<?php echo $posts["entry_telephone_h"]."-".$posts["entry_telephone_m"]."-".$posts["entry_telephone_l"]; ?></p>
        <p>メールアドレス:<?= $posts["entry_email"]; ?></p>
        <p>メルマガ:
        <?php
            $magazine = array(
                '0'=>'不要',
                '1'=>'講読'
            );
            $magazine_number = $posts["entry_magazine"];
            echo $magazine["{$magazine_number}"]
        ?>
        </p>
        <p>メルマガのタイプ:
        <?php
            $magazine_type = array(
                '0'=>'PC向け',
                '1'=>'モバイル向け'
            );
            $magazine_type_number = $posts["entry_magazine_type"];
            echo $magazine_type["{$magazine_type_number}"]
        ?>
        </p>
        
        <form method="post">
            <!-- 受け取った$postsをそのままポストする方法が分からなかったので,一々取り出して名前を付けています。 -->
            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
            <input type='hidden' name="state" value="after_confirmation">
            <input type='hidden' name="entry_name" value=<?= $posts["entry_name"] ?>>
            <input type='hidden' name="entry_ruby" value=<?= $posts["entry_ruby"] ?>>
            <input type='hidden' name="entry_birthday_y" value=<?= $posts["entry_birthday_y"] ?>>
            <input type='hidden' name="entry_birthday_m" value=<?= $posts["entry_birthday_m"] ?>>
            <input type='hidden' name="entry_birthday_d" value=<?= $posts["entry_birthday_d"] ?>>
            <input type='hidden' name="entry_prefecture" value=<?= $posts["entry_prefecture"] ?>>
            <input type='hidden' name="entry_address" value=<?= $posts["entry_address"] ?>>
            <input type='hidden' name="entry_telephone_h" value=<?= $posts["entry_telephone_h"] ?>>
            <input type='hidden' name="entry_telephone_m" value=<?= $posts["entry_telephone_m"] ?>>
            <input type='hidden' name="entry_telephone_l" value=<?= $posts["entry_telephone_l"] ?>>
            <input type='hidden' name="entry_email" value=<?= $posts["entry_email"] ?>>
            <input type='hidden' name="entry_magazine" value=<?= $posts["entry_magazine"] ?>>
            <input type='hidden' name="entry_magazine_type" value=<?= $posts["entry_magazine_type"] ?>>
            <input type="submit" value="投稿">
        </form>
    </body>
</html>