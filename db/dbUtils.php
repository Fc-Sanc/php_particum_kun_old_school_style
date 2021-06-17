<?php
    // 生产环境应设置不显示错误，调试环境应设定为On
    ini_set('display_errors', 'Off');

    /**
     * 修改本页面的常量以配置不同的环境
     */

    // 定义常量
    // 目的: 便于在不同的环境间适配程序
    // 数据库ip
    define('DB_HOST', 'localhost');
    // 数据库名
    define('DB_NAME', 'library');
    // 数据库用户名
    define('DB_USER', 'root');
    // 数据库密码
    define('DB_PWD', 'root');

    /**
     * 与数据库交互的对象，本实验中使用mysqli, 使用的函数有:<br>
     * query()<br>
     * prepare()<br>
     * 衍生出的mysqli_stmt类使用的函数有:<br>
     * bind_param()<br>
     * execute()<br>
     * get_result()<br>
     * 衍生出的mysqli_result类使用的函数有:<br>
     * fetch()<br>
     * fetch_all()<br>
     * @var mysqli
     */
    $manager = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);

    if ($manager->connect_errno) {
        exit();
    }

    session_start(); // 启动会话