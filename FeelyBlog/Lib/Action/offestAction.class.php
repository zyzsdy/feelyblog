<?php
function subString($str, $start, $length) {
    $i = 0;
    //完整排除之前的UTF8字符
    while($i < $start) {
        $ord = ord($str{$i});
        if($ord < 192) {
            $i++;
        } elseif($ord <224) {
            $i += 2;
        } else {
            $i += 3;
        }
    }
    //开始截取
    $result = '';
    while($i < $start + $length && $i < strlen($str)) {
        $ord = ord($str{$i});
        if($ord < 192) {
            $result .= $str{$i};
            $i++;
        } elseif($ord <224) {
            $result .= $str{$i}.$str{$i+1};
            $i += 2;
        } else {
            $result .= $str{$i}.$str{$i+1}.$str{$i+2};
            $i += 3;
        }
    }
    if($i < strlen($str)) {
        $result .= '...';
    }
    return $result;
}

class offestAction extends Action {
    public function index(){
        //直接访问该模块将被跳转回主页
        header("Location:/");
        exit();
    }
    public function _empty($data){
        $this->showblog($data);
    }
    protected function showblog($offnum){
        $insert['offest']=$offnum;
        $nextOffestNum=$offnum+10;
        $prevOffestNum=$offnum-10;
        if($prevOffestNum<0) $prevOffestNum=0;
        $insert['nextOffestNum']=$nextOffestNum;
        $insert['prevOffestNum']=$prevOffestNum;
        
        $insert['active']="INDEX";
        
        $blogQuery=M('blog');
        $blogData=$blogQuery->order('bid desc')->limit($offnum,10)->select();
        
        foreach($blogData as &$i){
            $i['content']=subString($i['content'],0,500);
        }
        $insert['bloglist']=$blogData;
        
        $this->assign($insert);
        $this->display('showblog');
    }
}