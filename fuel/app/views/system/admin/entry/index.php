<div>
   <h1>エントリー一覧</h1>
   <a href="../../../entry">エントリー新規追加</a>
   <br><br>
   <h2>検索</h2>
   <form method="get">
      <p>キーワード:<input type="text" name="keyword" default=""></p>

      <!-- ドロップダウンリストにした方が使いやすいとは思いますが、今回はこのようにしました。ドロップダウンリストにする実装も出来ます。 -->
      <p>生年月日(下限)(八桁の数字で入力。例えば,1994年10月5日->19941005)：<input type="number" name="entry_birthday_min" default="19500101" min="19500101" max="20211231"></p>
      
      <p>生年月日(上限)(八桁の数字で入力。例えば,1994年10月5日->19941005)：<input type="number" name="entry_birthday_max" default="20211231" min="19500101" max="20211231"></p>
      
      <p>都道府県:<br>
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
               echo '<input type="checkbox" name="entry_prefecture[]" value="', $i, '">', $pref["${i}"];
            }
         ?>
         </select></p>

         <p>メルマガ:<br>
         <input type="checkbox" name="entry_magazine[]" value="0">不要
         <input type="checkbox" name="entry_magazine[]" value="1">講読

         <p>メルマガのタイプ:<br>
         <input type="checkbox" name="entry_magazine_type[]" value="0">PC向け
         <input type="checkbox" name="entry_magazine_type[]" value="1">モバイル向け

         <p>登録日時(下限)(16桁の数字で入力。例えば,1994年10月5日16時8分50秒->19941005160850)：<input type="number" name="entry_register_time_min" default="19500101000000" min="19500101000000" max="20211231000000"></p>

         <p>登録日時(上限)(16桁の数字で入力。例えば,1994年10月5日16時8分50秒->19941005160850)：<input type="number" name="entry_register_time_max" default="20211231000000" min="19500101000000" max="20211231000000"></p>
      
      
      <br>
      <input type="submit" value="検索">
      <br><br>
   </form>
   <?php echo "<h3>".$message."</h3>"; ?>
   <?php
      $pagination = Pagination::instance('mypagination');
      if (!empty($entries)) {
      foreach($entries as $entry) {
            echo 'ID:'.$entry['entry_id']."<br>";
            echo '名前:'.$entry['entry_name']."<br>";
            echo 'ふりがな:'.$entry['entry_ruby']."<br>";
            echo '<form action="edit" method="get"><input type="hidden" name="entry_id" value="'.$entry['entry_id'].'"><input type="submit" value="編集"></form>';
            echo '<form action="delete" method="get"><input type="hidden" name="entry_id" value="'.$entry['entry_id'].'"><input type="submit" value="削除"></form>';;
            echo '<br>';
         }
      }
      echo $pagination->render()
   ?>
</div>