<div>
   <h1>エントリー削除</h1>

   <?php
      $pref = array(
         '0'=>'未選択',
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
   $magazine = array(
      "0"=>"不要",
      "1"=>"講読"
   );
   $magazine_type = array(
      "0"=>"PC向け",
      "1"=>"モバイル向け"
   );
   $transfer = array(
      "0"=>"未転送",
      "1"=>"転送済み"
   );

   if (!empty($session)) {
      foreach ($session["errors"] as $e) {
            echo "<p>".$e."</p>";
      }
   }

      echo '<p>ID:'.(string)$entry["entry_id"].'</p>';
      echo '<p>名前:'.$entry["entry_name"].'</p>';
      echo '<p>ふりがな:'.$entry["entry_ruby"].'</p>';
      echo '<p>生年月日:'.(string)$entry["entry_birthday"].'</p>';
      echo '<p>都道府県:'.$pref[(string)$entry["entry_prefecture"]].'</p>';
      echo '<p>住所:'.$entry["entry_address"].'</p>';
      echo '<p>電話番号:'.$entry["entry_telephone_h"]."-".$entry["entry_telephone_m"]."-".$entry["entry_telephone_l"].'</p>';
      echo '<p>メールアドレス:'.$entry["entry_email"].'</p>';
      echo '<p>メルマガ:'.$magazine[(string)$entry["entry_magazine"]].'</p>';
      echo '<p>メルマガのタイプ:'.$magazine_type[(string)$entry["entry_magazine_type"]].'</p>';
      echo '<p>転送済み:'.$transfer[(string)$entry["entry_transfer"]].'</p>';
      echo '<p>登録日時:'.(string)$entry["entry_register_time"].'</p>';
   ?>
   <br>
   <p>本当にこのエントリーを削除しますか?</p>
   <form method="post">
      <!-- csrf対策 -->
      <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
      <input type="hidden" name="entry_id" value="<?= $entry["entry_id"] ?>">
      <input type="submit" value="削除">
   </form>
</div>