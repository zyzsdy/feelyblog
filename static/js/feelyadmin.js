//用于Admin页面的脚本文件
//本文件被多个页面引用，所以请仅仅往里写函数好么？

//覆盖Date类的toString()方法
Date.prototype.toString=function(){
    var year=this.getFullYear();
    var mon=this.getMonth()+1;
    var day=this.getDate();
    var hours=this.getHours();
    hours=hours<10?"0"+hours:hours;
    var minute=this.getMinutes();
    minute=minute<10?"0"+minute:minute;
    var second=this.getSeconds();
    second=second<10?"0"+second:second;
    
    return year+"/"+mon+"/"+day+" "+hours+":"+minute+":"+second;
}

//注销登录
function unlogin(){
    $.get("/login/unlogin",{},function(data){
        if(data=="success"){
            window.location.href="/";
        }else{
            alert("注销失败"+data);
        }
    });
}
//预览
function preview(){
    var blogContent=$("#blogContent").val();
    var blogHtml=marked(blogContent);
    $("#preview").html(blogHtml);
}

//通过post提交更新
function updateBlog(){
    //获取值
    var bid=$("#bid").val();
    var title=$("#title").val();
    var content=$("#blogContent").val();
    if(bid==""){
        alert("Error #2002：获取博文id失败。");
    }else if(title==""){
        $("#title-ctrl").addClass("warning");
        $("#title").focus().select();
    }else if(content==""){
        alert("Error #3001: 一篇博客没有正文像话么？");
    }else{
        $.post("/admin/updateblog",{
            "bid" : bid,
            "title" : title,
            "content" : content,
        },function(data){
            if(data=="success"){
                var jpnd=confirm("修改成功！\n\n“确认”跳转到文章页，“取消”留在当前页。");
                if(jpnd){
                    window.location.href="/blog/"+bid;
                }
            }else{
                alert(data);
            }
        });
    }
}
//通过post创建新博文
function createBlog(){
    //获取值
    var title=$("#title").val();
    var content=$("#blogContent").val();
    if(title==""){
        $("#title-ctrl").addClass("warning");
        $("#title").focus().select();
    }else if(content==""){
        alert("Error #3001: 一篇博客没有正文像话么？");
    }else{
        $.post("/admin/createblog",{
            "title" : title,
            "content" : content,
        },function(data){
            if(data=="false"){
                alert("Error #2004: 我们好像没有办法新建一篇文章。");
            }else if(isNaN(data)){
                alert("Error #2004: 我们好像无法新建一篇文章。");
            }else{
                var jpnd=confirm("发布成功！\n\n“确认”跳转到文章页，“取消”留在当前页。");
                if(jpnd){
                    window.location.href="/blog/"+data;
                }
            }
        });
    }
}
//新增友情链接
function addlink(){
    //获取值
    var order=$("#nlk").val();
    var lname=$("#nnm").val();
    var lurlf=$("#nul").val();
    if(order==""){
        $("#nlk").focus().select();
    }else if(lname==""){
        $("#nnm").focus().select();
    }else if(lurlf==""){
        $("#nul").focus().select();
    }else{
        $.post("/admin/addlink",{
            "order" : order,
            "lname" : lname,
            "lurlf" : lurlf,
        },function(data){
            if(data=="false"){
                alert("Error #2006: 无法添加新数据。");
            }else{
                alert("增加成功。");
                window.location.reload();
            }
        });
    }
}
//编辑友情
function updlink(num){
    //获取值
    var order=$("#lk"+num).val();
    var lname=$("#nm"+num).val();
    var lurlf=$("#ul"+num).val();
    if(order==""){
        $("#lk"+num).focus().select();
    }else if(lname==""){
        $("#nm"+num).focus().select();
    }else if(lurlf==""){
        $("#ul"+num).focus().select();
    }else{
        $.post("/admin/updlink",{
            "lid" : num,
            "order" : order,
            "lname" : lname,
            "lurlf" : lurlf,
        },function(data){
            alert(data);
            window.location.reload();
        });
    }
}

//删除博文
function deleteBlog(bid){
    var conf=confirm("真的要删除 #."+bid+" 博文么？");
    if(conf){
        $.post("/admin/deleteblog",{
            "bid" : bid,
        },function(data){
            alert(data);
            window.location.reload();
        });
    }
}

//读取Autosave
function asread(){
    $.get("/admin/asread",{},function(d){
        data=JSON.parse(d);
        if(data.inuse==1){
            var astime=new Date();
            astime.setTime(data.time);
            $("#blogContent").val(data.content);
            $("#title").val(data.title);
            $("#asread-info").html("已载入于 "+astime.toString()+" 自动保存的内容。");
            $("#asread").fadeIn(600);
        }
    });
}

//Autosave
function assave(){
    var d=new Date();
    var tp=d.getTime();
    var title=$("#title").val();
    var content=$("#blogContent").val();
    $.post("/admin/assave",{
        "time": tp,
        "title" : title,
        "content" : content,
    },function(data){
        var info="";
        if(data=="success"){
            info="已于 "+d.toString()+" 自动保存。";
        }else{
            info=data;
        }
        $("#assave-info").html(info);
        $("#assave").fadeIn(500);
        var c=setTimeout(function(){
            $("#assave").fadeOut(500);
        },2000);
    });
}