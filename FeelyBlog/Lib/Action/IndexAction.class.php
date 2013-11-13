<?php
class IndexAction extends Action {
    public function index(){
        $indexInsert['active']="INDEX";
        
        
        $this->assign($indexInsert);
        $this->display();
    }
}