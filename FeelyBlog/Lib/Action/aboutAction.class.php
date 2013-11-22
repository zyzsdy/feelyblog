<?php
class aboutAction extends Action {
    public function index(){
        $insert['active']="ABOUT";
        $this->assign($insert);
        $this->display();
    }
}