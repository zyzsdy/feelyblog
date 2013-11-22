<?php
//判断用户是否登录。如果没有登录，则不能进入此页面。
function adminlogin(){
    $login_statu=session('login');
    if($login_statu!="1"){
        header("Location: /login/");
        exit();
    }
}
class adminAction extends Action {
    public function index(){
        adminlogin();
        
        $this->assign('active','MAIN');
        $this->display();
    }
    public function edit($id=0){
        adminlogin();
        //如果没有传入id，就显示所有博文列表
        if($id==0){
            $this->listed();
            return;
        }
        
        //传入了id，打开编辑页。
        $insert['editid']=$id;
        $insert['active']="EDIT";
        
        $blogQuery=M('blog');//实例化blog对象
        $blogData=$blogQuery->find($id);
        if(!$blogData){
            exit("Error #2001: 真的有这篇博文么？can not find a content where id ".$id);
        }
        $blogData['content']=stripslashes($blogData['content']);
        $insert['be']=$blogData;
        $this->assign($insert);
        $this->display();
    }
    private function listed(){
        adminlogin();
        $blogQuery=M('blog');//实例化blog对象
        $blogData=$blogQuery->field('content',true)->order('bid desc')->select();
        $insert['bloglist']=$blogData;
        
        $insert['active']="EDIT";
        $this->assign($insert);
        $this->display('listed');
    }
    public function updateblog(){
        adminlogin();
        //获取post数据
        $bid=$this->_post('bid');
        $blogData['title']=$this->_post('title');
        $blogData['content']=$this->_post('content');
        
        //存入数据
        $blogQuery=M('blog');//实例化blog对象
        $updStatus=$blogQuery->where('bid='.$bid)->save($blogData);
        if($updStatus==1){
            echo "success";
        }else{
            echo "Error #2003： 无法更新博文内容。can not update the content of this blog.";
        }
    }
    public function create(){
        adminlogin();
        
        $this->assign('active','CREATE');
        $this->display();
    }
    public function createblog(){
        adminlogin();
        
        //获取post数据
        $blogData['title']=$this->_post('title');
        $blogData['content']=$this->_post('content');
        
        //存入数据
        $blogQuery=M('blog');//实例化blog对象
        $newBid=$blogQuery->add($blogData);
        
        if($newBid){
            echo $newBid;
        }else{
            echo "false";
        }
    }
}