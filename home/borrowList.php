<!--
    读取当前用户的借阅记录，并提供续借和返还入口
-->
<?php
    require_once '../db/dbUtils.php';

    // 根据session信息，读取当前用户的借阅记录，保存在records数组里
    if ($stmt = $manager->prepare(
        'select name, overdue_date, ISBN
                    from user, borrow_record, book 
                    where user_id = ? and ISBN = book_ISBN and user_id = user.id')) {
        $stmt->bind_param('s', $_SESSION['user_id']);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            $records = $res->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
    }
?>
<!doctype html>
<html lang = "zh">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport"
              content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv = "X-UA-Compatible" content = "ie=edge">
        <title>Borrow records | One Library</title>

        <script src = "../asset/lib/jQuery/jquery-3.5.1.js"></script>

        <link rel = "stylesheet" href = "../asset/lib/bootstrap-3.3.7-dist/css/bootstrap.css">
        <script src = "../asset/lib/bootstrap-3.3.7-dist/js/bootstrap.js"></script>

        <link rel = "stylesheet" href = "../asset/style/page.css">
        <link rel = "stylesheet" href = "../asset/style/navigator.css">
        <link rel = "stylesheet" href = "../asset/style/table.css">

        <script>
            $(function () {
                $('a[href = "borrowList.php"]').parent().addClass('active')
            })
        </script>
    </head>
    <body>
        <?php include 'component/navigator.php' ?>
        <div class = "container">

            <table class = "table table-hover table-condensed" style = "background: rgba(255,255,255,0.75)">
                <thead>
                <tr>
                    <th>ISBN</th>
                    <th>书名</th>
                    <th>逾期时间</th>
                    <th>剩余天数</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($records) && count($records) > 0) { // 若有，则输出用户借阅记录
                    foreach ($records as $record) {
                        // 逾期时间和现在时间比较，若小于0则已逾期
                        $remainingDays = round((strtotime($record['overdue_date']) - strtotime('now')) / 3600 / 24);
                        if ($remainingDays < 0) {
                            $remainingDays = '已逾期';
                        } else {
                            $remainingDays .= '天';
                        }
                        echo <<<RECORD
                <tr>
                    <td>${record['ISBN']}</td>
                    <td>${record['name']}</td>
                    <td>${record['overdue_date']}</td>
                    <td>${remainingDays}</td>
                    <td>
                        <a href="return.php?ISBN=${record['ISBN']}"><button name="return" class="btn btn-success">归还</button></a>
                        <a href="renew.php?ISBN=${record['ISBN']}"><button name="return" class="btn btn-warning">续借</button></a>
                    </td>
                </tr>
RECORD;
                    }
                } else { // 没有记录，输出空集?>
                    <td colspan = "5" style = "text-align: center">Empty set.</td>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
