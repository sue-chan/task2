<html>
    <head>
        <title>フォーム</title>
    </head>
    <body>
        <h1>フォーム</h1>
        <?php
        // セッションにエラーメッセージや直前のポストの情報があれば取得する。
        if (!empty($session)) {
            foreach ($session["validator_errors"] as $e) {
                echo "<p>".$e."</p>";
            }
            $posts = $session["posts"];
        }
        ?>
        <form method = "post">
            <!-- csrf対策 -->
            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

            <input type='hidden' name="state" value="before_confirmation">

            <!-- もし直前のポストの情報があれば、defaultの値として表示 -->
            <label>名前：<input type="text" name="entry_name" value="<?php if (!empty($session)) {echo $posts["entry_name"];} ?>"></label>
            <br>
            <label>ふりがな：<input type="text" name="entry_ruby" value="<?php if (!empty($posts)) {echo $posts["entry_ruby"];} ?>"></label>
      
            <p>生年月日(年):<br>
            <select name="entry_birthday_y">
            <?php
            for($year = 1950; $year <= 2019; $year++) {
                // もし直前のポストの情報があれば、選んでいたoptionをselectedにする。
                if (!empty($session) and $year==$posts["entry_birthday_y"]) {
                    echo '<option value="', $year, '" selected>', $year, '</option>';
                } else {
                    echo '<option value="', $year, '">', $year, '</option>';
                }
            }
            ?>
            </select></p>
            
            <p>生年月日(月):<br>
            <select name="entry_birthday_m">
            <?php
            for($month = 1; $month <= 12; $month++) {
                if (!empty($session) and $month==$posts["entry_birthday_m"]) {
                    echo '<option value="', str_pad($month, 2, 0, STR_PAD_LEFT), '" selected>', $month, '</option>';
                } else {
                echo '<option value="', str_pad($month, 2, 0, STR_PAD_LEFT), '">', $month, '</option>';
                }
            }
            ?>
            </select></p>

            <p>生年月日(日):<br>
            <select name="entry_birthday_d">
            <?php
            for($day = 1; $day <= 31; $day++) {
                if (!empty($session) and $day==$posts["entry_birthday_d"]) {
                    echo '<option value="', str_pad($day, 2, 0, STR_PAD_LEFT), '" selected>', $day, '</option>';
                } else {
                echo '<option value="', str_pad($day, 2, 0, STR_PAD_LEFT), '">', $day, '</option>';
                }
            }
            ?>
            </select></p>
            
            <p>都道府県:<br>
            <select name="entry_prefecture">
            <option value="0"></option>
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
            for($i = 1; $i <= 47; $i++) {
                if (!empty($session) and $i==$posts["entry_prefecture"]) {
                    echo '<option value="', $i, '" selected>', $pref["${i}"], '</option>';
                } else {
                echo '<option value="', $i, '">', $pref["${i}"], '</option>';
                }
            }            
            ?>
            </select></p>

            <label>住所：<input type="text" name="entry_address" value="<?php if (!empty($session)) {echo $posts["entry_address"];} ?>"></label>
            <br>
            <label>電話番号(上桁)：<input type="text" name="entry_telephone_h" value="<?php if (!empty($session)) {echo $posts["entry_telephone_h"];} ?>"></label>
            <br>
            <label>電話番号(中桁)：<input type="text" name="entry_telephone_m" value="<?php if (!empty($session)) {echo $posts["entry_telephone_m"];} ?>"></label>
            <br>
            <label>電話番号(下桁)：<input type="text" name="entry_telephone_l" value="<?php if (!empty($session)) {echo $posts["entry_telephone_l"];} ?>"></label>
            <br>
            <label>メールアドレス：<input type="text" name="entry_email" value="<?php if (!empty($session)) {echo $posts["entry_email"];} ?>"></label>
            
            <p>メルマガ:<br>
            <select name="entry_magazine">
            <?php
            if (!empty($session) and $posts["entry_magazine"]=="0") {
	            echo '<option value="0" selected>不要</option>';
                echo '<option value="1">講読</option>';
            } elseif (!empty($session) and $posts["entry_magazine"]=="1") {
                echo '<option value="0">不要</option>';
                echo '<option value="1" selected>講読</option>';
            } else {
                echo '<option value="0">不要</option>';
                echo '<option value="1">講読</option>';
            }
            ?>
            </select></p>

            <p>メルマガのタイプ:<br>
            <select name="entry_magazine_type">
            <?php
            if (!empty($session) and $posts["entry_magazine_type"]=="0") {
	            echo '<option value="0" selected>PC向け</option>';
                echo '<option value="1">モバイル向け</option>';
            } elseif (!empty($session) and $posts["entry_magazine_type"]=="1") {
                echo '<option value="0">PC向け</option>';
                echo '<option value="1" selected>モバイル向け</option>';
            } else {
                echo '<option value="0">PC向け</option>';
                echo '<option value="1">モバイル向け</option>';
            }
            ?>
            </select></p>

            <input type="submit" value="確認">

        </form>
    </body>
</html>