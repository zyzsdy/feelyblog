//用于Admin页面的脚本文件
//本文件被多个页面引用，所以请仅仅往里写函数好么？

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
                alert("修改成功");
                window.location.href="/blog/"+bid;
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
            }else{
                alert("发布成功！");
                window.location.href="/blog/"+data;
            }
        });
    }
}