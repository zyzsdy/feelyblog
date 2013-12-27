<?php
//SideBar Widget类
    class sidebarWidget extends Widget {
        public function render($data){
            $linkQuery=M('linktable');
            $linkData=$linkQuery->order('id')->select();
            
            $insert['link']=$linkData;
            
            $content=$this->renderFile('sidebar',$insert);
            return $content;
        }
    }
