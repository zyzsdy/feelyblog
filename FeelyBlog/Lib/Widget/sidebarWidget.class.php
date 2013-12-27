<?php
//SideBar Widget类
    class sidebarWidget extends Widget {
        public function render($data){
            $content=$this->renderFile();
            return $content;
        }
    }
