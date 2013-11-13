<?php
class loginAction extends Action {
    public function index(){
        $this->display();
    }
    public function dologin(){
        //登录流程
        
        //获得用户名和密码
        $iv['username'] = $this->_post("username");
        $password = $this->_post("password");//加密密码
        $iv['password'] = sha1("100228".sha1($password)."feely");
        
        //查询数据库
        $data = M('user');//实例化user对象
        $urif = $data->where($iv)->select();
        
        //判断登录结果并返回数据
        if($urif){
            //设置session，标识已登录
            session('uid',$urif[0]['uid']);
            session('username',$urif[0]['username']);
            session('login','1');
            //发送成功信号
            echo "success";
        } else {
            echo "Error #1001: Username and password are not match.";
        }
        
    }
    public function unlogin(){
        //注销流程
        
        //判断用户是否登录
        $login_status=session('login');
        if('1'!=$login_status){
            echo "Error #1002: 你本来就没登录，不要在这里试着注销了。";
            return;
        }else{
            session('login','0');
            echo "success";
        }
        
    }
}