<?php
    // 删除用户
    require_once '../db/dbUtils.php';

    if ($stmt = $manager->prepare(
        'delete from user where id = ?')) {
        $stmt->bind_param('s', $_SESSION['user_id']);
        $stmt->execute();

        // 影响条数大于0则删除成功
        if ($stmt->affected_rows > 0) {
            echo '<script>alert("账户已清除, 感谢您的陪伴TAT")</script>';
            // 重定向至登录页面
            header('refresh: 0;url=../index.php');
        } else {
            echo '<script>alert("账户删除失败, 烦请联系管理人员#_#")</script>';
            // 重定向至用户信息页面
            header('refresh: 0;url=profile.php');
        }
        $stmt->close();
        exit();
    }