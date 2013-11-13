<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--移动设备、IE兼容-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--标题-->
    <title>登录----FeelyBlog</title>

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

    <div class="container-fluid feely-loginwrap">
        <div class="row">
            <div class="span4 offset4 well">
            
            <?php if(($_SESSION['login']) == "1"): ?><h1>你已经登录了，</h1>
                <h1>没必要再登一遍。</h1>
                <button type="button" class="btn btn-info" id="unlogin">注销点我</button>
                <div id="unloginSuccess" class="alert alert-info" style="display: none">
                    已经成功的注销了。<a href="/">返回首页</a>
                </div>
            <?php else: ?>
                <h1>登录</h1>
                <form>
                    <p class="control-group" id="username-ctrl">
                        <label>用户名：</label>
                        <input name="username" type="text" class="span4" id="username">
                    </p>
                    <p class="control-group" id="password-ctrl">
                        <label>密码</label>
                        <input name="password" type="password" class="span4" id="password">
                    </p>
                    <p>
                        <button type="button" class="btn btn-primary" id="loginbtn">登录</button>
                        &nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn" id="button-clear">清空</button>
                    </p>
                </form>
                <div id="nomatch" class="alert alert-error" style="display: none">
                    <strong>错误：</strong>用户名或密码不匹配。
                </div><?php endif; ?>
            </div>
        </div>
    </div>

    <!--载入Javascript脚本-->
    <!--页面底部的脚本载入有利于提高页面载入速度-->
    <script type="text/javascript" src="/static/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function logindo(){
            var username = $("#username").val();
            var password = $("#password").val();
            if(username==""){
                $("#username-ctrl").addClass("warning");
                $("#username").focus().select();
            }else if(password==""){
                $("#password-ctrl").addClass("warning");
                $("#password").focus().select();
            }else{
                $.post("/login/dologin",{
                    "username" : username,
                    "password" : password,
                },function(data){
                    if(data=="success"){
                        window.location.href="/";
                    }else{
                        $("#nomatch").css("display","block");
                    }
                });
            }
        }
        function unlogin(){
            $.get("/login/unlogin",{},function(data){
                if(data=="success"){
                    $("#unloginSuccess").css("display","block");
                }else{
                    $("#unloginSuccess").removeClass("alert-info").addClass("alert-error").html(data).css("display","block");
                }
            });
        }
        $(document).ready(function() {
            $("#loginbtn").click(function(){
                logindo();
            });
            $("#username").keydown(function(e){
		        var key=e.which;
		        if(key==13){
			        logindo();
		        }
	        });
	        $("#password").keydown(function(e){
		        var key=e.which;
		        if(key==13){
			        logindo();
		        }
	        });
            $("#button-clear").click(function(){
                var cfm=confirm("确认清除？");
                if(cfm){
                    $(".row").html("<p style=\"text-align:center\">已经十分尽心尽责的确实的清除了哦。</p>");
                }
            });
            $("#unlogin").click(function(){
                unlogin();
            });
            
        });
    </script>
</body>
</html>