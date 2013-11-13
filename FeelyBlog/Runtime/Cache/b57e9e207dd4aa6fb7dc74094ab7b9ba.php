<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--移动设备、IE兼容-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--标题-->
    <title>Admin----FeelyBlog</title>

    <!--载入Bootstrap样式文档-->
    <link type="text/css" href="/static/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link type="text/css" href="/static/css/feelystyle.css" rel="stylesheet" media="screen">
    <link type="text/css" href="/static/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">

    <!--低版本IE兼容-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
·   <!--导航条-->
    <div class="navbar navbar-inverse navbar-fixed-top feely-navbar" role="navigation">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="/admin">FeelyBlogAdminPanel</a>
                <ul class="nav">
                    <li><a href="/Index">返回主页</a></li>
                </ul>
                <ul class="nav pull-right">
                    <?php if(($_SESSION['login']) == "1"): ?><li><a href="/admin/"><?php echo (session('username')); ?></a></li>
                        <li><a href="#" id="unlogin">注销</a></li>
                    <?php else: ?>
                        <li><a href="/login/">登录</a></li><?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <!--正常界面-->
    <div class="container-fluid">
        <div class="row">
            <div class="span2">
                功能
            </div>
            <div class="span10">
            编辑
            </div>
        </div>
    </div>


<!--导入页面底部模板
    <!--页面底部-->
    <footer class="modal-footer">
        <p class="pull-left"><a href="#top">返回顶部</a></p>
        <p>FeelyBlog powered by FeelyBlog alpha 0.1</p>
        <p>轻量级个人博客不是越轻越好的，但是FeelyBlog试图平衡这些。</p>
        <p>Created by Zyzsdy (Email: zyzsdy#gmail)</p>
    </footer> <!--页面底部
-->
    <!--页面内容结束-->

    <!--载入Javascript脚本-->
    <!--页面底部的脚本载入有利于提高页面载入速度-->
    <script type="text/javascript" src="/static/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
    <!--其他脚本-->
    <script type="text/javascript">
    function unlogin(){
        $.get("/login/unlogin",{},function(data){
            if(data=="success"){
                window.location.href="/";
            }else{
                alert("注销失败"+data);
            }
        });
    }
    $(document).ready(function() {
        $("#unlogin").click(function(){
            unlogin();
        });
    });
    </script>
</body>
</html>