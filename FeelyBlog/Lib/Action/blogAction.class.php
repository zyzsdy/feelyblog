<?php
function utf8Substr($str, $from, $len)
{
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}
class blogAction extends Action {
    public function index(){
        $insert['active']='BLOG';
        $blogQuery=M('blog');//实例化blog对象
        $blogData=$blogQuery->order('bid desc')->select();
        
        foreach($blogData as &$i){
            $subContent=utf8Substr($i['content'],0,140);
            $i['content']=$subContent."...";
        }
        
        $insert['bloglist']=$blogData;
        
        $this->assign($insert);
        $this->display();
    }
    public function _empty($data){
        $this->showblog($data);
    }
    protected function showblog($bid){
        //创建查询条件
        $iv['bid']=$bid;
        $blogInsert['blogId']=$bid;
        
        //查询数据库
        $blogQuery=M('blog');//实例化blog对象
        $blogData=$blogQuery->where($iv)->select();
        
        if(!$blogData){
            echo "Error #2001: Can not fetch the content of blog id ".$bid;
            return;
        }else{
            $blogInsert['createTime']=$blogData[0]['time'];
            $blogInsert['blogTitle']=$blogData[0]['title'];
            $blogInsert['blogContent']=$blogData[0]['content'];
            $commentStr=$blogData[0]['comment'];//评论id字符串
        }
        $commentArr=explode(",",$commentStr);//将评论id字符串转换为数组
        
        //获取上一篇和下一篇标题
        $blogInsert['previd']=$bid+1;
        $blogInsert['nextid']=$bid-1;
        $blogInsert['prevtitle']=$blogQuery->where('bid='.($bid+1))->getField('title');
        $blogInsert['nexttitle']=$blogQuery->where('bid='.($bid-1))->getField('title');
        
        $commentQuery=M('comment');//实例化comment对象
        for($i=0;$commentArr[$i]!="";$i++){
            $cid=$commentArr[$i];
            $cmtArr[$i]=$commentQuery->find($cid);
        }
        $blogInsert['comment']=$cmtArr;
        $blogInsert['active']="BLOG";
        
        $this->assign($blogInsert);
        $this->display('showblog');
    }
    public function comsub(){
        //获取post数据
        $bid=$this->_post('bid');
        $comment['username']=$this->_post('username');
        $comment['email']=$this->_post('email');
        $comment['siteurl']=$this->_post('siteurl');
        $comment['content']=$this->_post('content');
        
        
        if($bid==""){
            exit("Error #2002");
        }
        //获得当前评论列表
        $blogQuery=M('blog');
        $oldStr=$blogQuery->where('bid='.$bid)->getField('comment');
        
        $commentQuery=M('comment');
        $comId=$commentQuery->add($comment);
        if(!$comId){
            echo "Error #2005： 评论添加失败。";
        }else{
            $newStr['comment']=$oldStr.$comId.",";
            $updStatus=$blogQuery->where('bid='.$bid)->save($newStr);
            if($updStatus==1){
                echo "success";
            }else{
                echo "Error #2006: 评论插入失败。";
            }
        }
    }
}