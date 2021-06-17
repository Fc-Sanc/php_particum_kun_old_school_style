<!--
    书籍详细信息, 并提供借阅入口
-->
<?php
    require_once '../db/dbUtils.php';

    // 需要提供书籍ISBN为参数
    if (isset($_GET['ISBN'])) {
        $isbn = $_GET['ISBN'];
        // 根据ISBN查询书籍信息, 并根据type_id查询type的名称
        if ($stmt = $manager->prepare(
            'select book.*, type.name as type 
                        from book, type where ISBN = ? and type_id = id')) {
            $stmt->bind_param('s', $isbn);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $book = $res->fetch_assoc();

                // 将书籍简介以\n结尾的段落转换为html中以<p></p>包裹的段落
                $book['brief'] = str_replace('\n', '</p><p>', '<p>' . $book['brief'] . '</p>');
            }
            $stmt->close();
        }
        // 若借阅记录中出现了该书籍的ISBN, 则说明本书已出借,不能再次借阅
        if ($stmt = $manager->prepare('select count(*) as c from borrow_record where book_ISBN = ?')) {
            $stmt->bind_param('s', $_GET['ISBN']);
            if ($stmt->execute()) {
                // 用borrowed标记书否已借出
                if ($stmt->get_result()->fetch_assoc()['c'] > 0) {
                    $book['borrowed'] = true;
                } else {
                    $book['borrowed'] = false;
                }
            }
        }
    }
?>
<!doctype html>
<html lang = "zh">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport"
              content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv = "X-UA-Compatible" content = "ie=edge">
        <title><?= $book['name'] ?> | One Library</title>

        <script src = "../asset/lib/jQuery/jquery-3.5.1.js"></script>

        <link rel = "stylesheet" href = "../asset/lib/bootstrap-3.3.7-dist/css/bootstrap.css">
        <script src = "../asset/lib/bootstrap-3.3.7-dist/js/bootstrap.js"></script>

        <link rel = "stylesheet" href = "../asset/style/page.css">
        <link rel = "stylesheet" href = "../asset/style/navigator.css">
        <link rel = "stylesheet" href = "../asset/style/details.css">
        <script>
            $(function () {
                $('#btn-borrow').click(function () {
                    if (confirm('您确定要借阅<?= $book['name'] ?>一书吗？\n归还日期截至' +
                        '<?=date('Y-m-d', strtotime('+1 month'))?>')) {
                        location.href = 'borrow.php?ISBN=' +<?=$book['ISBN']?>
                    }
                })

                $('#btn-go-back').click(function () {
                    history.go(-1)
                })
            })
        </script>
    </head>
    <body>
        <?php include 'component/navigator.php'; ?>
        <div class = "container main-body">
            <h1 class = "title">
                <?= $book['name'] ?>
            </h1>
            <span class = "info">ISBN: <?= $book['ISBN'] ?></span><br>
            <span class = "info">作品类型: <?= $book['type'] ?></span> |
            <span class = "info">作者: <?= $book['author'] ?></span><br>
            <span class = "info">出版社: <?= $book['press'] ?></span> |
            <span class = "info">出版时间: <?= $book['publication_date'] ?></span>
            <div class = "btn-group pull-right">
                <!--
                    根据borrowed给出本书是否已借出, 若借出则不能再次借阅
                -->
                <?php if ($book['borrowed']) { ?>
                    <button class = "btn btn-default">已借出</button>
                <?php } else { ?>
                    <button id = "btn-borrow" class = "btn btn-success">借阅</button>
                <?php } ?>
                <button id = "btn-go-back" class = "btn btn-warning">返回</button>
            </div>
            <hr>
            <div>
                <h3 class = "title">作品简介</h3>
                <div class = "text">
                    <?= $book['brief'] ?>
                </div>
            </div>
        </div>

    </body>
</html>
