<?php
    require_once '../db/dbUtils.php';

    // 本页面需要登录后才能访问
    if (isset($_SESSION['user_id'])) {

        // 获取用户信息
        if ($stmt = $manager->prepare('select * from user where id = ?')) {
            $stmt->bind_param('s', $_SESSION['user_id']);
            if ($stmt->execute()) {
                if ($res = $stmt->get_result())
                    $user = $res->fetch_assoc();
            }
        }

        // 当提交post表单时执行此处脚本
        if ($_POST) {
            // 更新地址, 电话, 性别
            $address = $_POST['address'] === '未填写' || empty($_POST['address'])
                ? null
                : $_POST['address'];
            $telNumber = $_POST['tel_number'] === '未填写' || empty($_POST['tel_number'])
                ? null
                : $_POST['tel_number'];
            $stmt = $manager->prepare('update user set gender = ?, address = ?, tel_number = ? where id = ?');
            $stmt->bind_param('ssss',
                $_POST['gender'], $address, $telNumber, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();

            // 当填写新密码时更新密码
            if (!empty($_POST['password'])) {
                $stmt = $manager->prepare('update user set password = ? where id = ?');
                $stmt->bind_param('ss', $_POST['password'], $_SESSION['user_id']);
                $stmt->execute();
                header('Location: ../index.php');
            }

            header("refresh: 0");
        }
    } else {
        header('Location: ../index.php');
    }

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
        <link rel = "stylesheet" href = "../asset/style/profile.css">

        <script>
            $(function () {
                $('a[href = "profile.php"]').parent().addClass('active')
                $('#update').submit(function () {
                    if ($('#tel_number').val().trim() === '' ||
                        $('#address').val().trim() === '') {
                        alert('请填写所有必填项(*)！')
                        return false
                    }
                    let pwdVal = $('#password').val()
                    if (pwdVal !== '') {
                        let res = pwdVal === $('#confirming_password').val()
                        if (!res) {
                            alert('若要更新密码，请确保两次密码填写一致！')
                            return res
                        }
                    }
                    return true
                })

                $('#btn_delete').click(function () {
                    if (confirm('您真的要彻底删除本用户吗？此操作不可回溯哦QAQ')) {
                        location.href = 'delete.php'
                    }
                })
            })
        </script>
    </head>
    <body>
        <?php include 'component/navigator.php'; ?>
        <?php if (isset($user)) { ?>
            <div class = "panel panel-warning user-profile-panel">
                <div class = "panel-heading">
                    <h3 class = "panel-title"><?= $user['username'] ?></h3>
                </div>
                <div class = "panel-body">
                    <label>性别</label>
                    <div class = "list-group-item">
                        <?= $user['gender'] ?>
                    </div>
                    <label>居住地</label>
                    <div class = "list-group-item">
                        <?= !empty($user['address']) ? $user['address'] : '未填写' ?>
                    </div>
                    <label>电话号码</label>
                    <div class = "list-group-item">
                        <?= !empty($user['tel_number']) ? $user['tel_number'] : '未填写' ?>
                    </div>
                    <label>用户组</label>
                    <div class = "list-group-item">
                        <?= $user['authority'] ?>
                    </div>
                </div>
                <div class = "panel-footer">
                    <div class = "btn-group">
                        <a href = "#">
                            <button class = "btn btn-success" data-toggle = "modal" data-target = "#updateModal">
                                更新信息
                            </button>
                        </a>
                        <a href = "#">
                            <button id = "btn_delete" class = "btn btn-danger">删除用户</button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 模态框做更新界面, 开始时隐藏, 点击更新才出现 -->
            <div class = "container">
                <div class = "modal fade" id = "updateModal" tabindex = "-1" role = "dialog"
                     aria-labelledby = "myModalLabel" aria-hidden = "true">
                    <div class = "modal-dialog">
                        <div class = "modal-content">
                            <div class = "modal-header bg-primary" style = "border-radius: 4px 4px 0 0">
                                <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">
                                    &times;
                                </button>
                                <h4 class = "modal-title" id = "myModalLabel">更新信息</h4>
                            </div>
                            <form id = "update" method = "post">
                                <div class = "modal-body">
                                    <div class = "form-group">
                                        <label for = "username">用户名</label>
                                        <input id = "username" type = "text"
                                               class = "form-control" contenteditable = "false" disabled
                                               value = "<?= $user['username'] ?>">
                                    </div>
                                    <div class = "form-group">
                                        <label for = "gender"><span style = "color: red">*</span>性别</label>
                                        <select id = "gender" name = "gender" class = "form-control">
                                            <option <?= $user['gender'] === '男' ? 'selected' : '' ?>>男</option>
                                            <option <?= $user['gender'] === '女' ? 'selected' : '' ?>>女</option>
                                            <option <?= $user['gender'] === '保密' ? 'selected' : '' ?>>保密</option>
                                        </select>
                                    </div>
                                    <div class = "form-group">
                                        <label for = "password">新密码</label>
                                        <input id = "password" name = "password" class = "form-control"
                                               type = "password">
                                    </div>
                                    <div class = "form-group">
                                        <label for = "confirming_password">确认新密码</label>
                                        <input id = "confirming_password"
                                               class = "form-control" type = "password">
                                    </div>
                                    <div class = "form-group">
                                        <label for = "tel_number"><span style = "color: red">*</span>电话号码</label>
                                        <input id = "tel_number" name = "tel_number"
                                               value = "<?= empty($user['tel_number']) ? '未填写' : $user['tel_number'] ?>"
                                               class = "form-control" type = "text">
                                    </div>
                                    <div class = "form-group">
                                        <label for = "address"><span style = "color: red">*</span>居住地</label>
                                        <input id = "address" name = "address"
                                               value = "<?= empty($user['address']) ? '未填写' : $user['address'] ?>"
                                               class = "form-control" type = "text">
                                    </div>
                                    <div class = "form-group">
                                        <label for = "authority">用户组</label>
                                        <select id = "authority" name = "authority" disabled class = "form-control">
                                            <option <?= $user['authority'] === 'user' ? 'selected' : '' ?>>
                                                user
                                            </option>
                                            <option <?= $user['authority'] === 'admin' ? 'selected' : '' ?>>
                                                admin
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class = "modal-footer">
                                    <button type = "button" class = "btn btn-default" data-dismiss = "modal">关闭</button>
                                    <input type = "submit" class = "btn btn-primary" value = "更新">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </body>
</html>
