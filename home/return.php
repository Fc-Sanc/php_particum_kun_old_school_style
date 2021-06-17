<?php
    // 归还图书，逾期扣钱！扣钱！

    require_once '../db/dbUtils.php';

    if (isset($_GET['ISBN'])) {
        // 查询逾期时间
        if ($stmt = $manager->prepare(
            'select overdue_date from borrow_record where book_ISBN = ? and user_id = ?')) {
            $stmt->bind_param('ss', $_GET['ISBN'], $_SESSION['user_id']);
            $stmt->execute();
            $overdueDate = $stmt->get_result()->fetch_assoc()['overdue_date'];
            $stmt->close();
        }

        if ($stmt = $manager->prepare(
            'delete from borrow_record where book_ISBN = ? and user_id = ?')) {
            $stmt->bind_param('ss', $_GET['ISBN'], $_SESSION['user_id']);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                // 若逾期则执行惩罚脚本，否则直接归还
                if (strtotime($overdueDate) - strtotime('now') > 0) {
                    echo '<script>alert("归还成功")</script>';
                } else {
                    // 扣钱!
                    echo '<script>alert("归还成功，逾期罚金账单已从您的学生卡中扣除")</script>';
                }
            } else {
                echo '<script>alert("归还失败")</script>';
            }
            header('refresh: 0;url=borrowList.php');
            $stmt->close();
            exit();
        }
    }
