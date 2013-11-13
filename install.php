<?php
//获取当前step
$nowStep=$_REQUEST['step'];

//初始显示 step为空

if($nowStep==""){
    $nowStep=0;
}else if($nowStep==1){
    //第一步操作：
    
    //输出HTML头
    echo '<!doctype html><html><head><meta charset="utf-8"><title>STEP1--Install</title></head><body>';
    
    //获取数据库信息
    echo '<p>正在获取数据库信息...</p>';
    $dburl=$_POST['dburl'];
    $dbport=$_POST['dbport'];
    $dbuser=$_POST['dbuser'];
    $dbpass=$_POST['dbpass'];
    $dbname=$_POST['dbname'];
    $dbprefix=$_POST['dbprefix'];
    //连接数据库
    echo '<p>正在创建数据库连接...</p>';
    $sqlserver=$dburl.":".$dbport;
    $con=mysql_connect($sqlserver,$dbuser,$dbpass)or die('<p>连接数据库服务器失败。'.mysql_error().'</p></body></html>');
    mysql_select_db($dbname)or die ('<p>连接数据库失败。'.mysql_error().'</p></body></html>'); 
    mysql_query("SET NAMES UTF8");
    echo '<p>成功创建数据库连接。</p>';
    //创建数据库
    
    //创建user
    echo '<p>正在创建数据表'.$dbprefix.'user</p>';
    $createUserStr="create table ".$dbprefix."user
(
uid int AUTO_INCREMENT,
PRIMARY KEY(uid),
time timestamp default CURRENT_TIMESTAMP,
username varchar(100) NOT NULL,
password varchar(70) NOT NULL,
email varchar(100)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    mysql_query($createUserStr,$con) or die('<p>'.$dbprefix.'user数据表创建失败。'.mysql_error().'</p></body></html>');
    echo '<p>'.$dbprefix.'user数据表创建成功。</p>';
    
    //创建blog
    echo '<p>正在创建数据表'.$dbprefix.'blog</p>';
    $createBlogStr="create table ".$dbprefix."blog
(
bid int AUTO_INCREMENT,
PRIMARY KEY(bid),
time timestamp default CURRENT_TIMESTAMP,
title text,
content text,
comment text
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    mysql_query($createBlogStr,$con) or die('<p>'.$dbprefix.'blog数据表创建失败。'.mysql_error().'</p></body></html>');
    echo '<p>'.$dbprefix.'blog数据表创建成功。</p>';
    
    //创建comment
    echo '<p>正在创建数据表'.$dbprefix.'comment</p>';
    $createCmtStr="create table ".$dbprefix."comment
(
cid int AUTO_INCREMENT,
PRIMARY KEY(cid),
posttime timestamp default CURRENT_TIMESTAMP,
username varchar(100),
email varchar(100),
siteurl varchar(100),
content text
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    mysql_query($createCmtStr,$con) or die('<p>'.$dbprefix.'comment数据表创建失败。'.mysql_error().'</p></body></html>');
    echo '<p>'.$dbprefix.'comment数据表创建成功。</p>';
    //生成配置文件
    echo '<p>正在生成配置文件...</p>';
    $configStr="<?php\nreturn array(\n'DB_DSN' => 'mysql://".$dbuser.":".$dbpass."@".$dburl.":".$dbport."/".$dbname."',\n'DB_PREFIX' => '".$dbprefix."',\n);\n?>";
    echo '<p>正在创建配置文件...</p>';
    $config_file=fopen("./FeelyBlog/Conf/config.php","w") or die("<p>配置文件创建失败。</p></body></html>");
    fwrite($config_file,$configStr) or die("<p>配置文件写入失败。</p></body></html>");
    echo "<p>创建配置文件成功...</p>";
    fclose($config_file) or die("<p>配置文件关闭失败。</p></body></html>");
    
    
    //数据库以及配置文件创建完成
    mysql_close($con) or die("<p>数据库连接关闭失败。</p></body></html>");
    echo '<p>数据库以及配置文件创建完成。</p><p>单击<a href="install.php?step=2">下一步</a>继续安装。</p></body></html>';
    exit();
}else if($nowStep==2){
    //第二步，什么也不做，显示模板就行
    ;
}else if($nowStep==3){
    //第三步，向数据库写入用户名及密码。
    
    //输出HTML头
    echo '<!doctype html><html><head><meta charset="utf-8"><title>STEP3--Install</title></head><body>';
    
    //获取post数据
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password2=$_POST['password2'];
    $email=$_POST['email'];
    
    if($username==""){
        echo '<p>用户名可不能为空哦。。</p><p><a href="install.php?step=2>点击返回</a></p></body></html>';
        exit();
    }else if($password!=$password2){
        echo '<p>两次密码不一致。。</p><p><a href="install.php?step=2>点击返回</a></p></body></html>';
        exit();
    }
    
    //读取配置文件
    $config_file=fopen("./FeelyBlog/Conf/config.php","r") or die("<p>配置文件打开失败。</p></body></html>");

    $sql=fscanf($config_file,"%s");//取得设置
    $sql=fscanf($config_file,"%s");
    $sql=fscanf($config_file,"'DB_DSN' => 'mysql://%s',");
    $prefix=fscanf($config_file,"'DB_PREFIX' => '%s',");
    fclose($config_file) or die("<p>配置文件关闭失败。</p></body></html>");
    
    $fci=strpos($sql[0],":",0);
    $fai=strpos($sql[0],"@",0);
    $fsi=strpos($sql[0],"/",0);
    $kii=strpos($sql[0],"'",0);
    
    $mst[0]=substr($sql[0],0,$fci);
    $mst[1]=substr($sql[0],$fci+1,$fai-$fci-1);
    $mst[2]=substr($sql[0],$fai+1,$fsi-$fai-1);
    $mst[3]=substr($sql[0],$fsi+1,$kii-$fsi-1);
    
    $pti=strpos($prefix[0],"'",0);
    $mst[4]=substr($prefix[0],0,$pti);
    
    //连接数据库。
    echo '<p>正在创建数据库连接...</p>';
    $con=mysql_connect($mst[2],$mst[0],$mst[1])or die('<p>连接数据库服务器失败。'.mysql_error().'</p></body></html>');
    mysql_select_db($mst[3])or die ('<p>连接数据库失败。'.mysql_error().'</p></body></html>'); 
    mysql_query("SET NAMES UTF8");
    echo '<p>成功创建数据库连接。</p>';
    
    //创建用户。
    echo '<p>正在创建用户...</p>';
    $password=sha1("100228".sha1($password)."feely");
    $queryStr="insert into ".$mst[4]."user (username, password, email) values('$username', '$password', '$email')";
    mysql_query($queryStr) or die('<p>创建用户失败。'.mysql_error().'</p></body></html>');
    echo '<p>创建用户成功。</p>';
    
    mysql_close($con) or die("<p>数据库连接关闭失败。</p></body></html>");
    echo '<p>用户创建完成。</p><p>还有最后一步哦。单击<a href="install.php?step=4">下一步</a>继续安装。</p></body></html>';
    exit();
}else if($nowStep==4){
    //完成安装
    ;
}else{
    echo "<body>参数错误。</body></html>";
    exit();
}




$stepShow[1]=$stepShow[3]="<body>参数错误。</body></html>";
$stepShow[0]='<body>
<p>这里是传说中的FeelyBlog安装程序。</p>
<p>众所周知，开发者比较懒【← ←不带你这么自我吐槽的。。。</p>
<p>&nbsp;</p>
<p>所以，安装程序木有界面是很正常的。</p>
<p>&nbsp;</p>
<p>您现在正要安装FeelyBlog……</p>
<p>FeelyBlog是一个PHP+Mysql的轻量级个人博客。</p>
<p>开源而且免费，使用Apache Licence 2.0协议授权。</p>
<p>GitHub：http://github.com/zyzsdy/feelyblog</p>
<p>================================================</p>
<p>第一步：我们需要你的MySQL信息来开始配置。</p>
<form action="install.php" method="post" name="form1" id="form1">
    <p>
        <label for="dburl">数据库地址：</label>
        <input name="dburl" type="text" id="dburl" value="localhost">
    </p>
    <p>请仅仅写IP地址或是域名，不要带上http://这种东西，记住，本安装程序不会对你输入的信息做任何验证，我们完全相信你。</p>
    <p>
        <label for="dbport">数据库端口：</label>
        <input name="dbport" type="text" id="dbport" value="3306">
    </p>
    <p>默认3306。请不要填写奇怪的东西。</p>
    <p>
        <label for="dbuser">用户名：</label>
        <input type="text" name="dbuser" id="dbuser">
    </p>
    <p>
        <label for="dbpass">密码：</label>
        <input type="text" name="dbpass" id="dbpass">
    </p>
    <p>【没有密码就留空吧】。。。虽然我很想这么说，但是还是麻烦填个密码吧。</p>
    <p>
        <label for="dbname">数据库名：</label>
        <input type="text" name="dbname" id="dbname">
    </p>
    <p>请填写你的mysql数据库名，如果你不知道那是什么，请联系你的网络管理员。本程序并不会自动帮你新建数据库。</p>
    <p>
        <label for="dbprefix">数据表前缀：</label>
        <input name="dbprefix" type="text" id="dbprefix" value="feely_">
    </p>
    <p>仅仅在你需要在一个数据库上安装多个本程序时，请确保它们的前缀互不相同。否则，别改！</p>
    <p>
        <input name="step" type="hidden" id="step" value="1">
    </p>
    <p>好了，我们目前就只需要这么多信息。请点击“下一步”按钮。</p>
    <p>
        <input type="submit" name="submit" id="submit" value="下一步">
    </p>
</form>
<p>&nbsp;</p>
</body>';
$stepShow[2]='<body>
<p>第二步：</p>
<p>终于进入第二步了，下面请设置你的博客用户名。</p>
<p>以后请使用这个用户名进行你博客的编辑工作哦。</p>
<form action="install.php?step=3" method="post" name="form1" id="form1">
    <p>
        <label for="username">用户名：</label>
        <input type="text" name="username" id="username">
        最好不要有中文，当然，其实我管不着的。</p>
    <p>
        <label for="password">密码：</label>
        <input type="password" name="password" id="password">
    </p>
    <p>
        <label for="password2">重复密码：</label>
        <input type="password" name="password2" id="password2">
    </p>
    <p>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">
    不填也行，反正用不到。</p>
    <p>&nbsp;</p>
    <p>填好了就点下一步哦。</p>
    <p>
        <input type="submit" name="submit" id="submit" value="下一步">
    </p>
</form>
</body>';
$stepShow[4]='<body>
<p>恭喜！安装完成！ </p>
<p>看到这个页面说明已经安装结束了。</p>
<p>但是！还剩<strong>最后一步</strong>！</p>
<p>非常重要！！！！！！！！！！</p>
<p>&nbsp;</p>
<p><strong style="font-size: 24px">请删除根目录下的install.php</strong>（本文件）！！！</p>
<p>&nbsp;</p>
<p>（弱弱的说：否则后果不堪设想。。。。）</p>
<p>&nbsp;</p>
<p>然后，享受你的FeelyBlog吧。</p>
</body>';
?>
<!--=============以下是模板部分===============-->

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Feely——Install</title>
</head>
<?php echo $stepShow[$nowStep]?>
</html>
