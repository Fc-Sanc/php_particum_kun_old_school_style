<?php
    // 续借书籍, 逾期不可续借

    require_once '../db/dbUtils.php';

    $overdue_date = date('Y-m-d', strtotime('+1 month'));
    if (isset($_GET['ISBN'])) {
        // 查询逾期时间
        if ($stmt = $manager->prepare(
            'select overdue_date from borrow_record where book_ISBN = ? and user_id = ?')) {
            $stmt->bind_param('ss', $_GET['ISBN'], $_SESSION['user_id']);
            $stmt->execute();
            $overdueDate = $stmt->get_result()->fetch_assoc()['overdue_date'];
            $stmt->close();
        }

        // 若逾期则要求先归还图书，否则执行续借脚本
        if (strtotime($overdueDate) - strtotime('now') > 0) {
            if ($stmt = $manager->prepare(
                'update borrow_record set overdue_date = ? where book_ISBN = ? and user_id = ?')) {
                $stmt->bind_param('sss', $overdue_date, $_GET['ISBN'], $_SESSION['user_id']);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    echo '<script>alert("续借成功，归还时间调整为一个月以后")</script>';
                } else {
                    echo '<script>alert("续借失败，如果是今天刚刚借阅的书籍则不需要续借哦")</script>';
                }
                header('refresh: 0;url=borrowList.php');
                $stmt->close();
                exit();
            }
        } else {
            echo '<script>alert("逾期图书不允许续借，请先归还本书")</script>';
            header('refresh: 0;url=borrowList.php');
        }
    }
