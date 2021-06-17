<?php
    require_once 'db/dbUtils.php';

    // 若提交表单，则执行注册脚本
    if ($_POST && isset($_POST['username'], $_POST['password'])) {
        // 提取变量
        $username = $_POST['username'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        if (empty($_POST['address'])) {
            $address = null;
        } else {
            $address = $_POST['address'];
        }
        if (empty($_POST['tel_number'])) {
            $telNumber = null;
        } else {
            $telNumber = $_POST['tel_number'];
        }
        // 插入用户
        if ($stmt = $manager->prepare(
            'insert into user(username, password, gender, address, tel_number) 
                        values(?, ?, ?, ?, ?)')) {
            $stmt->bind_param('sssss', $username, $password, $gender, $address, $telNumber);
            $stmt->execute();
            // 插入行数 > 0则注册成功
            if ($stmt->affected_rows > 0) {
                echo '<script>alert("注册成功, 请登录")</script>';
                header('refresh: 0;url=index.php');
            } else {
                echo '<script>alert("注册失败，请检查信息是否不符合规格")</script>';
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
        <script>
            // 判断表单必填项是否全部填写，密码验证是否正确
            // $(function() { ... }) 文档就绪函数
            $(function () {
                $('#register').submit(function () {
                    let pwd = $('#password').val()
                    let cPwd = $('#confirming_password').val()
                    let gender = $('#gender').val()
                    let username = $('#username').val()
                    let result = true
                    let msg = ''
                    if (username === '' || gender === '' || pwd === '') {
                        msg += '请填写全部必填项\n'
                        result = false
                    }
                    if (pwd !== cPwd) {
                        msg += '请确认两次密码输入相同'
                        result = false
                    }
                    if (msg !== '') {
                        alert(msg)
                    }
                    return result
                })
            })
        </script>
    </head>
    <body>
        <div class = "HomePage-title">
            One Library<p class = "HomePage-subtitle">enjoy for a moment with books...</p>
        </div>
        <div class = "container HomePage-login-form">
            <!-- 注册表单 -->
            <form id = "register" method = "post" class = "pull-right">
                <div class = "modal-body">
                    <div class = "form-group">
                        <input id = "username" name = "username" type = "text"
                               class = "form-control text-input" placeholder = "用户名(必填)">
                    </div>
                    <div class = "form-group">
                        <select id = "gender" name = "gender" class = "form-control text-input">
                            <option value = "">性别(必填)</option>
                            <option>男</option>
                            <option>女</option>
                            <option>保密</option>
                        </select>
                    </div>
                    <div class = "form-group">
                        <input id = "password" name = "password" class = "form-control text-input"
                               type = "password" placeholder = "密码(必填)">
                    </div>
                    <div class = "form-group">
                        <input id = "confirming_password"
                               class = "form-control text-input" type = "password" placeholder = "确认密码(必填)">
                    </div>
                    <div class = "form-group">
                        <input id = "tel_number" name = "tel_number"
                               class = "form-control text-input" type = "text" placeholder = "电话号码(选填)">
                    </div>
                    <div class = "form-group">
                        <input id = "address" name = "address"
                               class = "form-control text-input" type = "text" placeholder = "居住地(选填)">
                    </div>
                </div>
                <div class = "modal-footer">
                    <a href = "index.php">
                        <button type = "button" class = "btn btn-default" data-dismiss = "modal">返回</button>
                    </a>
                    <input type = "submit" class = "btn btn-success" value = "注册">
                </div>
            </form>
        </div>

    </body>
</html>
