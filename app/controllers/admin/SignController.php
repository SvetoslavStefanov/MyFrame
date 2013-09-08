<?
class admin_SignController extends admin_BaseController{
    var $before = array(
        'setUser' => array('in', 'login')
        );

    protected function setUser(){
        $this->sign = new Sign();
    }

    public function inAction(){
        if (isset($this->adminUser) && ($this->adminUser instanceof ActiveRecord)) {
            $this->redirect('Dashboard/home');
        }
        $this->title = 'Administration Login';
    }

    public function loginAction(){
        if (isset($this->adminUser) && ($this->adminUser instanceof ActiveRecord)) {
            $this->back();
        }

        if($this->sign = $this->sign->loginValidate($this->post("Sign"))){
            $this->setUserCookie();
            $this->redirect('Dashboard/home');
        }else{
            $this->action('in');
        }
    }

    public function outAction(){
        //session_destroy();
        unset($_SESSION['isAdmin']);
        setcookie('user_cookie', '',  time() -1, '/');
        $this->redirect("");
    }

    protected function setUserCookie ()
    {
        $userData = serialize(array('user_id' => $this->sign->id));

        setcookie('user_cookie', $userData, time() + 86400, '/');
        $_SESSION['isAdmin'] = $this->sign->id;
    }
}