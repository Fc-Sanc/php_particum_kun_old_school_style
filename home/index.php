<?php
    require_once '../db/dbUtils.php';
?>
<!--
    登录后的首页，并没有什么功能，仅作展示用
-->
<!doctype html>
<html lang = "zh">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport"
              content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv = "X-UA-Compatible" content = "ie=edge">
        <title>Home | One Library</title>

        <script src = "../asset/lib/jQuery/jquery-3.5.1.js"></script>

        <link rel = "stylesheet" href = "../asset/lib/bootstrap-3.3.7-dist/css/bootstrap.css">
        <script src = "../asset/lib/bootstrap-3.3.7-dist/js/bootstrap.js"></script>

        <link rel = "stylesheet" href = "../asset/style/page.css">
        <link rel = "stylesheet" href = "../asset/style/navigator.css">
        <link rel = "stylesheet" href = "../asset/style/userHomePage.css">

        <script>
            $(function () {
                $('a[href = "index.php"]').parent().addClass('active')
            })
        </script>
    </head>
    <body>
        <?php include 'component/navigator.php'; ?>
        <div class = "container main-body text-center">
            <p class = "text">细雨即将来临，大地的气息，
            <p class = "text">闪烁出声响，伴着雨燕翱翔；
            <p class = "text">池中的青蛙，将在夜晚鸣唱，
            <p class = "text">野柏树，瑟缩在白光中，
            <p class = "text">知更鸟披着轻盈的火，
            <p class = "text">在低篱上倾诉它的愿望；
            <p class = "text">当战争成为现实，
            <p class = "text">没有人知道，没有人忧伤。
            <p class = "text">如果人类悲哀地死去，
            <p class = "text">没有人在意，甚至鸟和树也是这样。
            <p class = "text">春天她自己，却在黎明苏醒，
            <p class = "text">她并不知道我们已灭亡。
            <p class = "text">——细雨即将来临, 菲利普乔塞法马</p>
        </div>
        <div class = "slogan">
            <h2>If you want to break the rule, then you have to know the rule first.</h2>
        </div>
    </body>
</html>
