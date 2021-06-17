<!--
    书籍列表，集成了显示全部书籍和按关键字查询，分页显示
-->
<?php
    require_once '../db/dbUtils.php';

    // 分页的准备

    // 每页要显示的书籍数量
    $size = 12;

    // 当前页
    $crtPage = $_GET['page'];

    // 提供给数据库，从数据库的第$start行开始查询$size条，显示在当前页面上
    $start = $size * ($crtPage - 1);

    // 根据是否按关键词查询，查询语句分为两种
    if (!isset($_GET['keyword'])) {
        // 没有关键词，查询所有数据，分页显示
        $param = '';

        // 查询总记录数
        if ($res = $manager->query('select count(*) as c from book')) {
            $bookCnt = $res->fetch_assoc()['c'];
        }

        // 查询书籍信息, 保存在$books数组中
        if ($stmt = $manager->prepare(
            'select ISBN, book.name as book_name, author, type.name as type_name, press
                    from book,type 
                    where type_id = id order by type_id limit ?, ?')) {
            $stmt->bind_param('ss', $start, $size);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $books = $res->fetch_all(MYSQLI_ASSOC);
            }
        }
    } else {
        // 有关键词，查询包含关键词的所有数据，分页显示
        $param = "&keyword={$_GET['keyword']}";
        $keyword = "%{$_GET['keyword']}%";

        // 查询符合条件的记录数
        if ($stmt = $manager->prepare(
            'select count(*) as c
                    from book,type 
                    where (
                        book.name like ? 
                        or ISBN like ?
                        or type.name like ?
                        or author like ?
                        )
                      and type_id = id order by type_id ')) {
            $stmt->bind_param('ssss', $keyword, $keyword, $keyword, $keyword);
            if ($stmt->execute()) {
                $bookCnt = $stmt->get_result()->fetch_assoc()['c'];
            }
            $stmt->close();
        }

        // 查询符合条件的书籍信息, 保存在$books数组中
        if ($stmt = $manager->prepare(
            'select ISBN, book.name as book_name, author, type.name as type_name
                    from book,type 
                    where (
                        book.name like ? 
                        or ISBN like ?
                        or type.name like ?
                        or author like ?
                        )
                      and type_id = id order by type_id limit ?, ?')) {
            $stmt->bind_param('ssssss', $keyword, $keyword, $keyword, $keyword, $start, $size);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $books = $res->fetch_all(MYSQLI_ASSOC);
            }
            $stmt->close();
        }
    }
    $pageNum = ceil($bookCnt / $size);
?>

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
        <link rel = "stylesheet" href = "../asset/style/table.css">

        <script>
            $(function () {
                $('a[href = "bookList.php"]').parent().addClass('active')
            })
        </script>
    </head>
    <body>

        <!--
            include 就算引入失败也会继续执行当前页面
            require 引入失败后就会终止运行
        -->
        <?php include 'component/navigator.php'; ?>
        <div class = "container">

            <table class = "table table-hover table-condensed"
                   style = "border-radius: 4px;background: rgba(255,255,255,0.75)">
                <thead>
                <tr>
                    <th>ISBN</th>
                    <th>书名</th>
                    <th>作者</th>
                    <th>类型</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>

                <?php if (isset($books) && count($books) > 0) { // 若查询出的书籍数不为0, 则分页显示
                    // 遍历books数组，输出每条的信息并提供详情入口
                    foreach ($books as $book) {
                        echo <<<BOOK
                <tr>
                    <td>${book['ISBN']}</td>
                    <td>${book['book_name']}</td>
                    <td>${book['author']}</td>
                    <td>${book['type_name']}</td>
                    <td>
                        <a href="details.php?ISBN=${book['ISBN']}"><button class="btn btn-success">详情</button></a>
                    </td>
                </tr>
BOOK;
                    }
                    ?>
                    <!--
                        分页部分，根据当前页数，判断每个按钮应执行的操作
                    -->
                    <tr>
                        <td colspan = "5" class = "text-center">
                            <a href = "<?= $crtPage == 1 ? '#' : 'bookList.php?page=1' . $param ?>">
                                <button class = "btn btn-info">首页</button>
                            </a>
                            <a href = "<?= $crtPage == 1 ? '#' : 'bookList.php?page=' . ($crtPage - 1) . $param ?>">
                                <button class = "btn btn-default">上一页</button>
                            </a>
                            <!-- 显示当前页和总页数 -->
                            <?= $crtPage ?> / <?= $pageNum ?>
                            <a href = "<?= $crtPage == $pageNum ? '#' : 'bookList.php?page=' . ($crtPage + 1) . $param ?>">
                                <button class = "btn btn-default">下一页</button>
                            </a>
                            <a href = "<?= $crtPage == $pageNum ? '#' : 'bookList.php?page=' . $pageNum . $param ?>">
                                <button class = "btn btn-warning">尾页</button>
                            </a>
                        </td>
                    </tr>
                <?php } else { // 为空则显示空集?>

                    <td colspan = "5" style = "text-align: center">Empty set.</td>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
