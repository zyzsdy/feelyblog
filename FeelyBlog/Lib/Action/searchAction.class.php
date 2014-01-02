<?php
function get_sign($params, $key){
    $_data = array();
    ksort($params);
    reset($params);
    foreach ($params as $k => $v){
        $_data[] = $k . '=' . rawurlencode($v);
    }
    $_sign = implode('&', $_data);
    return array(
        'sign' => strtolower(md5($_sign . $key)),
        'params' => $_sign,
    );
}
class searchAction extends Action {
    public function index(){
        $insert['active']="SEARCH";
        $this->assign($insert);
        $this->display();
    }
    public function biliapi($avid,$page){
        $params['type']='json';
        $params['appkey']='7f6598547dd20cd1';
        $params['id']=$avid;
        $params['page']=$page;
        $rt=get_sign($params,'c55e0a94dc9a6b4a44be537a730cd239');
        $paramStr=$rt['params']."&sign=".$rt['sign'];
        
        $url="http://api.bilibili.tv/view?".$paramStr;
        $link=curl_init();
        curl_setopt($link, CURLOPT_URL, $url);
        curl_setopt($link, CURLOPT_PORT, 80);
        curl_setopt($link, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($link, CURLOPT_USERAGENT, "FeelyBlog/beta 1.0 (zyzsdy@gmail.com)");
        $result=curl_exec($link);
        curl_close($link);
        
        $info=json_decode($result);
        
        //$htmlTpl="<embed style=\"width:970px;height:536px;\" pluginspage=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\" allowfullscreeninteractive=\"true\" flashvars=\"cid=%s&aid=%s\" src=\"https://static-s.bilibili.tv/play.swf\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" quality=\"high\">";
        $htmlTpl="<iframe height=\"536\" width=\"970\" src=\"https://secure.bilibili.tv/secure,cid=%s&aid=%s\" scrolling=\"no\" border=\"0\" frameborder=\"no\" framespacing=\"0\" onload=\"window.securePlayerFrameLoaded=true\"></iframe>";
        
        if($info->code!=-403&&$info->code!=-404&&$info->code!=-400){
            printf($htmlTpl,$info->cid,$avid);
        }else{
            echo "视频拉取失败。";
        }
    }
}