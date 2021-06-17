<!--
    借阅书籍，返回借阅结果
-->
<?php
    require_once '../db/dbUtils.php';

    // 该页面需要登录后才能执行
    if ($_SESSION['user_id']) {

        // 需要页面提供书籍的ISBN号
        if (isset($_GET['ISBN'])) {

            // 根据当前时间，延后一个月，计算归还时间
            // 2020-06-09
            $overdue_date = date('Y-m-d', strtotime('+1 month'));

            // 插入借阅记录，返回结果
            if ($stmt = $manager->prepare(
                'insert into borrow_record(user_id, book_ISBN, overdue_date) values (?, ?, ?)')) {
                $stmt->bind_param('sss', $_SESSION['user_id'], $_GET['ISBN'],
                    $overdue_date);
                $stmt->execute();
                // 影响行数 > 0, 则插入成功
                if ($stmt->affected_rows > 0) {
                    $ret = '借阅成功，请稍后...';
                } else {
                    $ret = '借阅失败，请稍后重试或联系管理员:(';
                }
                header('refresh: 2;url=borrowList.php');
                $stmt->close();
            }
        }
    } else {
        $ret = '请登录后再进行借阅';
        header('refresh: 2;url=../index.php');
    }
?>
<!doctype html>
<html lang = "zh">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport"
              content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv = "X-UA-Compatible" content = "ie=edge">
        <title>Borrow Book | One Library</title>

        <script src = "../asset/lib/jQuery/jquery-3.5.1.js"></script>

        <link rel = "stylesheet" href = "../asset/lib/bootstrap-3.3.7-dist/css/bootstrap.css">
        <script src = "../asset/lib/bootstrap-3.3.7-dist/js/bootstrap.js"></script>

        <link rel = "stylesheet" href = "../asset/style/page.css">
        <link rel = "stylesheet" href = "../asset/style/navigator.css">
    </head>
    <body>
        <?php include 'component/navigator.php' ?>
        <!-- 输出返回信息 -->
        <h1 style = "color: white"><?= $ret ?></h1>
    </body>
</html>
