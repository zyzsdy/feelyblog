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
class IndexAction extends Action {
    public function index(){
        $insert['active']="INDEX";
        
        $blogQuery=M('blog');
        $blogData=$blogQuery->order('bid desc')->limit(10)->select();
        
        foreach($blogData as &$i){
            $i['content']=subString($i['content'],0,500);
        }
        $insert['bloglist']=$blogData;
        
        $this->assign($insert);
        $this->display();
    }
}