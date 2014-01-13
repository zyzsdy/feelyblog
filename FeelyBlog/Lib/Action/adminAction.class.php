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
            //清除autosave
            
            $asQuery=M('autosave');
            $asdata['inuse']="0";
            $asStatus=$asQuery->where('id=1')->save($asdata);
            if($asStatus!=1){
                echo "Error #2010: 自动保存清空失败。";
            }
            
            echo $newBid;
        }else{
            echo "false";
        }
    }
    public function deleteblog(){
        adminlogin();
        
        //删除博文
        //获取post数据
        $bid=$this->_post('bid');
        
        //准备删除
        $blogQuery=M('blog');//实例化blog对象
        $deleteStatus=$blogQuery->where('bid='.$bid)->delete();//删除bid对应的数据请求。
        
        if(!$deleteStatus){
            echo "Error #2008： 数据库查询失败。无法找到你需要删除的文章。";
        }else{
            echo "已经成功删除了 #.".$bid." 的".$deleteStatus."篇文章";
        }
    }
    public function setlink(){
        adminlogin();
        
        $linkQuery=M('linktable');//实例化linktable对象
        $linklist=$linkQuery->order('id')->select();
        
        $insert['linklist']=$linklist;
        $insert['active']="SETLINK";
        
        $this->assign($insert);
        $this->display();
    }
    public function addlink(){
        adminlogin();
        //获取post数据
        $linkData['name']=$this->_post('lname');
        $linkData['urlf']=$this->_post('lurlf');
        $linkData['order']=$this->_post('order');
        //存入
        $linkQuery=M('linktable');
        $newId=$linkQuery->add($linkData);
        if($newId){
            echo $newId;
        }else{
            echo "false";
        }
    }
    public function updlink(){
        adminlogin();
        //获取post数据
        $linkData['name']=$this->_post('lname');
        $linkData['urlf']=$this->_post('lurlf');
        $linkData['order']=$this->_post('order');
        $id=$this->_post('lid');
        
        //存入数据
        $linkQuery=M('linktable');//实例化blog对象
        $updStatus=$linkQuery->where('id='.$id)->save($linkData);
        if($updStatus==1){
            echo "更新成功。";
        }else{
            echo "Error #2007： 数据更新失败";
        }
    }
    public function asread(){
        adminlogin();
        
        //自动保存的数据
        $asQuery=M('autosave');
        $data=$asQuery->where('id=1')->limit(1)->select();
        
        echo json_encode($data[0]);
    }
    public function assave(){
        adminlogin();
        
        //处理自动保存
        $asdata['title']=$this->_post('title');
        $asdata['content']=$this->_post('content');
        $asdata['time']=$this->_post('time');
        $asdata['inuse']="1";
        
        $asQuery=M('autosave');
        $asStatus=$asQuery->where('id=1')->save($asdata);
        
        if($asStatus==1){
            echo "success";
        }else{
            echo "Erroe #2009: 无法自动保存。";
        }
    }
}