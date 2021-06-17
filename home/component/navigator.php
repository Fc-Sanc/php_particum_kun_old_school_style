<!--
    导航栏，显示在用户页面的最上方
-->
<nav class = "navbar navbar-default navbar-static-top" style = "background: rgba(255,255,255,0.7);">
    <div class = "container-fluid">
        <div class = "navbar-header nav-title">
            <a class = "navbar-brand nav-title">
                One Library
            </a>
        </div>

        <div>
            <ul class = "nav navbar-nav">
                <li><a href = "index.php">首页</a></li>
                <li><a href = "bookList.php?page=1">书单</a></li>
            </ul>
        </div>

        <div>
            <ul class = "nav navbar-nav">
                <li>
                    <div class = "form-inline" style = "margin: 5px auto">
                        <form action = "bookList.php" method = "get">
                            <input type = "hidden" name = "page" value = "1">
                            <input id = "keyword" type = "text" name = "keyword" style = "width: 300px"
                                   placeholder = "任意关键字，支持模糊搜索" class = "form-control">
                            <input type = "submit" id = "search" class = "btn btn-info" value = "搜索">
                        </form>
                    </div>
                    <script>
                    </script>
                </li>
            </ul>
        </div>

        <!--
           根据session中的用户信息，填写用户的名称和定义提供的页面
        -->

        <div class = "pull-right">
            <ul class = "nav navbar-nav">
                <li class = "dropdown">
                    <a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">
                        <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'guest' ?>
                        <b class = "caret"></b>
                    </a>
                    <ul class = "dropdown-left dropdown-menu ">
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <li><a href = "profile.php">个人信息</a></li>
                            <li><a href = "borrowList.php">借阅信息</a></li>
                            <li><a href = "../index.php">注销</a></li>
                        <?php } else { ?>
                            <li><a href = "../index.php">登录</a></li>

                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>