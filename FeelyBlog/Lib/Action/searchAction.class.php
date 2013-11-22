<?php
class searchAction extends Action {
    public function index(){
        $insert['active']="SEARCH";
        $this->assign($insert);
        $this->display();
    }
}