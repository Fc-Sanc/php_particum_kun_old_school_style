<!--
    程序入口，用户登录接口，并提供注册入口和试用入口
-->
<?php
    require_once 'db/dbUtils.php';

    // 若session不存在，则执行登录脚本，否则执行注销脚本
    if (!isset($_SESSION['user_id'])) {
        if ($_POST && isset($_POST['username'], $_POST['password'])) {
            // 从表单获取用户名和密码
            $username = $_POST['username'];
            $password = $_POST['password'];
            // 查询用户是否存在，并提取数据
            $stmt = $manager->prepare(
                    'select id, authority, username from user where username = ? and password = ?'
            );
            $stmt->bind_param('ss', $username, $password);
            if ($stmt->execute()) {
                // 若res不为null，则用户存在
                if ($res = $stmt->get_result()->fetch_assoc()) {
                    // 向session中写入用户信息，供后续页面使用
                    $_SESSION['user_id'] = $res['id'];
                    $_SESSION['authority'] = $res['authority'];
                    $_SESSION['username'] = $res['username'];
                    // 页面重定向到用户主页
                    header('Location: home/index.php');

                } else {
                    echo '<script>alert("登录失败，请检查用户名与密码是否正确")</script>';
                }
            }
            $stmt->close();
        }
    } else {
        // 注销
        unset($_SESSION['user_id']);
        unset($_SESSION['authority']);
        unset($_SESSION['username']);
        header("refresh: 0");
    }
?>
<!doctype html>
<html lang = "zh">
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport"
              content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv = "X-UA-Compatible" content = "ie=edge">
        <title>One Library</title>

        <script src = "asset/lib/jQuery/jquery-3.5.1.js"></script>

        <link rel = "stylesheet" href = "asset/lib/bootstrap-3.3.7-dist/css/bootstrap.css">
        <script src = "asset/lib/bootstrap-3.3.7-dist/js/bootstrap.js"></script>

        <link rel = "stylesheet" href = "asset/style/page.css">
        <link rel = "stylesheet" href = "asset/style/homePage.css">
        <style>
            body {
                background: url("asset/img/background/background_001.jpg") no-repeat fixed center 0;
                background-size: cover;
            }
        </style>
    </head>
    <body>
        <div class = "HomePage-title">
            One Library<p class = "HomePage-subtitle">enjoy for a moment with books...</p>
        </div>
        <div class = "container HomePage-login-form">
            <!-- 登录表单 -->
            <form method = "post" class = "pull-right">
                <div class = "form-group" style = "margin: 10px auto">
                    <input class = "form-control text-input" id = "username" name = "username" type = "text"
                           autocomplete = "off" placeholder = "用户名">
                </div>
                <div class = "form-group" style = "margin: 10px auto">
                    <input class = "form-control text-input" id = "password" name = "password" type = "password"
                           placeholder = "密码">
                </div>
                <div class = "form-group pull-right" style = "margin: 10px">
                    <a href = "home">
                        <input type = "button" class = "btn btn-default" value = "先去逛逛">
                    </a>
                    <a href = "register.php">
                        <input type = "button" class = "btn btn-info" value = "注册">
                    </a>
                    <input type = "submit" class = "btn btn-primary" value = "登录">
                </div>
            </form>
        </div>

    </body>
</html>
