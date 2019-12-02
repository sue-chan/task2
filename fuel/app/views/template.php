<!DOCTYPE html>
<html>
<head>
    <title>
        <?php echo $title; ?>
    </title>
</head>
<body>
    <header>
        <!-- header.phpファイルを読み込む-->
        <?php echo $header; ?>
    </header>
    <div id="content">
        <!-- 各アクションの内容を読み込む-->
        <?php echo $content; ?>
    </div>
</body>
</html>