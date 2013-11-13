<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--移动设备、IE兼容-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--标题-->
    <title><?php echo ($blogTitle); ?>----FeelyBlog</title>

    <!--载入Bootstrap样式文档-->
    <link type="text/css" href="/static/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link type="text/css" href="/static/css/feelystyle.css" rel="stylesheet" media="screen">
    <link type="text/css" href="/static/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link type="text/css" href="/static/css/google-code-prettify/prettify.css" rel="stylesheet">

    <!--低版本IE兼容-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body onLoad="prettyPrint()">

    <!--页面内容开始-->
    
<!--加载外部模板
    <!--导航部分-->
    <div class="navbar navbar-inverse navbar-fixed-top feely-navbar" role="navigation">
        <div class="navbar-inner">
            <!--桌面版导航-->
            <div class="container hidden-phone">
                <a class="brand" href="/">FeelyBlog</a>
                <ul class="nav">
                <?php if(($active) == "INDEX"): ?><li class="active"><a href="/">首页</a></li>
                <?php else: ?>
                    <li><a href="/">首页</a></li><?php endif; ?>
                
                <?php if(($active) == "BLOG"): ?><li class="active"><a href="/blog/">文章</a></li>
                <?php else: ?>
                    <li><a href="/blog/">文章</a></li><?php endif; ?>
                
                <?php if(($active) == "PAGE"): ?><li class="active"><a href="/page/">页面</a></li>
                <?php else: ?>
                    <li><a href="/page/">页面</a></li><?php endif; ?>
                
                <?php if(($active) == "VALUE"): ?><li class="active"><a href="/about/">关于</a></li>
                <?php else: ?>
                    <li><a href="/about/">关于</a></li><?php endif; ?>
                    
                </ul>
                <ul class="nav pull-right">
                    <?php if(($_SESSION['login']) == "1"): ?><li><a href="/admin/"><?php echo (session('username')); ?></a></li>
                        <li><a href="#" id="unlogin">注销</a></li>
                    <?php else: ?>
                        <li><a href="/login/">登录</a></li><?php endif; ?>
                </ul>
            </div>
            <!--手机版导航-->
            <div class="container visible-phone">
                <a class="brand" href="/">FeelyBlog</a>
                <div class="btn-group pull-right">
                    <a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" herf="#">
                        <i class="icon-list"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if(($active) == "INDEX"): ?><li class="active"><a href="/">首页</a></li>
                        <?php else: ?>
                            <li><a href="/">首页</a></li><?php endif; ?>
                
                        <?php if(($active) == "BLOG"): ?><li class="active"><a href="/blog/">文章</a></li>
                        <?php else: ?>
                            <li><a href="/blog/">文章</a></li><?php endif; ?>
                
                        <?php if(($active) == "PAGE"): ?><li class="active"><a href="/page/">页面</a></li>
                        <?php else: ?>
                            <li><a href="/page/">页面</a></li><?php endif; ?>
                
                        <?php if(($active) == "VALUE"): ?><li class="active"><a href="/about/">关于</a></li>
                        <?php else: ?>
                            <li><a href="/about/">关于</a></li><?php endif; ?>
                        
                        <li class="divider"></li>
                        <?php if(($_SESSION['login']) == "1"): ?><li><a href="#" id="unlogin2">注销</a></li>
                        <?php else: ?>
                            <li><a href="/login/">登录</a></li><?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
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
        $("#unlogin2").click(function(){
            unlogin();
        });
    });
    </script>   <!--导航栏
    <!--TopBanner-->
    <div class="container-fluid feely-topbanner">
        <div class="row-fluid">
            <h2>FeelyBlog</h2>
            <p>飞翔的鱼仍在天空飞翔，这是我所不知道的那段时光里，这个世界所做出的改变。</p>
            <p>不知不觉，我似乎错过了许多，而如今却只能跟随。</p>
        </div>
    </div>    <!--TopBanner
-->
    
    <!--主页面内容-->
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span9">
                <h3><?php echo ($blogTitle); ?></h3>
                <p class="feely-quote"><?php echo ($createTime); ?></p>
                <hr class="divider">
                <div id="blogContainer">
                <?php echo ($blogContent); ?>
                </div>
                
                
                <!--上一页/下一页按钮-->
                <hr class="divider">
                <ul class="unstyled">
                    <li><a href="<?php echo ($previd); ?>">上一篇：<?php if(empty($prevtitle)): ?>没有了。<?php else: echo ($prevtitle); endif; ?></a></li>
                    <li><a href="<?php echo ($nextid); ?>">下一篇：<?php if(empty($nexttitle)): ?>没有了。<?php else: echo ($nexttitle); endif; ?></a></li>
                </ul>
                
                <!--评论部分-->
                <br>
                <hr class="divider">
                <h4>评论：</h4>
                <?php if(is_array($comment)): $i = 0; $__LIST__ = $comment;if( count($__LIST__)==0 ) : echo "暂时还没有人评论哦。" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="row-fluid">
                        <div class="span2">
                            <p>#<?php echo ($i); ?></p>
                            <p><a href="<?php echo ($vo["siteurl"]); ?>"><?php echo ($vo["username"]); ?></a></p>
                            <p class="feely-quote"><?php echo ($vo["posttime"]); ?></p>
                        </div>
                        <div class="span7" id="commentContent">
                            <?php echo ($vo["content"]); ?>
                        </div>
                    </div><hr class="divider"><?php endforeach; endif; else: echo "暂时还没有人评论哦。" ;endif; ?>

                
                <!--提交评论表单部分-->
                <h4>留下你的评论：</h4>
                <form>
                    <p>昵称：<input type="text" class="span3"></p>
                    <p>E-mail：<input type="email" class="span3"><span>我们不会泄露你的E-mail地址。</span></p>
                    <p>个人主页：<input type="text" class="span3"><span>将会在评论列表中展示链接。</span></p>
                    <p>
                        <label>评论内容：</label>
                        <textarea class="span7" rows="10"></textarea>
                    </p>
                    <p><button type="button" class="btn">提交</button></p>
                </form>
                
                
            </div>
            
<!--导入右侧边栏模板
            <!--右侧边栏-->
            <div class="span3 hidden-phone">
                <h3 class="page-header">Feathers</h3>
                <p>也许有一天，这就是没用的东西。</p>
            </div> <!--右侧边栏
-->
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
    <script type="text/javascript" src="/static/google-code-prettify/prettify.js"></script>
    <script type="text/javascript" src="/static/js/marked.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        var contentStr=$("#blogContainer").html();
        var contentHtml=marked(contentStr);
        $("#blogContainer").html(contentHtml);
    });
    </script>
    <!--其他脚本-->
</body>
</html>